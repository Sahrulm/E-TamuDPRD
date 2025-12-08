<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\KategoriPihak;
use App\Models\StatusKunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /* ================= View ================= */
    public function index()
    {
        return view('admin.index');
    }

    public function users()
    {
        return view('admin.kelolauser');
    }

    public function tamu()
    {
        return view('admin.kelolatamu');
    }

    /* ================= Users ================= */
    public function usersIndex(Request $request)
    {
        $users = User::query()
            ->when($request->filled('search'), function($q) use ($request){
                $s = $request->string('search');
                $q->where(fn($w)=>$w->where('email','like',"%{$s}%")
                                    ->orWhere('full_name','like',"%{$s}%")
                                    ->orWhere('no_wa','like',"%{$s}%"));
            })
            ->orderByDesc('created_at')
            ->get(['id','full_name','email','no_wa','role','is_active']);

        return response()->json($users);
    }

    public function usersStore(Request $request)
    {
        $data = $request->validate([
            'email'     => ['required','email','max:200','unique:users,email'],
            'password'  => ['required','string','min:8'],
            'role'      => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_HOST, User::ROLE_RESEPSIONIS])],
            'full_name' => ['nullable','string','max:200'],
            'no_wa'     => ['nullable','string','max:20'],
            'is_active' => ['sometimes','boolean'],
        ]);

        $user = User::create([
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'full_name' => $data['full_name'] ?? null,
            'no_wa'     => $data['no_wa'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return response()->json($user, 201);
    }

    public function usersUpdate(Request $request, User $user)
    {
        $data = $request->validate([
            'email'     => ['required','email','max:200', Rule::unique('users','email')->ignore($user->id)],
            'password'  => ['nullable','string','min:8'],
            'role'      => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_HOST, User::ROLE_RESEPSIONIS])],
            'full_name' => ['nullable','string','max:200'],
            'no_wa'     => ['nullable','string','max:20'],
            'is_active' => ['sometimes','boolean'],
        ]);

        $payload = [
            'email'     => $data['email'],
            'role'      => $data['role'],
            'full_name' => $data['full_name'] ?? $user->full_name,
            'no_wa'     => array_key_exists('no_wa', $data) ? $data['no_wa'] : $user->no_wa,
            'is_active' => $data['is_active'] ?? $user->is_active,
        ];
        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);
        return response()->json($user);
    }

    public function usersDestroy(User $user)
    {
        $user->delete();
        return response()->json(['deleted'=>true]);
    }

    /* ================= Kunjungan ================= */
    public function kunjunganIndex(Request $request)
    {
        $rows = Kunjungan::query()
            ->with(['tamu','kategoriPihak','host'])
            ->when($request->filled('status'), fn($q)=>$q->where('status_sekarang',$request->string('status')))
            ->orderByDesc('tanggal_kunjungan')
            ->orderBy('waktu_kunjungan')
            ->get();

        // Mapping data untuk UI
        $mapped = $rows->map(function(Kunjungan $k){
            // Ambil data dari kategori_pihak jika ada
            $bertemu_kategori = '';
            $bertemu_sub = '';
            $bertemu_dengan = '';
            
            if ($k->kategoriPihak) {
                $bertemu_kategori = $k->kategoriPihak->kategori ?? '';
                $bertemu_sub = $k->kategoriPihak->sub_kategori ?? '';
                $bertemu_dengan = $bertemu_kategori . ($bertemu_sub ? ' - ' . $bertemu_sub : '');
            }

            return [
                'id'                => $k->id,
                'nama'              => $k->tamu->nama ?? '—',
                'email'             => $k->tamu->email ?? '—',
                'no_hp'             => $k->tamu->no_hp ?? '—',
                'jumlah_peserta'    => $k->jumlah_peserta,
                'instansi_kategori' => $k->tamu->instansi_kategori ?? null,
                'instansi_nama'     => $k->tamu->instansi_nama ?? null,
                'tanggal_kunjungan' => optional($k->tanggal_kunjungan)->format('Y-m-d'),
                'waktu_kunjungan'   => $k->waktu_kunjungan,
                'bertemu_dengan'    => $bertemu_dengan,
                'bertemu_kategori'  => $bertemu_kategori,
                'bertemu_sub'       => $bertemu_sub,
                'kategori_pihak_id' => $k->kategori_pihak_id,
                'dokumen'           => $k->dokumen ? asset('storage/'.$k->dokumen) : null,
                'status_sekarang'   => $k->status_sekarang,
                'host_id'           => $k->host_id,
                'tamu_id'           => $k->tamu_id,
            ];
        });

        return response()->json($mapped);
    }

    public function kunjunganStore(Request $request)
    {
        $data = $request->validate([
            // Data tamu
            'nama'               => ['required','string','max:200'],
            'email'              => ['required','email','max:200'],
            'no_hp'              => ['required','string','max:50'],
            'instansi_kategori'  => ['nullable','string','max:100'],
            'instansi_nama'      => ['nullable','string','max:200'],

            // Data kunjungan
            'jumlah_peserta'     => ['nullable','integer','min:1','max:1000'],
            'tanggal_kunjungan'  => ['required','date'],
            'waktu_kunjungan'    => ['required','date_format:H:i'],
            'status_sekarang'    => ['nullable', Rule::in(['menunggu','diterima','ditolak','selesai'])],
            'host_id'            => ['nullable','integer','exists:users,id'],

            // Dokumen & keperluan
            'dokumen'            => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
            'keperluan'          => ['sometimes','nullable','string','max:500'],
            
            // Data pihak yang dituju
            'bertemu_kategori'   => ['sometimes','nullable','string','max:100'],
            'bertemu_sub'        => ['sometimes','nullable','string','max:200'],
        ]);

        return DB::transaction(function () use ($request, $data) {
            // 1. Cari atau buat kategori pihak
            $kategoriPihakId = null;
            if (!empty($data['bertemu_kategori'])) {
                $kategoriPihak = KategoriPihak::firstOrCreate([
                    'kategori' => $data['bertemu_kategori'],
                    'sub_kategori' => $data['bertemu_sub'] ?? '',
                ], [
                    'is_active' => true
                ]);
                $kategoriPihakId = $kategoriPihak->id;
            }

            // 2. Buat tamu
            $tamu = Tamu::create([
                'nama'               => $data['nama'],
                'email'              => $data['email'],
                'no_hp'              => $data['no_hp'],
                'instansi_kategori'  => $data['instansi_kategori'] ?? null,
                'instansi_nama'      => $data['instansi_nama'] ?? null,
            ]);

            // 3. Handle dokumen
            $path = null;
            if ($request->hasFile('dokumen')) {
                $path = $request->file('dokumen')->store('kunjungan', 'public');
            }

            // 4. Buat kunjungan
            $kunjungan = Kunjungan::create([
                'tamu_id'           => $tamu->id,
                'kategori_pihak_id' => $kategoriPihakId,
                'jumlah_peserta'    => $data['jumlah_peserta'] ?? 1,
                'keperluan'         => $data['keperluan'] ?? null,
                'tanggal_kunjungan' => $data['tanggal_kunjungan'],
                'waktu_kunjungan'   => $data['waktu_kunjungan'],
                'status_sekarang'   => $data['status_sekarang'] ?? 'menunggu',
                'dokumen'           => $path,
                'host_id'           => $data['host_id'] ?? null,
            ]);

            // 5. Riwayat status awal
            StatusKunjungan::create([
                'kunjungan_id'       => $kunjungan->id,
                'old_status'         => null,
                'new_status'         => $kunjungan->status_sekarang,
                'changed_by_user_id' => null,
                'note'               => null,
                'changed_at'         => now(),
            ]);

            return response()->json(['created'=>true, 'kunjungan_id' => $kunjungan->id]);
        });
    }

    public function kunjunganUpdate(Request $request, Kunjungan $kunjungan)
    {
        $data = $request->validate([
            // Data tamu
            'nama'               => ['sometimes','required','string','max:200'],
            'email'              => ['sometimes','required','email','max:200'],
            'no_hp'              => ['sometimes','required','string','max:50'],
            'instansi_kategori'  => ['sometimes','nullable','string','max:100'],
            'instansi_nama'      => ['sometimes','nullable','string','max:200'],

            // Data kunjungan
            'jumlah_peserta'     => ['sometimes','nullable','integer','min:1','max:1000'],
            'tanggal_kunjungan'  => ['sometimes','required','date'],
            'waktu_kunjungan'    => ['sometimes','required','date_format:H:i'],
            'status_sekarang'    => ['sometimes','nullable', Rule::in(['menunggu','diterima','ditolak','selesai'])],
            'host_id'            => ['sometimes','nullable','integer','exists:users,id'],

            // Dokumen & keperluan
            'dokumen'            => ['sometimes','nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
            'keperluan'          => ['sometimes','nullable','string','max:500'],
            
            // Data pihak yang dituju
            'bertemu_kategori'   => ['sometimes','nullable','string','max:100'],
            'bertemu_sub'        => ['sometimes','nullable','string','max:200'],
            'tamu_id'            => ['sometimes','nullable','integer','exists:tamu,id'],
        ]);

        return DB::transaction(function () use ($request, $data, $kunjungan) {
            // 1. Update tamu jika ada perubahan
            if ($kunjungan->tamu && (
                $request->exists('nama') || 
                $request->exists('email') || 
                $request->exists('no_hp') || 
                $request->exists('instansi_kategori') || 
                $request->exists('instansi_nama')
            )) {
                $kunjungan->tamu->update([
                    'nama'               => $data['nama'] ?? $kunjungan->tamu->nama,
                    'email'              => $data['email'] ?? $kunjungan->tamu->email,
                    'no_hp'              => $data['no_hp'] ?? $kunjungan->tamu->no_hp,
                    'instansi_kategori'  => array_key_exists('instansi_kategori', $data) ? $data['instansi_kategori'] : $kunjungan->tamu->instansi_kategori,
                    'instansi_nama'      => array_key_exists('instansi_nama', $data) ? $data['instansi_nama'] : $kunjungan->tamu->instansi_nama,
                ]);
            }

            // 2. Update kategori pihak jika ada perubahan
            if ($request->exists('bertemu_kategori')) {
                $kategoriPihakId = null;
                if (!empty($data['bertemu_kategori'])) {
                    $kategoriPihak = KategoriPihak::firstOrCreate([
                        'kategori' => $data['bertemu_kategori'],
                        'sub_kategori' => $data['bertemu_sub'] ?? '',
                    ], [
                        'is_active' => true
                    ]);
                    $kategoriPihakId = $kategoriPihak->id;
                }
                $kunjungan->kategori_pihak_id = $kategoriPihakId;
            }

            // 3. Handle dokumen baru
            if ($request->hasFile('dokumen')) {
                if ($kunjungan->dokumen) {
                    Storage::disk('public')->delete($kunjungan->dokumen);
                }
                $kunjungan->dokumen = $request->file('dokumen')->store('kunjungan', 'public');
            }

            // 4. Update field kunjungan lainnya
            $fill = [];
            foreach (['jumlah_peserta','keperluan','tanggal_kunjungan','waktu_kunjungan','host_id'] as $f) {
                if ($request->exists($f)) $fill[$f] = $data[$f] ?? null;
            }
            $kunjungan->fill($fill);

            // 5. Update status dengan riwayat
            if ($request->exists('status_sekarang') && $data['status_sekarang'] !== $kunjungan->status_sekarang) {
                $old = $kunjungan->status_sekarang;
                $kunjungan->status_sekarang = $data['status_sekarang'];
                
                StatusKunjungan::create([
                    'kunjungan_id'       => $kunjungan->id,
                    'old_status'         => $old,
                    'new_status'         => $kunjungan->status_sekarang,
                    'changed_by_user_id' => null,
                    'note'               => null,
                    'changed_at'         => now(),
                ]);
            }

            $kunjungan->save();

            return response()->json(['updated'=>true, 'kunjungan_id' => $kunjungan->id]);
        });
    }

    public function kunjunganDestroy(Kunjungan $kunjungan)
    {
        DB::transaction(function() use ($kunjungan){
            if ($kunjungan->dokumen) {
                Storage::disk('public')->delete($kunjungan->dokumen);
            }
            $kunjungan->riwayatStatus()->delete();
            $kunjungan->delete();
        });

        return response()->json(['deleted'=>true]);
    }

    public function dashboardData(Request $request)
    {
        $range = $request->get('range', 7);
        
        try {
            \Log::info('Dashboard data requested', ['range' => $range]);

            // Metrics dasar
            $metrics = [
                'totalTamu' => Tamu::count(),
                'totalSelesai' => Kunjungan::where('status_sekarang', 'selesai')->count(),
                'totalUser' => User::count(),
                'todayVisits' => Kunjungan::whereDate('tanggal_kunjungan', today())->count(),
            ];

            // Status counts
            $statusCounts = [
                'menunggu' => Kunjungan::where('status_sekarang', 'menunggu')->count(),
                'diterima' => Kunjungan::where('status_sekarang', 'diterima')->count(),
                'ditolak' => Kunjungan::where('status_sekarang', 'ditolak')->count(),
                'selesai' => Kunjungan::where('status_sekarang', 'selesai')->count(),
            ];

            // Data untuk chart
            $chartData = $this->getChartData($range);

            // Data tamu untuk frontend
            $tamuData = $this->getKunjunganForDashboard();

            \Log::info('Dashboard data prepared', [
                'metrics' => $metrics,
                'status_counts' => $statusCounts,
                'chart_data_days' => count($chartData),
                'tamu_records' => count($tamuData)
            ]);

            return response()->json([
                'metrics' => $metrics,
                'statusCounts' => $statusCounts,
                'chartData' => $chartData,
                'tamu' => $tamuData
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in dashboardData: ' . $e->getMessage());
            
            return response()->json([
                'metrics' => ['totalTamu' => 0, 'totalSelesai' => 0, 'totalUser' => 0, 'todayVisits' => 0],
                'statusCounts' => ['menunggu' => 0, 'diterima' => 0, 'ditolak' => 0, 'selesai' => 0],
                'chartData' => [],
                'tamu' => []
            ], 500);
        }
    }

    // METHOD BARU: Ambil data khusus untuk chart
    private function getChartData($range = 7)
    {
        $chartData = [];
        $today = now();
        
        for ($i = $range - 1; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            
            // Query untuk setiap hari
            $dayKunjungan = Kunjungan::whereDate('tanggal_kunjungan', $dateStr)->get();
            
            $chartData[] = [
                'date' => $dateStr,
                'label' => $date->format('d M'),
                'menunggu' => $dayKunjungan->where('status_sekarang', 'menunggu')->count(),
                'diterima' => $dayKunjungan->where('status_sekarang', 'diterima')->count(),
                'ditolak' => $dayKunjungan->where('status_sekarang', 'ditolak')->count(),
                'selesai' => $dayKunjungan->where('status_sekarang', 'selesai')->count(),
                'total' => $dayKunjungan->count()
            ];
        }

        \Log::info('Chart data generated', [
            'range' => $range,
            'data_points' => count($chartData),
            'sample' => $chartData[0] ?? 'No data'
        ]);

        return $chartData;
    }

    // Helper method untuk data kunjungan dashboard
    private function getKunjunganForDashboard()
    {
        return Kunjungan::with(['tamu', 'kategoriPihak'])
            ->orderByDesc('tanggal_kunjungan')
            ->orderBy('waktu_kunjungan')
            ->get()
            ->map(function(Kunjungan $k) {
                $bertemu_kategori = '';
                $bertemu_sub = '';
                $bertemu_dengan = '';
                
                if ($k->kategoriPihak) {
                    $bertemu_kategori = $k->kategoriPihak->kategori ?? '';
                    $bertemu_sub = $k->kategoriPihak->sub_kategori ?? '';
                    $bertemu_dengan = $bertemu_kategori . ($bertemu_sub ? ' - ' . $bertemu_sub : '');
                }

                return [
                    'id' => $k->id,
                    'nama' => $k->tamu->nama ?? '—',
                    'email' => $k->tamu->email ?? '—',
                    'no_hp' => $k->tamu->no_hp ?? '—',
                    'jumlah_peserta' => $k->jumlah_peserta,
                    'instansi_kategori' => $k->tamu->instansi_kategori ?? null,
                    'instansi_nama' => $k->tamu->instansi_nama ?? null,
                    'tanggal_kunjungan' => optional($k->tanggal_kunjungan)->format('Y-m-d'),
                    'waktu_kunjungan' => $k->waktu_kunjungan,
                    'bertemu_dengan' => $bertemu_dengan,
                    'bertemu_kategori' => $bertemu_kategori,
                    'bertemu_sub' => $bertemu_sub,
                    'kategori_pihak_id' => $k->kategori_pihak_id,
                    'dokumen' => $k->dokumen ? asset('storage/'.$k->dokumen) : null,
                    'status_sekarang' => $k->status_sekarang,
                    'host_id' => $k->host_id,
                    'tamu_id' => $k->tamu_id,
                ];
            });
    }
}