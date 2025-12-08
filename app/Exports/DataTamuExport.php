<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class DataTamuExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    use Exportable;

    protected array $filters;
    protected string $tz;
    protected bool $storesUtc;

    public function __construct(array $filters = [], string $tz = 'Asia/Makassar', bool $storesUtc = true)
    {
        $this->filters   = $filters;
        $this->tz        = $tz;
        $this->storesUtc = $storesUtc;
    }

    public function query()
    {
        $q      = trim((string) Arr::get($this->filters, 'q', ''));
        $bulan  = trim((string) Arr::get($this->filters, 'bulan', ''));
        $status = trim((string) Arr::get($this->filters, 'status', ''));

        if ($status === 'disetujui') $status = 'diterima';

        return Kunjungan::query()
            ->with(['tamu', 'kategoriPihak'])
            ->leftJoin('kategori_pihak','kategori_pihak.id','=','kunjungan.kategori_pihak_id')
            ->orderByDesc('tanggal_kunjungan')
            ->orderByDesc('waktu_kunjungan')
            ->when($status !== '', fn($q2) => $q2->where('status_sekarang', $status))
            ->when($bulan !== '' && ctype_digit($bulan) && (int)$bulan >= 1 && (int)$bulan <= 12,
                fn($q2) => $q2->whereMonth('tanggal_kunjungan', (int)$bulan))
            ->when($q !== '', function ($q2) use ($q) {
                $q2->where(function ($w) use ($q) {
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
            });
    }

    public function headings(): array
    {
        // Urutan kolom (perhatikan indeks untuk penentuan kolom G,H,I)
        return [
            'Nama',              // A
            'Email',             // B
            'Nomor HP',          // C
            'Kategori Instansi', // D
            'Nama Instansi',     // E
            'Jumlah Peserta',    // F
            'Tanggal Kunjungan', // G
            'Waktu Kunjungan',   // H
            'Bertemu Dengan',    // I
        ];
    }

    public function map($row): array
    {
        $namaTamu     = $row->nama ?? ($row->tamu->nama ?? '—');
        $emailTamu    = $row->email ?? ($row->tamu->email ?? '—');
        $hpTamu       = $row->no_hp ?? ($row->tamu->no_hp ?? '—');
        $katInstansi  = $row->instansi_kategori ?? ($row->tamu->instansi_kategori ?? null);
        $namaInstansi = $row->instansi_nama ?? ($row->tamu->instansi_nama ?? '—');
        $jumlah       = $row->jumlah_peserta ?? $row->jumlah ?? null;

        $tanggal = $row->tanggal_kunjungan instanceof \DateTimeInterface
            ? $row->tanggal_kunjungan->format('Y-m-d')
            : (string) $row->tanggal_kunjungan;

        $waktu = $row->waktu_kunjungan instanceof \DateTimeInterface
            ? $row->waktu_kunjungan->format('H:i:s')
            : (strlen((string) $row->waktu_kunjungan) === 5
                ? ($row->waktu_kunjungan . ':00')
                : ($row->waktu_kunjungan ?? '00:00:00'));

        $srcTz   = $this->storesUtc ? 'Asia/Makassar' : $this->tz;
        $dtLocal = Carbon::parse("$tanggal $waktu", $srcTz)->timezone($this->tz);
        $tglOut  = $dtLocal->translatedFormat('d M Y');
        $wktOut  = $dtLocal->format('H:i') . ' WITA';

        // Ambil dari relasi/leftJoin kategori_pihak
        $bertemu = optional($row->kategoriPihak)->subnama
            ?? ($row->sub_kategori ?? $row->bertemu_dengan ?? '—');

        return [
            $namaTamu,                               // A
            $emailTamu,                              // B
            $hpTamu,                                 // C
            $katInstansi ? strtoupper($katInstansi) : '—', // D
            $namaInstansi,                           // E
            $jumlah,                                 // F  <-- akan di-center (baris data)
            $tglOut,                                 // G  <-- akan di-center (baris data)
            $wktOut,                                 // H  <-- akan di-center (baris data)
            $bertemu,                                // I
        ];
    }

    /** Header bold + background kuning */
    public function styles(Worksheet $sheet)
    {
        // Header range
        $lastColIndex  = count($this->headings());
        $lastColLetter = Coordinate::stringFromColumnIndex($lastColIndex);
        $headerRange   = "A1:{$lastColLetter}1";

        // Header bold + fill kuning
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FFFFF59D');

        return [];
    }

    /** Center untuk kolom F, G, H di baris data (mulai row 2) */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet    = $event->sheet->getDelegate();
                $lastRow  = $sheet->getHighestRow();

                // Kolom:
                // F = Jumlah Peserta, G = Tanggal Kunjungan, H = Waktu Kunjungan
                $ranges = ["F2:F{$lastRow}", "G2:G{$lastRow}", "H2:H{$lastRow}"];

                foreach ($ranges as $range) {
                    $sheet->getStyle($range)->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                          ->setVertical(Alignment::VERTICAL_CENTER);
                }
            },
        ];
    }
}
