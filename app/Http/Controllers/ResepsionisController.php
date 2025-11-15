<?php

namespace App\Http\Controllers;

use App\Exports\DataTamuExports;
use App\Models\KategoriPihak;
use App\Models\Kunjungan;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataTamuExport;
use Illuminate\Support\Facades\Schema;


class ResepsionisController extends Controller
{
    private bool $storesUtc = true; // <— UBAH kalau perlu

    private function todayBaseQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $tz       = config('app.timezone', 'Asia/Makassar');   // WITA
        $nowLocal = Carbon::now($tz);

        // rentang hari ini dalam WITA
        $startLocal = $nowLocal->copy()->startOfDay(); // 00:00 WITA
        $endLocal   = $nowLocal->copy()->endOfDay();   // 23:59:59 WITA

        if ($this->storesUtc) {
            // Jika DB simpan UTC → konversi rentang ke UTC
            $start = $startLocal->copy()->timezone('UTC');
            $end   = $endLocal->copy()->timezone('UTC');
        } else {
            // Jika DB simpan waktu lokal → pakai rentang lokal apa adanya
            $start = $startLocal;
            $end   = $endLocal;
        }

        return Kunjungan::query()->whereBetween('tanggal_kunjungan', [$start, $end]);
    }

    public function index(Request $request)
    {
        $tz    = config('app.timezone', 'Asia/Makassar');
        $today = Carbon::now($tz)->toDateString();

        $base = Kunjungan::where('tanggal_kunjungan', $today);
        // $base = Kunjungan::query();

        $menunggu = Kunjungan::where('status_sekarang', 'menunggu')->count();
        $data = [
            'tanggal'                  => $today,
            'tamu_hari_ini'            => (clone $base)->distinct('tamu_id')->count('tamu_id'),
            'total_kunjungan_hari_ini' => (clone $base)->count(),
            'menunggu'                 => (clone $base)->where('status_sekarang', 'menunggu')->count(),
            'disetujui'                => (clone $base)->where('status_sekarang', 'diterima')->count(),
            'ditolak'                  => (clone $base)->where('status_sekarang', 'ditolak')->count(),
        ];

        // **List kartu kunjungan untuk ditampilkan**
        $kunjungan_hari_ini = (clone $base)
            ->with(['tamu']) 
            ->leftJoin('kategori_pihak','kategori_pihak.id','=','kunjungan.kategori_pihak_id')  // butuh nama tamu
            ->orderBy('waktu_kunjungan')     // urut jam
            ->get();
        // dd($kunjungan_hari_ini);
        return view('resepsionis.index', array_merge($data, compact('kunjungan_hari_ini')));
    }

    public function stats(Request $request)
    {
        $tz    = config('app.timezone', 'Asia/Makassar');
        $today = Carbon::now($tz)->toDateString();

        $base = $this->todayBaseQuery();

        return response()->json([
            'tanggal'                  => $today,
            'tamu_hari_ini'            => (clone $base)->distinct('tamu_id')->count('tamu_id'),
            'total_kunjungan_hari_ini' => (clone $base)->count(),
            'menunggu'                 => (clone $base)->where('status_sekarang', 'menunggu')->count(),
            'disetujui'                => (clone $base)->where('status_sekarang', 'diterima')->count(),
            'ditolak'                  => (clone $base)->where('status_sekarang', 'ditolak')->count(),
        ]);
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

            // KATEGORI & SUB KATEGORI (mengacu tabel kategori_pihak)
            'kategori_pihak_top'=> ['required','in:pimpinan,akd,sekretariat'],
            'kategori_pihak'    => ['required','string','max:64'], // ex: pimpinan_ketua, akd_bk, ...

            // KUNJUNGAN
            'jumlah'            => ['required','integer','min:1','max:50'],
            'keperluan'         => ['required','string'],
            'tanggal_kunjungan' => ['required','date','after_or_equal:today'],
            'waktu_kunjungan'   => ['required','date_format:H:i'],

            // DOKUMEN jadi bagian dari kunjungan (kolom string)
            'dokumen'           => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
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

            // 3) Upload file ke storage publik (opsional)
            $dokumenPath = null;
            if ($request->hasFile('dokumen')) {
                // simpan ke storage/app/public/kunjungan/*
                $stored = $request->file('dokumen')
                    ->store('kunjungan', 'public'); // returns "kunjungan/xxxxx.ext"
                $dokumenPath = 'storage/'.$stored;  // dapat diakses setelah php artisan storage:link
            }

            // 4) Buat Kunjungan
            Kunjungan::create([
                'tamu_id'           => $tamu->id,
                'kategori_pihak_id' => $kp->id,
                'jumlah_peserta'    => $validated['jumlah'],
                'keperluan'         => $validated['keperluan'],
                'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
                'waktu_kunjungan'   => $validated['waktu_kunjungan'],
                'dokumen'           => $dokumenPath,
                'status_sekarang'   => 'menunggu',
                // 'host_id'        => null,
            ]);

            return redirect()
                ->route('resepsionis.index')
                ->with('success', 'Pengajuan kunjungan berhasil dikirim. Status: menunggu.');
        });
    }
    public function datatamu(Request $request)
    {
        // Query params
        $q      = trim((string) $request->get('q', ''));
        $bulan  = trim((string) $request->get('bulan', ''));
        $status = trim((string) $request->get('status', ''));

        if ($status === 'disetujui') {
            $status = 'diterima';
        }

        // Base query dengan eager loading yang lebih spesifik
        $query = Kunjungan::query()
            ->with([
                'tamu',
                'kategoriPihak' => function($query) {
                    $query->select('id', 'sub_kategori');
                }
            ])
            ->leftJoin('kategori_pihak', 'kategori_pihak.id', '=', 'kunjungan.kategori_pihak_id')
            ->orderByDesc('tanggal_kunjungan')
            ->orderByDesc('waktu_kunjungan')
            ->select('kunjungan.*', 'kategori_pihak.sub_kategori'); // Tambahkan kolom yang diperlukan

        // Filter status
        if ($status !== '') {
            $query->where('status_sekarang', $status);
        }

        // Filter bulan
        if ($bulan !== '' && ctype_digit($bulan) && (int)$bulan >= 1 && (int)$bulan <= 12) {
            $query->whereMonth('tanggal_kunjungan', (int) $bulan);
        }

        // Pencarian
        if ($q !== '') {
        $query->where(function ($w) use ($q) {
            $w->where('keperluan', 'LIKE', "%{$q}%")
            ->orWhere('dokumen', 'LIKE', "%{$q}%")
            ->orWhere('waktu_kunjungan', 'LIKE', "%{$q}%")
            ->orWhereDate('tanggal_kunjungan', $q)
            ->orWhereHas('tamu', function ($t) use ($q) {
                $t->where('nama', 'LIKE', "%{$q}%")
                    ->orWhere('email', 'LIKE', "%{$q}%")
                    ->orWhere('no_hp', 'LIKE', "%{$q}%")
                    ->orWhere('instansi_nama', 'LIKE', "%{$q}%")
                    ->orWhere('instansi_kategori', 'LIKE', "%{$q}%");
            })
            ->orWhereHas('kategoriPihak', function ($kp) use ($q) {
                $kp->where('sub_kategori', 'LIKE', "%{$q}%");
            });
        });
        }

        $tamu = $query->paginate(15)->withQueryString();

        return view('resepsionis.datatamu', compact('tamu'));
    }
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
    public function exportDatatamu(Request $request): StreamedResponse
    {
    // Ambil parameter filter yang sama seperti di datatamu()
    $q      = trim((string) $request->get('q', ''));
    $bulan  = trim((string) $request->get('bulan', ''));
    $status = trim((string) $request->get('status', ''));

    if ($status === 'disetujui') {
        $status = 'diterima';
    }

    // Query dasar yang sama dengan datatamu()
    $query = Kunjungan::query()
        ->with(['tamu', 'kategoriPihak'])
        ->orderByDesc('tanggal_kunjungan')
        ->leftJoin('kategori_pihak','kategori_pihak.id','=','kunjungan.kategori_pihak_id')
        ->orderByDesc('waktu_kunjungan')
        ->select('kunjungan.*');    

    if ($status !== '') {
        $query->where('status_sekarang', $status);
    }
    if ($bulan !== '' && ctype_digit($bulan) && (int)$bulan >= 1 && (int)$bulan <= 12) {
        $query->whereMonth('tanggal_kunjungan', (int) $bulan);
    }
    if ($q !== '') {
        $query->where(function ($w) use ($q) {
            $w->where('keperluan', 'LIKE', "%{$q}%")
              ->orWhere('instansi_nama', 'LIKE', "%{$q}%")
              ->orWhere('dokumen', 'LIKE', "%{$q}%")
              ->orWhere('waktu_kunjungan', 'LIKE', "%{$q}%")
              ->orWhereDate('tanggal_kunjungan', $q)
              ->orWhereHas('tamu', function ($t) use ($q) {
                  $t->where('nama', 'LIKE', "%{$q}%")
                    ->orWhere('email', 'LIKE', "%{$q}%")
                    ->orWhere('no_hp', 'LIKE', "%{$q}%")
                    ->orWhere('instansi_nama', 'LIKE', "%{$q}%")
                    ->orWhere('instansi_kategori', 'LIKE', "%{$q}%");
              })
              ->orWhereHas('kategoriPihak', function ($kp) use ($q) {
                  $kp->where('subnama', 'LIKE', "%{$q}%")
                     ->orWhere('sub_kategori', 'LIKE', "%{$q}%")
                     ->orWhere('kategori', 'LIKE', "%{$q}%");
              });
        });
    }

    // Ambil data (kalau dataset sangat besar, pertimbangkan chunk())
    $rows = $query->get();

    // Siapkan response CSV streamed
    $tz = config('app.timezone', 'Asia/Makassar');
    $filename = 'data-tamu-' . now($tz)->format('Ymd_His') . '.csv';

    $headers = [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        'Cache-Control'       => 'no-store, no-cache, must-revalidate',
        'Pragma'              => 'no-cache',
    ];

    return response()->stream(function () use ($rows, $tz) {
        $out = fopen('php://output', 'w');

        // Tulis BOM agar Excel/Windows baca UTF-8 dengan benar
        echo "\xEF\xBB\xBF";

        // Header kolom (samakan dengan tabel di view)
        fputcsv($out, [
            'Status',
            'Nama',
            'Email',
            'Nomor HP',
            'Kategori Instansi',
            'Nama Instansi',
            'Jumlah Peserta',
            'Tanggal Kunjungan',
            'Waktu Kunjungan',
            'Bertemu Dengan',
            'Dokumen',
        ]);

        foreach ($rows as $row) {
            $status = $row->status_sekarang ?? $row->status ?? 'menunggu';
            $labelStatus = match ($status) {
                'menunggu' => 'Menunggu',
                'diterima', 'disetujui' => 'Disetujui',
                'ditolak'  => 'Ditolak',
                'selesai'  => 'Selesai',
                default    => ucfirst($status),
            };

            $namaTamu     = $row->nama ?? ($row->tamu->nama ?? '—');
            $emailTamu    = $row->email ?? ($row->tamu->email ?? '—');
            $hpTamu       = $row->no_hp ?? ($row->tamu->no_hp ?? '—');
            $katInstansi  = $row->instansi_kategori ?? ($row->tamu->instansi_kategori ?? null);
            $namaInstansi = $row->instansi_nama ?? ($row->tamu->instansi_nama ?? '—');
            $jumlah       = $row->jumlah_peserta ?? $row->jumlah ?? null;

            // Gabungkan tanggal & waktu ke zona WITA
            $tanggal = $row->tanggal_kunjungan instanceof \DateTimeInterface
                ? $row->tanggal_kunjungan->format('Y-m-d')
                : (string) $row->tanggal_kunjungan;

            $waktu = $row->waktu_kunjungan instanceof \DateTimeInterface
                ? $row->waktu_kunjungan->format('H:i:s')
                : (strlen((string) $row->waktu_kunjungan) === 5
                    ? ($row->waktu_kunjungan . ':00')
                    : ($row->waktu_kunjungan ?? '00:00:00'));

            // Jika DB simpan UTC dan kamu pakai flag $this->storesUtc, bisa disesuaikan. Di sini kita asumsikan nilai sudah sesuai.
            $dtLocal = \Illuminate\Support\Carbon::parse("$tanggal $waktu", $tz)->timezone($tz);
            $tglOut  = $dtLocal->translatedFormat('d M Y');
            $wktOut  = $dtLocal->format('H:i') . ' WITA';

            $bertemu = optional($row->kategoriPihak)->subnama
                ?? ($row->sub_kategori ?? $row->bertemu_dengan ?? '—');

            // Dokumen URL (jika di DB "storage/...": langsung url(); jika path storage: gunakan Storage::url())
            $dok    = $row->dokumen ?? null;
            if ($dok) {
                if (\Illuminate\Support\Str::startsWith($dok, ['http://','https://'])) {
                    $dokUrl = $dok;
                } elseif (\Illuminate\Support\Str::startsWith($dok, 'storage/')) {
                    $dokUrl = url($dok);
                } else {
                    $dokUrl = Storage::url($dok);
                }
            } else {
                $dokUrl = '';
            }

            fputcsv($out, [
                $labelStatus,
                $namaTamu,
                $emailTamu,
                $hpTamu,
                $katInstansi ? strtoupper($katInstansi) : '—',
                $namaInstansi,
                $jumlah,
                $tglOut,
                $wktOut,
                $bertemu,
                $dokUrl,
            ]);
        }

        fclose($out);
    }, 200, $headers);
    }

    public function exportDatatamuXlsx(Request $request)
    {
    
    $tz = config('app.timezone', 'Asia/Makassar');
    $filename = 'data-tamu-' . now($tz)->format('Ymd_His') . '.xlsx';

    // Kirim semua query params ke Export class supaya filternya sama persis
    return Excel::download(
        new DataTamuExport($request->all(), $tz, $this->storesUtc),
        $filename
    );
    }

    public function markSelesai(Request $request, Kunjungan $kunjungan)
    {
        // Hanya boleh menandai selesai jika status saat ini 'diterima' (atau 'disetujui')
        if (!in_array($kunjungan->status_sekarang, ['diterima', 'disetujui'])) {
            return back()->with('error', 'Hanya kunjungan yang sudah diterima yang bisa ditandai selesai.');
        }

        $kunjungan->status_sekarang = 'selesai';
        // Jika perlu, isikan waktu selesai:
        // $kunjungan->waktu_selesai = now('Asia/Makassar');
        $kunjungan->save();

        return back()->with('success', 'Kunjungan ditandai selesai.');
    }

}
