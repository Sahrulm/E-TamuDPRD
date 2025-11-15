@extends('layouts.default')

@section('title', 'E-Tamu DPRD – Data Tamu')

@section('content')
  <!-- HERO -->
  <section class="pt-24 sm:pt-28 pb-6 bg-gradient-to-r from-brand-50 via-brand-100 to-amber-100 relative overflow-hidden">
    <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full border border-brand-200/60 bg-gradient-to-br from-amber-100/70 to-transparent"></div>
    <div class="pointer-events-none absolute -right-20 -bottom-24 h-80 w-80 rounded-full border-4 border-brand-100/70 bg-gradient-to-tr from-brand-50/70 to-transparent"></div>
    <div class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-8 h-20 w-20 rounded-full pattern-dots opacity-30"></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-end justify-between gap-4">
      <div class="space-y-2 w-full">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-brand-700 shadow-soft">
          <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-brand-500 text-[10px] text-white">◎</span>
          Mode Resepsionis · Data Tamu
        </span>
        <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 animate-fadeUp">
          Data Tamu
        </h1>
        <p class="text-slate-700 mt-1 text-sm sm:text-base animate-fadeUp" style="animation-delay:.06s">
          Daftar lengkap kunjungan tamu. Gunakan pencarian &amp; filter untuk mempersempit data.
        </p>
      </div>
    </div>
  </section>

  <main class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8 pb-20">
    <div class="py-4 sm:py-6 rounded-2xl border border-amber-100 bg-white/95 p-4 sm:p-6 shadow-soft anim-on">
      <!-- FILTER BAR -->
      <form action="{{ route('resepsionis.datatamu') }}" method="GET" class="grid gap-3 grid-cols-1 sm:grid-cols-5">
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-slate-700">Pencarian</label>
            <input name="q" value="{{ request('q') }}" type="text"
                  class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm sm:text-base"
                  placeholder="Cari nama, email, instansi, no HP, keperluan...">
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700">Filter Bulan</label>
          <select name="bulan"
                  class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm sm:text-base">
            <option value="">Semua</option>
            @foreach(range(1,12) as $m)
              @php
                $val = sprintf('%02d', $m);
                $selected = request('bulan') == $val ? 'selected' : '';
                $nama = \Carbon\Carbon::createFromDate(null, $m, 1)->locale('id')->translatedFormat('F');
              @endphp
              <option value="{{ $val }}" {{ $selected }}>{{ $nama }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700">Status</label>
          <select name="status"
                  class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm sm:text-base">
            <option value="">Semua</option>
            <option value="menunggu"  {{ request('status')=='menunggu'  ? 'selected' : '' }}>Menunggu</option>
            <option value="diterima"  {{ request('status')=='diterima'  ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak"   {{ request('status')=='ditolak'   ? 'selected' : '' }}>Ditolak</option>
            <option value="selesai"   {{ request('status')=='selesai'   ? 'selected' : '' }}>Selesai</option>
          </select>
        </div>

        <div class="flex items-end gap-2 mobile-stack sm:flex-row">
          <button type="submit" class="ripple w-full rounded-xl bg-brand-500 px-4 py-2 text-white font-semibold shadow hover:shadow-lift transition-transform text-sm sm:text-base">
            Terapkan
          </button>

          {{-- Tombol Ekspor: bawa seluruh query string filter yang aktif --}}
          <a href="{{ route('resepsionis.datatamu.export.xlsx', request()->query()) }}"
             class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-center font-semibold text-white shadow hover:shadow-lift transition-transform text-sm sm:text-base">
            Ekspor
          </a>
        </div>
      </form>

      <!-- TABEL DATA TAMU - Desktop -->
      <div class="mt-6 overflow-x-auto hidden md:block">
        @php
          $badge = [
            'menunggu' => 'bg-amber-50 ring-amber-200 text-amber-700',
            'diterima' => 'bg-emerald-50 ring-emerald-200 text-emerald-700',
            'disetujui'=> 'bg-emerald-50 ring-emerald-200 text-emerald-700',
            'ditolak'  => 'bg-rose-50 ring-rose-200 text-rose-700',
            'selesai'  => 'bg-slate-50 ring-slate-200 text-slate-700',
          ];
          $label = [
            'menunggu' => 'Menunggu',
            'diterima' => 'Disetujui',
            'disetujui'=> 'Disetujui',
            'ditolak'  => 'Ditolak',
            'selesai'  => 'Selesai',
          ];
        @endphp

        <table class="min-w-[1200px] w-full text-sm">
          <thead>
            <tr class="text-left text-slate-700 bg-amber-100/80">
              <th class="py-3 pr-4 pl-3 text-xs font-semibold tracking-wide">Status</th>
              <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Nama</th>
              <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Email</th>
              <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Nomor HP</th>
              <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Kategori Instansi</th>
              <th class="py-3 pr-4 text-center text-xs font-semibold tracking-wide">Nama Instansi</th>
              <th class="py-3 pr-4 text-center text-xs font-semibold tracking-wide">Jumlah Peserta</th>
              <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Tanggal Kunjungan</th>
              <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Waktu Kunjungan</th>
              <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Bertemu Dengan</th>
              <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Dokumen</th>
              <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Aksi</th>
            </tr>
          </thead>
          <tbody class="align-top bg-white/95">
            @forelse($tamu as $row)
              @php
                $status = $row->status_sekarang ?? $row->status ?? 'menunggu';

                $tz = 'Asia/Makassar';
                $srcTz = $tz;

                $tglStr = $row->tanggal_kunjungan instanceof \DateTimeInterface
                          ? $row->tanggal_kunjungan->format('Y-m-d')
                          : (string) ($row->tanggal_kunjungan ?? now($tz)->toDateString());

                $rawWkt = $row->waktu_kunjungan ?? '00:00:00';
                $wktStr = $row->waktu_kunjungan instanceof \DateTimeInterface
                          ? $row->waktu_kunjungan->format('H:i:s')
                          : (strlen((string) $rawWkt) === 5 ? ($rawWkt . ':00') : (string) $rawWkt);

                $dt = \Illuminate\Support\Carbon::parse(trim($tglStr.' '.$wktStr), $srcTz)->timezone($tz);

                $namaTamu     = $row->nama ?? ($row->tamu->nama ?? '—');
                $emailTamu    = $row->email ?? ($row->tamu->email ?? '—');
                $hpTamu       = $row->no_hp ?? ($row->tamu->no_hp ?? '—');
                $katInstansi  = $row->instansi_kategori ?? ($row->tamu->instansi_kategori ?? null);
                $namaInstansi = $row->instansi_nama ?? ($row->tamu->instansi_nama ?? '—');
                $jumlah       = $row->jumlah_peserta ?? $row->jumlah ?? '—';

                $bertemu      = optional($row->kategoriPihak)->subnama
                                ?? ($row->sub_kategori ?? $row->bertemu_dengan ?? '—');

                $dok          = $row->dokumen ?? null;
              @endphp

              <tr class="border-t border-amber-100 hover:bg-amber-50/40">
                <td class="py-3 pr-4 pl-3">
                  <span class="inline-flex rounded-full px-2 py-0.5 text-xs ring-1 {{ $badge[$status] ?? 'bg-slate-50 ring-slate-200 text-slate-700' }}">
                    {{ $label[$status] ?? ucfirst($status) }}
                  </span>
                </td>

                <td class="py-3 pr-4 font-medium text-slate-900">{{ $namaTamu }}</td>
                <td class="py-3 pr-4 text-slate-700">{{ $emailTamu }}</td>
                <td class="py-3 pr-4 whitespace-nowrap text-slate-700">{{ $hpTamu }}</td>
                <td class="py-3 pr-4 text-slate-700">{{ $katInstansi ? strtoupper($katInstansi) : '—' }}</td>
                <td class="py-3 pr-4 text-center text-slate-800">{{ $namaInstansi }}</td>
                <td class="py-3 pr-4 text-center text-slate-800">{{ is_numeric($jumlah) ? number_format($jumlah) : $jumlah }}</td>
                <td class="py-3 pr-4 text-slate-700">{{ $dt->translatedFormat('d M Y') }}</td>
                <td class="py-3 pr-4 text-slate-800">{{ $dt->format('H:i') }} WITA</td>
                <td class="py-3 pr-4 text-slate-700">{{ $bertemu }}</td>
                <td class="py-3 pr-4">
                  @if($dok)
                    @php
                      $dokUrl = \Illuminate\Support\Str::startsWith($dok, ['http://','https://'])
                                ? $dok
                                : (\Illuminate\Support\Str::startsWith($dok, 'storage/')
                                    ? url($dok)
                                    : \Illuminate\Support\Facades\Storage::url($dok));
                    @endphp
                    <a href="{{ $dokUrl }}" target="_blank"
                       class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-3 py-1.5 text-xs hover:bg-amber-100 text-slate-800 border border-amber-200">
                      Lihat
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M12.293 2.293a1 1 0 011.414 0L18 6.586V8a2 2 0 01-2 2h-1V8a1 1 0 00-1-1h-2V6a2 2 0 012-2h1V3.414l-3.293-3.293z"/><path d="M3 6a2 2 0 012-2h6v2H5v10h10v-6h2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V6z"/></svg>
                    </a>
                  @else
                    <span class="text-slate-400 text-xs">—</span>
                  @endif
                </td>

                <td class="py-3 pr-4 align-middle">
                  @if(in_array($status, ['diterima','disetujui']) && isset($row->id))
                    <form method="POST" action="{{ route('resepsionis.kunjungan.selesai', $row->id) }}" class="inline">
                      @csrf
                      <button type="submit"
                              class="ripple inline-flex items-center justify-center
                                    rounded-md bg-emerald-600 hover:bg-emerald-700
                                    px-2.5 py-1 h-7 text-xs font-semibold leading-tight
                                    text-white focus:outline-none focus:ring-1 focus:ring-emerald-500/60
                                    whitespace-nowrap">
                        Tandai Selesai
                      </button>
                    </form>
                  @else
                    <span class="text-slate-400 text-xs">—</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr class="border-t border-amber-100">
                <td colspan="12" class="py-8 text-center text-slate-500">Tidak ada data ditemukan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- CARD DATA TAMU - Mobile -->
      <div class="mt-6 space-y-4 md:hidden">
        @forelse($tamu as $row)
          @php
            $status = $row->status_sekarang ?? $row->status ?? 'menunggu';

            $tz = 'Asia/Makassar';
            $srcTz = $tz;

            $tglStr = $row->tanggal_kunjungan instanceof \DateTimeInterface
                      ? $row->tanggal_kunjungan->format('Y-m-d')
                      : (string) ($row->tanggal_kunjungan ?? now($tz)->toDateString());

            $rawWkt = $row->waktu_kunjungan ?? '00:00:00';
            $wktStr = $row->waktu_kunjungan instanceof \DateTimeInterface
                      ? $row->waktu_kunjungan->format('H:i:s')
                      : (strlen((string) $rawWkt) === 5 ? ($rawWkt . ':00') : (string) $rawWkt);

            $dt = \Illuminate\Support\Carbon::parse(trim($tglStr.' '.$wktStr), $srcTz)->timezone($tz);

            $namaTamu     = $row->nama ?? ($row->tamu->nama ?? '—');
            $emailTamu    = $row->email ?? ($row->tamu->email ?? '—');
            $hpTamu       = $row->no_hp ?? ($row->tamu->no_hp ?? '—');
            $katInstansi  = $row->instansi_kategori ?? ($row->tamu->instansi_kategori ?? null);
            $namaInstansi = $row->instansi_nama ?? ($row->tamu->instansi_nama ?? '—');
            $jumlah       = $row->jumlah_peserta ?? $row->jumlah ?? '—';

            $bertemu      = optional($row->kategoriPihak)->subnama
                            ?? ($row->sub_kategori ?? $row->bertemu_dengan ?? '—');

            $dok          = $row->dokumen ?? null;

            $badge = [
              'menunggu' => 'bg-amber-50 ring-amber-200 text-amber-700',
              'diterima' => 'bg-emerald-50 ring-emerald-200 text-emerald-700',
              'disetujui'=> 'bg-emerald-50 ring-emerald-200 text-emerald-700',
              'ditolak'  => 'bg-rose-50 ring-rose-200 text-rose-700',
              'selesai'  => 'bg-slate-50 ring-slate-200 text-slate-700',
            ];
            $label = [
              'menunggu' => 'Menunggu',
              'diterima' => 'Disetujui',
              'disetujui'=> 'Disetujui',
              'ditolak'  => 'Ditolak',
              'selesai'  => 'Selesai',
            ];
          @endphp

          <div class="rounded-2xl border border-amber-100 bg-white p-4 shadow-soft">
            <div class="flex justify-between items-start mb-3">
              <div>
                <h3 class="font-semibold text-slate-900 text-sm">{{ $namaTamu }}</h3>
                <p class="text-xs text-slate-600 mt-1">{{ $emailTamu }}</p>
              </div>
              <span class="inline-flex rounded-full px-2 py-0.5 text-xs ring-1 {{ $badge[$status] ?? 'bg-slate-50 ring-slate-200 text-slate-700' }}">
                {{ $label[$status] ?? ucfirst($status) }}
              </span>
            </div>

            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-500">No. HP:</span>
                <span class="text-slate-900">{{ $hpTamu }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-500">Instansi:</span>
                <span class="text-slate-900 text-right">{{ $namaInstansi }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-500">Kategori:</span>
                <span class="text-slate-900">{{ $katInstansi ? strtoupper($katInstansi) : '—' }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-500">Jumlah:</span>
                <span class="text-slate-900">{{ is_numeric($jumlah) ? number_format($jumlah) : $jumlah }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-500">Tanggal:</span>
                <span class="text-slate-900">{{ $dt->translatedFormat('d M Y') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-500">Waktu:</span>
                <span class="text-slate-900">{{ $dt->format('H:i') }} WITA</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-500">Bertemu:</span>
                <span class="text-slate-900 text-right">{{ $bertemu }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-slate-500">Dokumen:</span>
                <div>
                  @if($dok)
                    @php
                      $dokUrl = \Illuminate\Support\Str::startsWith($dok, ['http://','https://'])
                                ? $dok
                                : (\Illuminate\Support\Str::startsWith($dok, 'storage/')
                                    ? url($dok)
                                    : \Illuminate\Support\Facades\Storage::url($dok));
                    @endphp
                    <a href="{{ $dokUrl }}" target="_blank"
                       class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-2 py-1 text-xs hover:bg-amber-100 text-slate-800 border border-amber-200">
                      Lihat
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M12.293 2.293a1 1 0 011.414 0L18 6.586V8a2 2 0 01-2 2h-1V8a1 1 0 00-1-1h-2V6a2 2 0 012-2h1V3.414l-3.293-3.293z"/><path d="M3 6a2 2 0 012-2h6v2H5v10h10v-6h2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V6z"/></svg>
                    </a>
                  @else
                    <span class="text-slate-400 text-xs">—</span>
                  @endif
                </div>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-amber-100">
              @if(in_array($status, ['diterima','disetujui']) && isset($row->id))
                <form method="POST" action="{{ route('resepsionis.kunjungan.selesai', $row->id) }}" class="w-full">
                  @csrf
                  <button type="submit"
                          class="ripple w-full inline-flex items-center justify-center
                                rounded-md bg-emerald-600 hover:bg-emerald-700
                                px-3 py-2 text-xs font-semibold leading-tight
                                text-white focus:outline-none focus:ring-1 focus:ring-emerald-500/60">
                    Tandai Selesai
                  </button>
                </form>
              @else
                <div class="text-center text-slate-400 text-xs py-2">Tidak ada aksi</div>
              @endif
            </div>
          </div>
        @empty
          <div class="rounded-2xl border border-amber-100 bg-white p-8 text-center text-slate-500">
            Tidak ada data ditemukan.
          </div>
        @endforelse
      </div>

      @if(method_exists($tamu, 'links'))
        <div class="mt-6">{{ $tamu->withQueryString()->links() }}</div>
      @endif
    </div>
  </main>
@endsection