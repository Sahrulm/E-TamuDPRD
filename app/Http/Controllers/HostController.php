<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kunjungan;
use Illuminate\Support\Carbon;
use App\Services\WhatsAppService;

class HostController extends Controller
{
    /**
     * Service untuk kirim WhatsApp via Fonnte
     */
    protected WhatsAppService $wa;

    public function __construct(WhatsAppService $wa)
    {
        $this->wa = $wa;
    }

    public function index(Request $request)
    {
        $waiting = Kunjungan::query()
            ->with([
                'tamu:id,nama,email,no_hp,instansi_nama,instansi_kategori',
                'kategoriPihak:id,kategori,sub_kategori',
            ])
            ->where('status_sekarang', 'menunggu')
            ->orderByDesc('tanggal_kunjungan')
            ->get();

        $mapped = $waiting->map(function (Kunjungan $k) {
            return (object) [
                'id'                => $k->id,
                'status_sekarang'   => $k->status_sekarang ?? 'menunggu',
                'nama'              => optional($k->tamu)->nama ?? '—',
                'email'             => optional($k->tamu)->email ?? '—',
                'no_hp'             => optional($k->tamu)->no_hp ?? '—',
                'instansi_kategori' => optional($k->tamu)->instansi_kategori,
                'instansi_nama'     => optional($k->tamu)->instansi_nama ?? '—',
                'jumlah_peserta'    => $k->jumlah_peserta ?? $k->jumlah ?? null,
                'tanggal_kunjungan' => $k->tanggal_kunjungan,
                'waktu_kunjungan'   => $k->waktu_kunjungan,
                'bertemu_dengan'    => optional($k->kategoriPihak)->sub_kategori
                                        ?? optional($k->kategoriPihak)->kategori
                                        ?? ($k->bertemu_dengan ?? '—'),
                'dokumen'           => $k->dokumen ?? null,
                'keperluan'         => $k->keperluan ?? null,
            ];
        });

        $tz = 'Asia/Makassar';
        $today = Carbon::now($tz)->toDateString();

        $totalHariIni = Kunjungan::whereDate('created_at', $today)->count();

        $startOfWeek = Carbon::now($tz)->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::now($tz)->endOfWeek(Carbon::SUNDAY);

        $diterimaMingguIni = Kunjungan::where('status_sekarang', 'diterima')
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->count();

        $ditolakMingguIni = Kunjungan::where('status_sekarang', 'ditolak')
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->count();

        $badge = [
            'menunggu' => 'bg-amber-50 ring-amber-200 text-amber-700',
            'diterima' => 'bg-emerald-50 ring-emerald-200 text-emerald-700',
            'ditolak'  => 'bg-rose-50 ring-rose-200 text-rose-700',
        ];
        $label = [
            'menunggu' => 'Menunggu',
            'diterima' => 'Diterima',
            'ditolak'  => 'Ditolak',
        ];

        return view('host.index', [
            'pengajuan'         => $mapped,
            'tamuMenunggu'      => $mapped,
            'totalHariIni'      => $totalHariIni,
            'diterimaMingguIni' => $diterimaMingguIni,
            'ditolakMingguIni'  => $ditolakMingguIni,
            'badge'             => $badge,
            'label'             => $label,
        ]);
    }

    // METHOD UNTUK DATA PENGAJUAN (TAB "DATA")
    public function datapengajuan(Request $request)
    {
        $search = $request->get('search', '');
        
        // Query dasar
        $query = Kunjungan::query()
            ->with([
                'tamu:id,nama,email,no_hp,instansi_nama,instansi_kategori',
                'kategoriPihak:id,kategori,sub_kategori',
            ])
            ->where('status_sekarang', 'menunggu')
            ->orderByDesc('tanggal_kunjungan');
        
        // Filter pencarian SAMA PERSIS dengan controller lain
        if ($search !== '') {
            $query->where(function ($w) use ($search) {
                $w->where('keperluan', 'LIKE', "%{$search}%")
                    ->orWhere('dokumen', 'LIKE', "%{$search}%")
                    ->orWhere('waktu_kunjungan', 'LIKE', "%{$search}%")
                    ->orWhereDate('tanggal_kunjungan', $search)
                    ->orWhereHas('tamu', function ($t) use ($search) {
                        $t->where('nama', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('no_hp', 'LIKE', "%{$search}%")
                            ->orWhere('instansi_nama', 'LIKE', "%{$search}%")
                            ->orWhere('instansi_kategori', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('kategoriPihak', function ($kp) use ($search) {
                        $kp->where('sub_kategori', 'LIKE', "%{$search}%");
                    });
            });
        }
        
        $pengajuan = $query->get();
        
        $mapped = $pengajuan->map(function (Kunjungan $k) {
            return (object) [
                'id'                => $k->id,
                'status_sekarang'   => $k->status_sekarang ?? 'menunggu',
                'nama'              => optional($k->tamu)->nama ?? '—',
                'email'             => optional($k->tamu)->email ?? '—',
                'no_hp'             => optional($k->tamu)->no_hp ?? '—',
                'instansi_kategori' => optional($k->tamu)->instansi_kategori,
                'instansi_nama'     => optional($k->tamu)->instansi_nama ?? '—',
                'jumlah_peserta'    => $k->jumlah_peserta ?? $k->jumlah ?? null,
                'tanggal_kunjungan' => $k->tanggal_kunjungan,
                'waktu_kunjungan'   => $k->waktu_kunjungan,
                'bertemu_dengan'    => optional($k->kategoriPihak)->sub_kategori
                                        ?? optional($k->kategoriPihak)->kategori
                                        ?? ($k->bertemu_dengan ?? '—'),
                'dokumen'           => $k->dokumen ?? null,
                'keperluan'         => $k->keperluan ?? null,
            ];
        });

        $badge = [
            'menunggu' => 'bg-amber-50 ring-amber-200 text-amber-700',
            'diterima' => 'bg-emerald-50 ring-emerald-200 text-emerald-700',
            'ditolak'  => 'bg-rose-50 ring-rose-200 text-rose-700',
        ];
        $label = [
            'menunggu' => 'Menunggu',
            'diterima' => 'Diterima',
            'ditolak'  => 'Ditolak',
        ];

        return view('host.datapengajuan', [
            'pengajuan'         => $mapped,
            'badge'             => $badge,
            'label'             => $label,
            'currentSearch'     => $search,
        ]);
    }

    // METHOD RIWAYAT KUNJUNGAN
    public function riwayat(Request $request)
    {
        $status = $request->get('status', 'semua');
        $search = $request->get('search', '');
        
        // Query dasar
        $query = Kunjungan::query()
            ->with([
                'tamu:id,nama,email,no_hp,instansi_nama,instansi_kategori',
                'kategoriPihak:id,kategori,sub_kategori',
                'host:id,full_name',
            ])
            ->whereIn('status_sekarang', ['diterima', 'ditolak', 'selesai'])
            ->orderByDesc('updated_at');
        
        // Filter status
        if ($status !== 'semua') {
            $query->where('status_sekarang', $status);
        }
        
        // Filter pencarian SAMA PERSIS dengan controller lain
        if ($search !== '') {
            $query->where(function ($w) use ($search) {
                $w->where('keperluan', 'LIKE', "%{$search}%")
                    ->orWhere('dokumen', 'LIKE', "%{$search}%")
                    ->orWhere('waktu_kunjungan', 'LIKE', "%{$search}%")
                    ->orWhereDate('tanggal_kunjungan', $search)
                    ->orWhereHas('tamu', function ($t) use ($search) {
                        $t->where('nama', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('no_hp', 'LIKE', "%{$search}%")
                            ->orWhere('instansi_nama', 'LIKE', "%{$search}%")
                            ->orWhere('instansi_kategori', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('kategoriPihak', function ($kp) use ($search) {
                        $kp->where('sub_kategori', 'LIKE', "%{$search}%");
                    });
            });
        }
        
        // Pagination
        $riwayat = $query->paginate(10)->withQueryString();
        
        // Map data untuk view
        $riwayat->getCollection()->transform(function (Kunjungan $k) {
            $tamu = $k->tamu;
            $kategori = $k->kategoriPihak;
            $host = $k->host;
            
            // Format waktu kunjungan
            $waktu = $k->waktu_kunjungan;
            if ($waktu) {
                $waktuFormatted = substr((string) $waktu, 0, 5);
            } else {
                $waktuFormatted = '—';
            }
            
            // Format waktu diproses
            $diprosesPada = $k->updated_at ? $k->updated_at->translatedFormat('d/m/Y H:i') : '—';
            
            // Format nama host
            $hostNama = $host ? $host->full_name : ($k->approved_by_name ?? '—');
            
            return [
                'id' => $k->id,
                'nama' => $tamu ? $tamu->nama : '—',
                'email' => $tamu ? $tamu->email : '—',
                'no_hp' => $tamu ? $tamu->no_hp : '—',
                'instansi_nama' => $tamu ? $tamu->instansi_nama : '—',
                'instansi_kategori' => $tamu ? $tamu->instansi_kategori : null,
                'jumlah_peserta' => $k->jumlah_peserta ?? $k->jumlah ?? null,
                'tanggal_kunjungan' => $k->tanggal_kunjungan,
                'waktu_kunjungan' => $waktuFormatted,
                'tanggal_kunjungan_formatted' => $k->tanggal_kunjungan 
                    ? Carbon::parse($k->tanggal_kunjungan)->translatedFormat('d/m/Y')
                    : '—',
                'bertemu_dengan' => $kategori 
                    ? ($kategori->sub_kategori ?? $kategori->kategori)
                    : ($k->bertemu_dengan ?? '—'),
                'keperluan' => $k->keperluan ?? '—',
                'status_sekarang' => $k->status_sekarang,
                'alasan' => $k->alasan_penolakan ?? ($k->catatan ?? ''),
                'diproses_pada' => $diprosesPada,
                'host_nama' => $hostNama,
                'created_at' => $k->created_at,
                'updated_at' => $k->updated_at,
            ];
        });
        
        // Statistik untuk filter
        $statistik = [
            'semua' => Kunjungan::whereIn('status_sekarang', ['diterima', 'ditolak', 'selesai'])->count(),
            'diterima' => Kunjungan::where('status_sekarang', 'diterima')->count(),
            'ditolak' => Kunjungan::where('status_sekarang', 'ditolak')->count(),
            'selesai' => Kunjungan::where('status_sekarang', 'selesai')->count(),
        ];
        
        $badge = [
            'diterima' => 'bg-emerald-50 ring-emerald-200 text-emerald-700',
            'ditolak'  => 'bg-rose-50 ring-rose-200 text-rose-700',
            'selesai'  => 'bg-blue-50 ring-blue-200 text-blue-700',
        ];
        
        $label = [
            'diterima' => 'Disetujui',
            'ditolak'  => 'Ditolak',
            'selesai'  => 'Selesai',
        ];
        
        return view('host.riwayat', [
            'riwayat' => $riwayat,
            'statistik' => $statistik,
            'badge' => $badge,
            'label' => $label,
            'currentStatus' => $status,
            'currentSearch' => $search,
        ]);
    }

    public function accept(Request $r, Kunjungan $kunjungan)
    {
        $ok = $kunjungan->approve(Auth::user());

        if (! $ok) {
            return back()->with('error', 'Status bukan "menunggu".');
        }

        // Ambil data tamu & kategori pihak
        $kunjungan->loadMissing(['tamu', 'kategoriPihak']);

        $tz      = 'Asia/Makassar';
        $tamu    = optional($kunjungan->tamu);
        $kat     = optional($kunjungan->kategoriPihak);

        $nama    = $tamu->nama ?? 'Bapak/Ibu';
        $nomor   = $tamu->no_hp; // disimpan apa adanya, akan dinormalisasi di service
        $instansi= $tamu->instansi_nama ?? '—';

        $tanggal = $kunjungan->tanggal_kunjungan
            ? Carbon::parse($kunjungan->tanggal_kunjungan, $tz)->translatedFormat('d M Y')
            : '—';

        $waktuRaw = $kunjungan->waktu_kunjungan;
        $waktu    = $waktuRaw ? substr((string) $waktuRaw, 0, 5) : '—';

        $bertemu = $kat->sub_kategori
                    ?? $kat->kategori
                    ?? ($kunjungan->bertemu_dengan ?? '—');

        $pesan = "Halo {$nama},\n\n"
            . "Pengajuan kunjungan Anda ke *DPRD Kota Gorontalo* telah *DITERIMA*.\n\n"
            . "📅 Tanggal : {$tanggal}\n"
            . "⏰ Waktu   : {$waktu} WITA\n"
            . "🏢 Instansi: {$instansi}\n"
            . "👥 Bertemu : {$bertemu}\n\n"
            . "Silakan hadir tepat waktu. Terima kasih.";

        if ($nomor) {
            $this->wa->sendMessage($nomor, $pesan);
        }

        return back()->with('success', 'Pengajuan diterima dan notifikasi WA dikirim (jika nomor tersedia).');
    }

    public function reject(Request $r, Kunjungan $kunjungan)
    {
        $ok = $kunjungan->reject(Auth::user());

        if (! $ok) {
            return back()->with('error', 'Status bukan "menunggu".');
        }

        // Ambil data tamu
        $kunjungan->loadMissing('tamu');

        $tamu  = optional($kunjungan->tamu);
        $nama  = $tamu->nama ?? 'Bapak/Ibu';
        $nomor = $tamu->no_hp;

        // Kalau nanti mau pakai form alasan, tinggal ambil dari input:
        $alasan = $r->input('alasan', 'Jadwal belum tersedia pada waktu yang diajukan.');

        $pesan = "Halo {$nama},\n\n"
            . "Mohon maaf, pengajuan kunjungan Anda ke *DPRD Kota Gorontalo* telah *DITOLAK*.\n\n"
            . "Alasan: {$alasan}\n\n"
            . "Silakan mengajukan kembali dengan jadwal lain. Terima kasih.";

        if ($nomor) {
            $this->wa->sendMessage($nomor, $pesan);
        }

        return back()->with('success', 'Pengajuan ditolak dan notifikasi WA dikirim (jika nomor tersedia).');
    }
}