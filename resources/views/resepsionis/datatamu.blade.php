@extends('layouts.resepsionis')

@section('title', 'E-Tamu DPRD – Data Tamu')

@section('content')
  <!-- HERO -->
  <section class="pt-24 sm:pt-28 pb-8 bg-gradient-to-r from-brand-50 via-brand-100 to-amber-100 relative overflow-hidden">
    <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full border-2 border-brand-300/60 bg-gradient-to-br from-amber-100/70 to-transparent"></div>
    <div class="pointer-events-none absolute -right-20 -bottom-24 h-80 w-80 rounded-full border-4 border-brand-200/70 bg-gradient-to-tr from-brand-50/70 to-transparent"></div>
    <div class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-8 h-20 w-20 rounded-full pattern-dots opacity-40"></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-end justify-between gap-4">
      <div class="space-y-3 w-full">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/90 px-4 py-2 text-sm font-bold text-brand-700 shadow-lg border-2 border-brand-200">
          <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-brand-500 text-xs text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
          </span>
          Mode Resepsionis · Data Tamu
        </span>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 animate-fadeUp">
          Data Tamu
        </h1>
        <p class="text-slate-700 mt-2 text-base sm:text-lg animate-fadeUp" style="animation-delay:.06s">
          Daftar lengkap kunjungan tamu. Gunakan pencarian &amp; filter untuk mempersempit data.
        </p>
      </div>
    </div>
  </section>

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
    <div class="py-6 sm:py-8 rounded-2xl border-2 border-brand-200 bg-white p-6 sm:p-8 shadow-lift anim-on">
      <!-- FILTER BAR -->
      <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 mb-4">Filter & Pencarian</h2>
        <form action="{{ route('resepsionis.datatamu') }}" method="GET" class="grid gap-4 grid-cols-1 sm:grid-cols-5">
          <div class="sm:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Pencarian</label>
            <div class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
              </svg>
              <input name="q" value="{{ request('q') }}" type="text"
                    class="w-full rounded-xl border-2 border-slate-300 bg-white pl-10 pr-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-base"
                    placeholder="Cari nama, email, instansi, no HP, keperluan...">
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Filter Bulan</label>
            <div class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19a2 2 0 002 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
              </svg>
              <select name="bulan"
                      class="w-full rounded-xl border-2 border-slate-300 bg-white pl-10 pr-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-base appearance-none">
                <option value="">Semua Bulan</option>
                @foreach(range(1,12) as $m)
                  @php
                    $val = sprintf('%02d', $m);
                    $selected = request('bulan') == $val ? 'selected' : '';
                    $nama = \Carbon\Carbon::createFromDate(null, $m, 1)->locale('id')->translatedFormat('F');
                  @endphp
                  <option value="{{ $val }}" {{ $selected }}>{{ $nama }}</option>
                @endforeach
              </select>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
            <div class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
              <select name="status"
                      class="w-full rounded-xl border-2 border-slate-300 bg-white pl-10 pr-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-base appearance-none">
                <option value="">Semua Status</option>
                <option value="menunggu"  {{ request('status')=='menunggu'  ? 'selected' : '' }}>Menunggu</option>
                <option value="diterima"  {{ request('status')=='diterima'  ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak"   {{ request('status')=='ditolak'   ? 'selected' : '' }}>Ditolak</option>
                <option value="selesai"   {{ request('status')=='selesai'   ? 'selected' : '' }}>Selesai</option>
              </select>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </div>
          </div>

          <div class="flex items-end gap-1 mobile-stack sm:flex-row">
            <button type="submit" class="ripple w-full rounded-xl bg-brand-500 px-6 py-3 text-white font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 text-base">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
              </svg>
              Terapkan
            </button>

            {{-- Tombol Ekspor: bawa seluruh query string filter yang aktif --}}
            <a href="{{ route('resepsionis.datatamu.export.xlsx', request()->query()) }}"
               class="w-full rounded-xl bg-emerald-600 px-6 py-3 text-center font-bold text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-0 text-base">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
              </svg>
              Ekspor
            </a>
          </div>
        </form>
      </div>

      <!-- TABEL DATA TAMU - Desktop -->
      <div class="mt-8 overflow-x-auto hidden md:block">
        @php
          $badge = [
            'menunggu' => 'bg-amber-100 border-amber-300 text-amber-800',
            'diterima' => 'bg-emerald-100 border-emerald-300 text-emerald-800',
            'disetujui'=> 'bg-emerald-100 border-emerald-300 text-emerald-800',
            'ditolak'  => 'bg-rose-100 border-rose-300 text-rose-800',
            'selesai'  => 'bg-slate-100 border-slate-300 text-slate-800',
          ];
          $label = [
            'menunggu' => 'Menunggu',
            'diterima' => 'Disetujui',
            'disetujui'=> 'Disetujui',
            'ditolak'  => 'Ditolak',
            'selesai'  => 'Selesai',
          ];
        @endphp

        <table class="min-w-[1400px] w-full text-sm">
          <thead>
            <tr class="text-left text-slate-700 bg-gradient-to-r from-brand-50 to-amber-50 border-2 border-brand-200">
              <th class="py-4 pr-4 pl-6 text-sm font-bold tracking-wide border-r border-brand-200">Status</th>
              <th class="py-4 pr-4 text-sm font-bold tracking-wide border-r border-brand-200">Nama</th>
              <th class="py-4 pr-4 text-sm font-bold tracking-wide border-r border-brand-200">Email</th>
              <th class="py-4 pr-4 text-sm font-bold tracking-wide border-r border-brand-200">Nomor HP</th>
              <th class="py-4 pr-4 text-sm font-bold tracking-wide border-r border-brand-200">Kategori Instansi</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-brand-200">Nama Instansi</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-brand-200">Jumlah Peserta</th>
              <th class="py-4 pr-4 text-sm font-bold tracking-wide border-r border-brand-200">Tanggal Kunjungan</th>
              <th class="py-4 pr-4 text-sm font-bold tracking-wide border-r border-brand-200">Waktu Kunjungan</th>
              <th class="py-4 pr-4 text-sm font-bold tracking-wide border-r border-brand-200">Bertemu Dengan</th>
              <th class="py-4 pr-4 text-sm font-bold tracking-wide border-r border-brand-200">Dokumen</th>
              <th class="py-4 pr-6 text-sm font-bold tracking-wide">Aksi</th>
            </tr>
          </thead>
          <tbody class="align-top bg-white">
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

              <tr class="border-b-2 border-brand-100 hover:bg-brand-50/60 transition-colors">
                <td class="py-4 pr-4 pl-6 border-r border-brand-100">
                  <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-bold border {{ $badge[$status] ?? 'bg-slate-100 border-slate-300 text-slate-800' }}">
                    {{ $label[$status] ?? ucfirst($status) }}
                  </span>
                </td>

                <td class="py-4 pr-4 border-r border-brand-100 font-bold text-slate-900">{{ $namaTamu }}</td>
                <td class="py-4 pr-4 border-r border-brand-100 text-slate-700">{{ $emailTamu }}</td>
                <td class="py-4 pr-4 border-r border-brand-100 whitespace-nowrap text-slate-700 font-medium">{{ $hpTamu }}</td>
                <td class="py-4 pr-4 border-r border-brand-100 text-slate-700 font-medium">{{ $katInstansi ? strtoupper($katInstansi) : '—' }}</td>
                <td class="py-4 pr-4 border-r border-brand-100 text-center text-slate-800 font-medium">{{ $namaInstansi }}</td>
                <td class="py-4 pr-4 border-r border-brand-100 text-center text-slate-800 font-bold">{{ is_numeric($jumlah) ? number_format($jumlah) : $jumlah }}</td>
                <td class="py-4 pr-4 border-r border-brand-100 text-slate-700 font-medium">{{ $dt->translatedFormat('d M Y') }}</td>
                <td class="py-4 pr-4 border-r border-brand-100 text-slate-800 font-bold">{{ $dt->format('H:i') }} WITA</td>
                <td class="py-4 pr-4 border-r border-brand-100 text-slate-700 font-medium">{{ $bertemu }}</td>
                <td class="py-4 pr-4 border-r border-brand-100">
                  @if($dok)
                    @php
                      $dokUrl = \Illuminate\Support\Str::startsWith($dok, ['http://','https://'])
                                ? $dok
                                : (\Illuminate\Support\Str::startsWith($dok, 'storage/')
                                    ? url($dok)
                                    : \Illuminate\Support\Facades\Storage::url($dok));
                    @endphp
                    <a href="{{ $dokUrl }}" target="_blank"
                       class="inline-flex items-center gap-2 rounded-xl bg-amber-100 px-3 py-2 text-xs font-bold hover:bg-amber-200 text-slate-800 border-2 border-amber-300 transition-colors">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                      </svg>
                      Lihat
                    </a>
                  @else
                    <span class="text-slate-400 text-xs font-medium">—</span>
                  @endif
                </td>

                <td class="py-4 pr-6 align-middle">
                  @if(in_array($status, ['diterima','disetujui']) && isset($row->id))
                    <form method="POST" action="{{ route('resepsionis.kunjungan.selesai', $row->id) }}" class="inline">
                      @csrf
                      <button type="submit"
                              class="ripple inline-flex items-center justify-center gap-2
                                    rounded-xl bg-emerald-600 hover:bg-emerald-700
                                    px-4 py-2 text-xs font-bold
                                    text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/60
                                    whitespace-nowrap transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Selesai
                      </button>
                    </form>
                  @else
                    <span class="text-slate-400 text-xs font-medium">—</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr class="border-b-2 border-brand-100">
                <td colspan="12" class="py-12 text-center">
                  <div class="flex flex-col items-center justify-center text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-400 mb-4" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-slate-600 mb-2">Tidak ada data ditemukan</h3>
                    <p class="text-slate-500">Coba ubah filter pencarian Anda</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- CARD DATA TAMU - Mobile -->
      <div class="mt-8 space-y-4 md:hidden">
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
              'menunggu' => 'bg-amber-100 border-amber-300 text-amber-800',
              'diterima' => 'bg-emerald-100 border-emerald-300 text-emerald-800',
              'disetujui'=> 'bg-emerald-100 border-emerald-300 text-emerald-800',
              'ditolak'  => 'bg-rose-100 border-rose-300 text-rose-800',
              'selesai'  => 'bg-slate-100 border-slate-300 text-slate-800',
            ];
            $label = [
              'menunggu' => 'Menunggu',
              'diterima' => 'Disetujui',
              'disetujui'=> 'Disetujui',
              'ditolak'  => 'Ditolak',
              'selesai'  => 'Selesai',
            ];
          @endphp

          <div class="rounded-2xl border-2 border-brand-200 bg-white p-6 shadow-lift hover:shadow-xl transition-all">
            <div class="flex justify-between items-start mb-4">
              <div class="flex-1">
                <h3 class="font-bold text-slate-900 text-lg mb-1">{{ $namaTamu }}</h3>
                <p class="text-sm text-slate-600 flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                  </svg>
                  {{ $emailTamu }}
                </p>
              </div>
              <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-bold border {{ $badge[$status] ?? 'bg-slate-100 border-slate-300 text-slate-800' }}">
                {{ $label[$status] ?? ucfirst($status) }}
              </span>
            </div>

            <div class="space-y-3 text-sm">
              <div class="flex justify-between items-center">
                <span class="text-slate-600 font-medium flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                  </svg>
                  No. HP:
                </span>
                <span class="text-slate-900 font-bold">{{ $hpTamu }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-slate-600 font-medium flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                  </svg>
                  Instansi:
                </span>
                <span class="text-slate-900 font-bold text-right">{{ $namaInstansi }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-slate-600 font-medium">Kategori:</span>
                <span class="text-slate-900 font-bold">{{ $katInstansi ? strtoupper($katInstansi) : '—' }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-slate-600 font-medium">Jumlah:</span>
                <span class="text-slate-900 font-bold">{{ is_numeric($jumlah) ? number_format($jumlah) : $jumlah }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-slate-600 font-medium flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                  </svg>
                  Tanggal:
                </span>
                <span class="text-slate-900 font-bold">{{ $dt->translatedFormat('d M Y') }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-slate-600 font-medium">Waktu:</span>
                <span class="text-slate-900 font-bold">{{ $dt->format('H:i') }} WITA</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-slate-600 font-medium">Bertemu:</span>
                <span class="text-slate-900 font-bold text-right">{{ $bertemu }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-slate-600 font-medium flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                  </svg>
                  Dokumen:
                </span>
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
                       class="inline-flex items-center gap-2 rounded-xl bg-amber-100 px-3 py-2 text-xs font-bold hover:bg-amber-200 text-slate-800 border-2 border-amber-300 transition-colors">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                      </svg>
                      Lihat
                    </a>
                  @else
                    <span class="text-slate-400 text-xs font-medium">—</span>
                  @endif
                </div>
              </div>
            </div>

            <div class="mt-6 pt-4 border-t-2 border-brand-100">
              @if(in_array($status, ['diterima','disetujui']) && isset($row->id))
                <form method="POST" action="{{ route('resepsionis.kunjungan.selesai', $row->id) }}" class="w-full">
                  @csrf
                  <button type="submit"
                          class="ripple w-full inline-flex items-center justify-center gap-2
                                rounded-xl bg-emerald-600 hover:bg-emerald-700
                                px-4 py-3 text-sm font-bold
                                text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/60 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    Tandai Selesai
                  </button>
                </form>
              @else
                <div class="text-center text-slate-400 text-sm py-3 font-medium">Tidak ada aksi tersedia</div>
              @endif
            </div>
          </div>
        @empty
          <div class="rounded-2xl border-2 border-brand-200 bg-white p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-400 mb-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            <h3 class="text-lg font-bold text-slate-600 mb-2">Tidak ada data ditemukan</h3>
            <p class="text-slate-500">Coba ubah filter pencarian Anda</p>
          </div>
        @endforelse
      </div>

      @if(method_exists($tamu, 'links'))
        <div class="flex flex-wrap items-center justify-between mt-8 text-sm text-slate-700">
          <div class="mb-4 sm:mb-0">
            <span class="font-bold text-slate-900">Menampilkan</span>
            <span class="font-bold text-brand-700">{{ $tamu->firstItem() }}</span>
            –
            <span class="font-bold text-brand-700">{{ $tamu->lastItem() }}</span>
            <span class="font-bold text-slate-900">dari</span>
            <span class="font-bold text-brand-700">{{ $tamu->total() }}</span>
            <span class="font-bold text-slate-900">data</span>
          </div>
          <div class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($tamu->onFirstPage())
              <span class="px-4 py-2.5 rounded-xl border-2 border-brand-300 bg-brand-50 opacity-50 cursor-not-allowed font-bold">‹</span>
            @else
              <a href="{{ $tamu->previousPageUrl() }}" 
                 class="px-4 py-2.5 rounded-xl border-2 border-brand-300 bg-brand-50 hover:bg-brand-100 font-bold transition-colors">‹</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($tamu->getUrlRange(1, $tamu->lastPage()) as $page => $url)
              @if ($page == $tamu->currentPage())
                <span class="px-4 py-2.5 rounded-xl border-2 text-sm bg-brand-500 border-brand-500 text-white shadow-yellow-glow font-bold">{{ $page }}</span>
              @else
                <a href="{{ $url }}" 
                   class="px-4 py-2.5 rounded-xl border-2 text-sm bg-brand-50 border-brand-300 text-slate-800 hover:bg-brand-100 font-bold transition-colors">{{ $page }}</a>
              @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($tamu->hasMorePages())
              <a href="{{ $tamu->nextPageUrl() }}" 
                 class="px-4 py-2.5 rounded-xl border-2 border-brand-300 bg-brand-50 hover:bg-brand-100 font-bold transition-colors">›</a>
            @else
              <span class="px-4 py-2.5 rounded-xl border-2 border-brand-300 bg-brand-50 opacity-50 cursor-not-allowed font-bold">›</span>
            @endif
          </div>
        </div>
      @endif
    </div>
  </main>
@endsection