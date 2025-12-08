<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\User;
use App\Models\Kunjungan;
use App\Models\KategoriPihak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Http;

class TamuController extends Controller
{
    /**
     * Service untuk kirim WhatsApp via Fonnte
     */
    protected WhatsAppService $wa;

    public function __construct(WhatsAppService $wa)
    {
        $this->wa = $wa;
    }

    public function landing()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // TAMU
            'nama'              => ['required','string','max:150'],
            'email'             => ['required','email','max:150'],
            'no_hp'             => ['required','string','max:30'],
            'instansi_kategori' => ['required','in:opd,lembaga,perseorangan,ormas'],
            'instansi_nama'     => ['required','string','max:200'],

            // KATEGORI & SUB KATEGORI
            'kategori_pihak_top'=> ['required','in:pimpinan,akd,sekretariat'],
            'kategori_pihak'    => ['required','string','max:64'],

            // KUNJUNGAN
            'jumlah'            => ['required','integer','min:1','max:50'],
            'keperluan'         => ['required','string'],
            'tanggal_kunjungan' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'waktu_kunjungan'   => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    // Validasi format waktu
                    if (!preg_match('/^(0[8-9]|1[0-6]):[0-5][0-9]$/', $value)) {
                        $fail('Waktu kunjungan harus antara 08:00 - 16:30');
                        return;
                    }
                    
                    $hour = (int) substr($value, 0, 2);
                    $minute = (int) substr($value, 3, 2);
                    
                    // Validasi jam kerja 08:00 - 16:30
                    if ($hour < 8 || $hour > 16 || ($hour == 16 && $minute > 30)) {
                        $fail('Waktu kunjungan harus antara 08:00 - 16:30');
                        return;
                    }
                    
                    // Validasi untuk hari ini - minimal 1 jam dari sekarang
                    $tanggal = Carbon::createFromFormat('Y-m-d', $request->tanggal_kunjungan);
                    if ($tanggal->isToday()) {
                        $now = Carbon::now();
                        $waktuKunjungan = Carbon::createFromFormat('H:i', $value);
                        $minTime = $now->copy()->addHour();
                        
                        if ($waktuKunjungan->lt($minTime)) {
                            $fail('Untuk hari ini, waktu kunjungan minimal 1 jam dari sekarang (' . $minTime->format('H:i') . ')');
                        }
                    }
                }
            ],

            // DOKUMEN - nullable
            'dokumen'           => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
        ], [
            'tanggal_kunjungan.after_or_equal' => 'Tanggal kunjungan tidak boleh hari kemarin',
            'waktu_kunjungan.date_format' => 'Format waktu tidak valid',
        ]);

        return DB::transaction(function () use ($validated, $request) {

            // 1) Tamu: gunakan kombinasi email + no_hp untuk dedup
            $tamu = Tamu::firstOrCreate(
                ['email' => $validated['email'], 'no_hp' => $validated['no_hp']],
                [
                    'nama' => $validated['nama'],
                    'instansi_kategori' => $validated['instansi_kategori'],
                    'instansi_nama'     => $validated['instansi_nama'],
                ]
            );

            // 2) Cari/insert baris kategori_pihak (sub kategori)
            $kp = KategoriPihak::where('kategori', $validated['kategori_pihak_top'])
                    ->where('sub_kategori', $validated['kategori_pihak'])
                    ->first();

            if (!$kp) {
                $kp = KategoriPihak::create([
                    'kategori'     => $validated['kategori_pihak_top'],
                    'sub_kategori' => $validated['kategori_pihak'],
                    'subnama'      => $this->labelFromCode($validated['kategori_pihak']),
                    'is_active'    => true,
                ]);
            }

            // 3) File upload handling
            $dokumenPath = null;

            if ($request->hasFile('dokumen')) {
                $file = $request->file('dokumen');
                
                if ($file->isValid()) {
                    try {
                        // Manual file handling
                        $filename = 'kunjungan_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $directory = storage_path('app/public/kunjungan');
                        
                        // Buat directory jika belum ada
                        if (!file_exists($directory)) {
                            mkdir($directory, 0755, true);
                        }
                        
                        // Pindahkan file manual
                        if ($file->move($directory, $filename)) {
                            $dokumenPath = 'storage/kunjungan/' . $filename;
                        }
                    } catch (\Exception $e) {
                        // Continue without file - dokumen is nullable
                    }
                }
            }

            // 4) Buat Kunjungan
            $kunjungan = Kunjungan::create([
                'tamu_id'           => $tamu->id,
                'kategori_pihak_id' => $kp->id,
                'jumlah_peserta'    => $validated['jumlah'],
                'keperluan'         => $validated['keperluan'],
                'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
                'waktu_kunjungan'   => $validated['waktu_kunjungan'],
                'dokumen'           => $dokumenPath,
                'status_sekarang'   => 'menunggu',
            ]);

            // 5) Kirim notifikasi WhatsApp ke host (jika ada)
            $this->sendWhatsAppNotificationToHosts($kunjungan);

            return redirect()
                ->route('welcome')
                ->with('success', 'Pengajuan kunjungan berhasil dikirim. Status: menunggu.');
        });
    }

    /**
     * Kirim notifikasi WhatsApp ke user dengan role 'host'
     * MENGGUNAKAN SATU METODE SAJA UNTUK MENCEGAH DUPLIKASI
     */
    private function sendWhatsAppNotificationToHosts(Kunjungan $kunjungan)
    {
        try {
            Log::info('🔔 === [TAMU CONTROLLER] START WhatsApp Notification ===');
            Log::info('📝 Kunjungan ID: ' . $kunjungan->id);
            Log::info('👤 Tamu: ' . ($kunjungan->tamu->nama ?? 'Unknown'));
            
            // 1. Ambil SEMUA host aktif
            $hosts = User::where('role', User::ROLE_HOST)
                        ->where('is_active', true)
                        ->get(['id', 'full_name', 'no_wa', 'email']);
            
            Log::info('📊 Total active hosts found: ' . $hosts->count());
            
            if ($hosts->isEmpty()) {
                Log::warning('⚠️ NO ACTIVE HOSTS FOUND!');
                return;
            }
            
            // 2. Filter host dengan WhatsApp valid
            $validHosts = [];
            $invalidHosts = [];
            
            foreach ($hosts as $host) {
                $phone = $this->formatPhoneForWhatsApp($host->no_wa);
                if ($phone) {
                    $validHosts[] = [
                        'id' => $host->id,
                        'name' => $host->full_name,
                        'phone' => $phone,
                        'original_no_wa' => $host->no_wa
                    ];
                } else {
                    $invalidHosts[] = [
                        'name' => $host->full_name,
                        'no_wa' => $host->no_wa,
                        'reason' => 'Format nomor tidak valid'
                    ];
                }
            }
            
            Log::info('✅ Valid hosts: ' . count($validHosts));
            Log::info('❌ Invalid hosts: ' . count($invalidHosts));
            
            if (empty($validHosts)) {
                Log::warning('🚫 TIDAK ADA HOST DENGAN NOMOR WHATSAPP VALID!');
                return;
            }
            
            // 3. Log semua host valid (opsional, untuk debugging)
            Log::info('📱 === HOST VALID UNTUK DIKIRIMI ===');
            foreach ($validHosts as $index => $host) {
                Log::info("Target #" . ($index + 1) . ": {$host['name']} ({$host['phone']})");
            }
            
            // 4. Generate message
            $message = $this->generateWhatsAppMessage($kunjungan);
            Log::info('✉️ Message length: ' . strlen($message));
            
            $successCount = 0;
            $failedCount = 0;
            
            // 5. KIRIM KE SETIAP HOST VALID - HANYA SATU METODE!
            Log::info('🚀 === MULAI PENGIRIMAN KE SEMUA HOST ===');
            
            foreach ($validHosts as $host) {
                Log::info("📤 Mengirim ke: {$host['name']} ({$host['phone']})");
                
                try {
                    // GUNAKAN SATU METODE SAJA - WhatsAppService
                    $result = $this->wa->sendMessage($host['phone'], $message);
                    
                    // Cek format respons yang berbeda-beda
                    $isSuccess = false;
                    
                    // Format 1: ['success' => true]
                    if (isset($result['success']) && $result['success'] === true) {
                        $isSuccess = true;
                    }
                    // Format 2: ['status' => true] (Fonnte langsung)
                    elseif (isset($result['status']) && $result['status'] === true) {
                        $isSuccess = true;
                    }
                    // Format 3: ['ok' => true] (format alternatif)
                    elseif (isset($result['ok']) && $result['ok'] === true) {
                        $isSuccess = true;
                    }
                    
                    if ($isSuccess) {
                        $successCount++;
                        Log::info("✅ Berhasil dikirim ke {$host['name']}");
                    } else {
                        $failedCount++;
                        $error = $result['error'] ?? $result['message'] ?? json_encode($result);
                        Log::error("❌ Gagal mengirim ke {$host['name']}: {$error}");
                    }
                    
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error("❌ Exception untuk {$host['name']}: " . $e->getMessage());
                }
                
                // Delay antara pengiriman ke host berbeda
                usleep(500000); // 0.5 detik delay
            }
            
            // 6. SUMMARY
            Log::info('📈 === SUMMARY PENGIRIMAN ===');
            Log::info("✅ Berhasil dikirim: {$successCount}/" . count($validHosts));
            Log::info("❌ Gagal dikirim: {$failedCount}/" . count($validHosts));
            
            // 7. Cek potensi duplikasi di log
            $this->checkForDuplicateSending($validHosts, $kunjungan);
            
            Log::info('🔚 === [TAMU CONTROLLER] END WhatsApp Notification ===');
            
        } catch (\Exception $e) {
            Log::error('🔥 MAIN EXCEPTION in sendWhatsAppNotificationToHosts: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Cek duplikasi pengiriman di logs
     */
    private function checkForDuplicateSending(array $validHosts, Kunjungan $kunjungan)
    {
        try {
            // Ambil log terakhir untuk cek duplikasi
            $logPath = storage_path('logs/laravel.log');
            
            if (file_exists($logPath)) {
                $logContent = file_get_contents($logPath);
                
                // Hitung berapa kali kunjungan ID muncul dalam konteks pengiriman
                $kunjunganId = $kunjungan->id;
                $hostNames = array_column($validHosts, 'name');
                
                foreach ($hostNames as $hostName) {
                    // Cari pattern "Mengirim ke: [hostName]" untuk kunjungan ini
                    $pattern = "/Mengirim ke:\s*" . preg_quote($hostName, '/') . ".*kunjungan.*" . $kunjunganId . "/i";
                    $count = preg_match_all($pattern, $logContent);
                    
                    if ($count > 1) {
                        Log::warning("⚠️ DETECTED POSSIBLE DUPLICATE for {$hostName}: {$count} times in logs");
                    }
                }
            }
        } catch (\Exception $e) {
            // Silent fail untuk method ini
        }
    }

    /**
     * Format nomor telepon untuk WhatsApp API
     */
    private function formatPhoneForWhatsApp(?string $phone): ?string
    {
        if (empty($phone) || trim($phone) === '') {
            Log::debug("formatPhone: Empty phone -> NULL");
            return null;
        }
        
        $original = $phone;
        
        // Cek string spesifik yang invalid
        $lower = strtolower(trim($phone));
        if ($lower === 'null' || $lower === '0' || $lower === 'kosong' || $lower === '-' || $lower === 'undefined') {
            Log::debug("formatPhone: Invalid string '{$original}' -> NULL");
            return null;
        }
        
        // Bersihkan nomor - hanya angka
        $number = preg_replace('/[^0-9]/', '', $phone);
        
        if (empty($number)) {
            Log::debug("formatPhone: No digits found in '{$original}' -> NULL");
            return null;
        }
        
        if (strlen($number) < 10) {
            Log::debug("formatPhone: Too short ({$number}) -> NULL");
            return null;
        }
        
        // Format ke 62...
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        
        // Pastikan dimulai dengan 62
        if (!str_starts_with($number, '62')) {
            $number = '62' . $number;
        }
        
        // Validasi akhir
        if (!preg_match('/^62[0-9]{9,12}$/', $number)) {
            Log::debug("formatPhone: Final validation failed for '{$number}' -> NULL");
            return null;
        }
        
        Log::debug("formatPhone: '{$original}' -> '{$number}' -> VALID");
        return $number;
    }

    /**
     * Generate pesan WhatsApp untuk notifikasi kunjungan baru
     */
    private function generateWhatsAppMessage(Kunjungan $kunjungan): string
    {
        $tamu = $kunjungan->tamu;
        $kategori = $kunjungan->kategoriPihak;
        
        $tanggal = Carbon::parse($kunjungan->tanggal_kunjungan)->translatedFormat('d M Y');
        
        $message = "📋 *NOTIFIKASI KUNJUNGAN BARU*\n\n";
        $message .= "Halo, ada pengajuan kunjungan baru di sistem E-Tamu DPRD Kota Gorontalo.\n\n";
        
        $message .= "👤 *Data Pemohon:*\n";
        $message .= "Nama: " . ($tamu->nama ?? '-') . "\n";
        $message .= "Instansi: " . ($tamu->instansi_nama ?? '-') . "\n";
        $message .= "No. HP: 0" . ($tamu->no_hp ?? '-') . "\n";
        $message .= "Email: " . ($tamu->email ?? '-') . "\n\n";
        
        $message .= "📅 *Detail Kunjungan:*\n";
        $message .= "Tanggal: " . $tanggal . "\n";
        $message .= "Waktu: " . $kunjungan->waktu_kunjungan . " WITA\n";
        $message .= "Jumlah Peserta: " . $kunjungan->jumlah_peserta . " orang\n";
        
        $bertemuDengan = $kategori->subnama ?? ($kategori->sub_kategori ?? '-');
        $message .= "Bertemu dengan: *" . $bertemuDengan . "*\n";
        $message .= "Kategori: " . ($kategori->kategori ?? '-') . "\n\n";
        
        // Potong keperluan jika terlalu panjang
        $keperluan = $kunjungan->keperluan;
        if (strlen($keperluan) > 300) {
            $keperluan = substr($keperluan, 0, 300) . '...';
        }
        
        $message .= "*Keperluan:*\n" . $keperluan . "\n\n";
        
        $message .= "*Status:* *MENUNGGU*\n\n";
        
        $message .= "Silakan login ke sistem *Host* untuk melihat detail lengkap dan melakukan konfirmasi.\n";
        $message .= "Terima kasih.";
        
        return $message;
    }

    /**
     * Helper untuk konversi kode ke label
     */
    private function labelFromCode(string $code): string
    {
        return match ($code) {
            'pimpinan_ketua'            => 'Ketua DPRD',
            'pimpinan_wk1'              => 'Wakil Ketua 1',
            'pimpinan_wk2'              => 'Wakil Ketua 2',
            'akd_bk'                    => 'Badan Kehormatan',
            'akd_banggar'               => 'Badan Anggaran',
            'akd_bapemperda'            => 'Badan Pembentukan Peraturan Daerah',
            'akd_bamus'                 => 'Badan Musyawarah',
            'akd_komisi1'               => 'Komisi 1',
            'akd_komisi2'               => 'Komisi 2',
            'akd_komisi3'               => 'Komisi 3',
            'sekretaris'                => 'Sekretaris',
            'bag_umum_humas'            => 'Bagian Umum dan Humas',
            'bag_keuangan'              => 'Bagian Keuangan',
            'persidangan_perundangan'   => 'Persidangan & Perundang-undangan',
            'dokumen'                   => 'Dokumen',
            default                     => ucwords(str_replace('_',' ', $code)),
        };
    }

    /**
     * METHOD BARU: Test WhatsApp secara manual
     */
    public function testWhatsApp(Request $request)
    {
        try {
            Log::info('=== MANUAL WHATSAPP TEST ===');
            
            // Test ke nomor tertentu
            $testPhone = $request->input('phone', '6281234567890');
            $testMessage = "Test WhatsApp dari TamuController pada " . now();
            
            Log::info('Test phone: ' . $testPhone);
            Log::info('Test message: ' . $testMessage);
            
            // Gunakan WhatsAppService saja
            $result = $this->wa->sendMessage($testPhone, $testMessage);
            
            Log::info('WhatsAppService result:', $result);
            
            return response()->json([
                'success' => true,
                'whatsapp_service' => $result,
                'message' => 'Test completed. Check laravel.log for details.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Test failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * METHOD DEBUG: Untuk cek semua host dan nomor mereka
     */
    public function debugHosts()
    {
        try {
            Log::info('🔍 === DEBUG HOSTS INFORMATION ===');
            
            // Ambil SEMUA user dengan role host
            $allHosts = User::where('role', User::ROLE_HOST)->get();
            
            Log::info('Total users with role="host": ' . $allHosts->count());
            
            $activeHosts = User::where('role', User::ROLE_HOST)
                              ->where('is_active', true)
                              ->get();
            
            Log::info('Active hosts (is_active=1): ' . $activeHosts->count());
            
            $results = [
                'all_hosts' => [],
                'active_hosts' => []
            ];
            
            foreach ($allHosts as $host) {
                $phone = $this->formatPhoneForWhatsApp($host->no_wa);
                $results['all_hosts'][] = [
                    'id' => $host->id,
                    'name' => $host->full_name,
                    'no_wa' => $host->no_wa,
                    'formatted' => $phone,
                    'is_active' => $host->is_active,
                    'is_valid' => !empty($phone)
                ];
            }
            
            foreach ($activeHosts as $host) {
                $phone = $this->formatPhoneForWhatsApp($host->no_wa);
                $results['active_hosts'][] = [
                    'id' => $host->id,
                    'name' => $host->full_name,
                    'no_wa' => $host->no_wa,
                    'formatted' => $phone,
                    'is_valid' => !empty($phone)
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $results,
                'summary' => [
                    'total_hosts' => $allHosts->count(),
                    'active_hosts' => $activeHosts->count(),
                    'active_valid_whatsapp' => count(array_filter($results['active_hosts'], function($h) {
                        return $h['is_valid'];
                    }))
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Debug error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * METHOD DEBUG: Untuk cek duplikasi pengiriman
     */
    public function checkDuplicates(Request $request)
    {
        try {
            $kunjunganId = $request->input('kunjungan_id');
            
            if (!$kunjunganId) {
                return response()->json([
                    'success' => false,
                    'message' => 'kunjungan_id diperlukan'
                ]);
            }
            
            $kunjungan = Kunjungan::find($kunjunganId);
            
            if (!$kunjungan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kunjungan tidak ditemukan'
                ]);
            }
            
            $logPath = storage_path('logs/laravel.log');
            $logContent = file_exists($logPath) ? file_get_contents($logPath) : '';
            
            // Cari semua entri untuk kunjungan ini
            $pattern = "/Mengirim ke:.*{$kunjunganId}/i";
            preg_match_all($pattern, $logContent, $matches);
            
            $duplicateEntries = [];
            foreach ($matches[0] as $match) {
                $duplicateEntries[] = $match;
            }
            
            $uniqueEntries = array_unique($duplicateEntries);
            
            return response()->json([
                'success' => true,
                'kunjungan_id' => $kunjunganId,
                'total_entries' => count($duplicateEntries),
                'unique_entries' => count($uniqueEntries),
                'is_duplicate' => count($duplicateEntries) > count($uniqueEntries),
                'entries' => $duplicateEntries,
                'message' => count($duplicateEntries) . ' entries found, ' . count($uniqueEntries) . ' unique'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}