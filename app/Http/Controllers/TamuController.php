<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\KategoriPihak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TamuController extends Controller
{
    public function landing()
    {
        return view('welcome'); // lihat full blade di bagian E
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
                ->route('welcome')
                ->with('success', 'Pengajuan kunjungan berhasil dikirim. Status: menunggu.');
        });
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
}
