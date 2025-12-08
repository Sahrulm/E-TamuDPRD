@extends('layouts.host')

@section('content')
{{-- DASHBOARD (metrik + kartu) --}}
<section id="panel-dashboard" class="animate-fadeUp">
  <!-- Ringkasan status -->
  <div class="mb-6 sm:mb-8 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-3 text-sm sm:text-base text-slate-700 font-medium">
      <span class="inline-flex h-3 w-3 rounded-full bg-emerald-500 animate-pulseSoft"></span>
      <span>Sistem aktif · Data diperbarui secara real-time</span>
    </div>
    <div class="inline-flex flex-wrap gap-3 text-xs sm:text-sm">
      <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-emerald-800 font-bold border-2 border-emerald-300">
        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
        Diterima
      </span>
      <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-4 py-2 text-rose-800 font-bold border-2 border-rose-300">
        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
        Ditolak
      </span>
      <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-4 py-2 text-amber-800 font-bold border-2 border-amber-300">
        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
        Menunggu
      </span>
    </div>
  </div>

  <!-- Metrik (stack di mobile) -->
  <div class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-3 mb-6 sm:mb-8">
    <div class="rounded-2xl border-2 border-brand-200 bg-white p-6 shadow-lift hover:shadow-yellow-glow transition-all duration-300 relative overflow-hidden">
      <div class="absolute -right-8 -top-8 h-20 w-20 rounded-full bg-gradient-to-br from-brand-300/60 to-amber-300/30"></div>
      <div class="relative">
        <div class="flex items-center justify-between gap-3 mb-3">
          <div class="text-slate-600 text-sm sm:text-base font-bold">Total Pengajuan Hari Ini</div>
          <div class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-brand-500 text-white text-sm font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/>
            </svg>
          </div>
        </div>
        <div class="text-3xl sm:text-4xl font-extrabold text-slate-900">
          {{ $totalHariIni }}
        </div>
        <p class="mt-2 text-xs text-slate-600 font-medium">Pengajuan hari ini</p>
      </div>
    </div>
    <div class="rounded-2xl border-2 border-emerald-200 bg-emerald-50 p-6 shadow-lift hover:shadow-yellow-glow transition-all duration-300 relative overflow-hidden">
      <div class="absolute -right-8 -top-8 h-20 w-20 rounded-full bg-gradient-to-br from-emerald-400/40 to-emerald-300/10"></div>
      <div class="relative">
        <div class="flex items-center justify-between gap-3 mb-3">
          <div class="text-emerald-900 text-sm sm:text-base font-bold">Diterima Minggu Ini</div>
          <div class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-emerald-500 text-white text-sm font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
          </div>
        </div>
        <div class="text-3xl sm:text-4xl font-extrabold text-emerald-700">
          {{ $diterimaMingguIni }}
        </div>
        <p class="mt-2 text-xs text-emerald-700 font-medium">Disetujui minggu ini</p>
      </div>
    </div>
    <div class="rounded-2xl border-2 border-rose-200 bg-rose-50 p-6 shadow-lift hover:shadow-yellow-glow transition-all duration-300 relative overflow-hidden">
      <div class="absolute -right-8 -top-8 h-20 w-20 rounded-full bg-gradient-to-br from-rose-400/40 to-rose-300/10"></div>
      <div class="relative">
        <div class="flex items-center justify-between gap-3 mb-3">
          <div class="text-rose-900 text-sm sm:text-base font-bold">Ditolak Minggu Ini</div>
          <div class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-rose-500 text-white text-sm font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
          </div>
        </div>
        <div class="text-3xl sm:text-4xl font-extrabold text-rose-600">
          {{ $ditolakMingguIni }}
        </div>
        <p class="mt-2 text-xs text-rose-700 font-medium">Ditolak minggu ini</p>
      </div>
    </div>
  </div>

  <!-- Kartu pengajuan ringkas -->
  <div class="grid gap-4 sm:gap-6 [grid-template-columns:repeat(1,minmax(0,1fr))] sm:[grid-template-columns:repeat(2,minmax(0,1fr))] xl:[grid-template-columns:repeat(3,minmax(0,1fr))]">
    @forelse($pengajuan as $row)
      @if($row->status_sekarang === 'menunggu')
      <article class="rounded-2xl border-2 border-amber-200 bg-white p-6 shadow-lift hover:shadow-xl hover:border-brand-300 hover:-translate-y-1 transition-all duration-300"
               data-item-id="{{ $row->id }}">
        <div class="flex items-start justify-between gap-4 mb-4">
          <div class="flex-1">
            <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2">
              {{ $row->nama }}
            </h3>
            <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
              <div class="text-slate-600 font-medium">No. HP</div>
              <div class="font-bold text-slate-900 text-right sm:text-left">{{ $row->no_hp }}</div>
              <div class="text-slate-600 font-medium">Instansi</div>
              <div class="font-bold text-slate-900 text-right sm:text-left">{{ $row->instansi_nama }}</div>
              <div class="text-slate-600 font-medium">Bertemu</div>
              <div class="font-bold text-slate-900 text-right sm:text-left">{{ $row->bertemu_dengan }}</div>
            </div>
          </div>
          <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-800 border-2 border-amber-300">
            Menunggu
          </span>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-3">
          <form method="POST" action="{{ route('host.kunjungan.terima', $row->id) }}" class="inline w-full sm:w-auto">
            @csrf
            <button type="submit" class="ripple w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-5 py-3 font-bold text-white active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-brand-400/70 transition-all duration-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
              Terima
            </button>
          </form>
          <button type="button" 
                  class="btn-tolak ripple w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-amber-100 hover:bg-amber-200 border-2 border-amber-300 px-5 py-3 font-bold text-amber-800 active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-amber-300/80 transition-all duration-200"
                  data-id="{{ $row->id }}"
                  data-nama="{{ $row->nama }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
            Tolak
          </button>
        </div>
      </article>
      @endif
    @empty
      <div class="col-span-full rounded-2xl border-2 border-amber-200 bg-white p-8 sm:p-12 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-amber-400 mb-4" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
        </svg>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Belum ada pengajuan menunggu</h3>
        <p class="text-slate-600">Semua pengajuan telah diproses dengan baik</p>
      </div>
    @endforelse
  </div>
</section>

{{-- DATA PENGAJUAN (desktop: tabel; mobile: kartu list) --}}
<section id="panel-data" class="hidden animate-fadeUp">
  <div class="rounded-2xl border-2 border-amber-200 bg-white p-6 shadow-lift space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
          </svg>
          Data Pengajuan Menunggu
        </h2>
        <p class="text-sm sm:text-base text-slate-600 mt-2 font-medium">
          Seluruh tamu yang melakukan pengajuan dan masih berstatus menunggu.
        </p>
      </div>
      <div class="flex flex-wrap items-center gap-3">
        <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-4 py-2 text-sm font-bold text-amber-800 border-2 border-amber-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
            <path d="M11 17h2v-6h-2v6zm1-15C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zM11 9h2V7h-2v2z"/>
          </svg>
          Scroll horizontal untuk melihat semua kolom
        </span>
      </div>
    </div>

    <!-- MOBILE LIST (≤ md) -->
    <div class="md:hidden space-y-4">
      @forelse($tamuMenunggu as $row)
        @if($row->status_sekarang === 'menunggu')
          @php
            $tz = 'Asia/Makassar';
            $tglStr = $row->tanggal_kunjungan instanceof \DateTimeInterface
                      ? $row->tanggal_kunjungan->format('Y-m-d')
                      : (string) ($row->tanggal_kunjungan ?? now($tz)->toDateString());
            $rawWkt = $row->waktu_kunjungan ?? '00:00:00';
            $wktStr = $row->waktu_kunjungan instanceof \DateTimeInterface
                      ? $row->waktu_kunjungan->format('H:i:s')
                      : (strlen((string) $rawWkt) === 5 ? ($rawWkt . ':00') : (string) $rawWkt);
            $dt = \Illuminate\Support\Carbon::parse(trim($tglStr.' '.$wktStr), $tz);

            $id            = $row->id ?? null;
            $namaTamu      = $row->nama ?? '—';
            $emailTamu     = $row->email ?? '—';
            $hpTamu        = $row->no_hp ?? '—';
            $namaInstansi  = $row->instansi_nama ?? '—';
            $keperluan     = $row->keperluan ?? '—';
            $jumlah        = $row->jumlah_peserta ?? $row->jumlah ?? '—';
            $bertemu       = $row->bertemu_dengan ?? '—';
            $dok           = $row->dokumen ?? null;

            $dokUrl = null;
            if ($dok) {
              $dokUrl = \Illuminate\Support\Str::startsWith($dok, ['http://','https://'])
                       ? $dok
                       : (\Illuminate\Support\Str::startsWith($dok, 'storage/')
                          ? url($dok)
                          : \Illuminate\Support\Facades\Storage::url($dok));
            }
          @endphp

          <article class="rounded-2xl border-2 border-amber-200 bg-amber-50 p-6 shadow-lift hover:shadow-md transition-all">
            <div class="flex items-start justify-between gap-4 mb-4">
              <h3 class="font-bold text-lg leading-tight text-slate-900 flex-1 text-center">
                {{ $namaTamu }}
              </h3>
              <div class="text-right text-sm text-slate-600">
                <div class="font-medium">{{ $dt->translatedFormat('d M Y') }}</div>
                <div class="font-bold text-slate-800">{{ $dt->format('H:i') }} WITA</div>
              </div>
            </div>

            <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm mb-4">
              <dt class="text-slate-600 font-medium flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
                HP
              </dt>
              <dd class="font-bold text-right sm:text-left">{{ $hpTamu }}</dd>
              <dt class="text-slate-600 font-medium flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                Email
              </dt>
              <dd class="font-bold text-right sm:text-left truncate">{{ $emailTamu }}</dd>
              <dt class="text-slate-600 font-medium">Instansi</dt>
              <dd class="font-bold text-right sm:text-left">{{ $namaInstansi }}</dd>
              <dt class="text-slate-600 font-medium">Bertemu</dt>
              <dd class="font-bold text-right sm:text-left">{{ $bertemu }}</dd>
              <dt class="text-slate-600 font-medium">Peserta</dt>
              <dd class="font-bold text-right sm:text-left">
                {{ is_numeric($jumlah) ? number_format($jumlah) : ($jumlah ?? '—') }}
              </dd>
            </dl>

            <div class="mb-4">
              <div class="text-slate-600 font-medium mb-2">Keperluan</div>
              <div class="font-medium text-slate-800 bg-white p-3 rounded-xl border border-amber-100">
                {{ $keperluan }}
              </div>
            </div>

            <div class="flex items-center gap-3 mb-4">
              @if($dokUrl)
                <a href="{{ $dokUrl }}" target="_blank"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-bold hover:bg-amber-100 text-slate-800 border-2 border-amber-200 shadow-sm transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                  </svg>
                  Lihat Dokumen
                </a>
              @endif
            </div>

            <div class="grid grid-cols-2 gap-3">
              @if($id)
                <form method="POST" action="{{ route('host.kunjungan.terima', $id) }}">
                  @csrf
                  <button type="submit" class="ripple w-full inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-3 text-white font-bold active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-brand-400/70 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    Terima
                  </button>
                </form>
                <button type="button" 
                        class="btn-tolak ripple w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-100 hover:bg-amber-200 border-2 border-amber-300 px-4 py-3 text-amber-800 font-bold active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-amber-300/80 transition-all"
                        data-id="{{ $id }}"
                        data-nama="{{ $namaTamu }}">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                  </svg>
                  Tolak
                </button>
              @else
                <div class="col-span-2 text-center text-slate-400 text-sm py-3">—</div>
              @endif
            </div>
          </article>
        @endif
      @empty
        <div class="rounded-2xl border-2 border-amber-200 bg-amber-50 p-8 text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-amber-400 mb-4" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <h3 class="text-xl font-bold text-slate-700 mb-2">Tidak ada tamu menunggu verifikasi</h3>
          <p class="text-slate-600">Semua pengajuan telah diproses</p>
        </div>
      @endforelse
    </div>

    <!-- DESKTOP TABLE (≥ md) -->
    <div class="hidden md:block">
      <div class="overflow-x-auto rounded-xl border-2 border-amber-200 bg-amber-50/40">
        <table class="min-w-[1400px] w-full text-sm">
          <thead class="bg-gradient-to-r from-amber-100 to-amber-50">
            <tr class="text-left text-slate-700 border-b-2 border-amber-200">
              <th class="py-4 pl-6 pr-4 sticky-col text-sm font-bold tracking-wide border-r border-amber-200 text-center">Aksi</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-amber-200">Nama</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-amber-200">Email</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-amber-200">Nomor HP</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-amber-200">Nama Instansi</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-amber-200">Keperluan</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-amber-200">Jumlah Peserta</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-amber-200">Tanggal Kunjungan</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-amber-200">Waktu Kunjungan</th>
              <th class="py-4 pr-4 text-center text-sm font-bold tracking-wide border-r border-amber-200">Bertemu Dengan</th>
              <th class="py-4 pr-6 text-center text-sm font-bold tracking-wide">Dokumen</th>
            </tr>
          </thead>
          <tbody class="align-top bg-white">
            @forelse($tamuMenunggu as $row)
              @if($row->status_sekarang === 'menunggu')
                @php
                  $tz = 'Asia/Makassar';
                  $tglStr = $row->tanggal_kunjungan instanceof \DateTimeInterface
                            ? $row->tanggal_kunjungan->format('Y-m-d')
                            : (string) ($row->tanggal_kunjungan ?? now($tz)->toDateString());
                  $rawWkt = $row->waktu_kunjungan ?? '00:00:00';
                  $wktStr = $row->waktu_kunjungan instanceof \DateTimeInterface
                            ? $row->waktu_kunjungan->format('H:i:s')
                            : (strlen((string) $rawWkt) === 5 ? ($rawWkt . ':00') : (string) $rawWkt);
                  $dt = \Illuminate\Support\Carbon::parse(trim($tglStr.' '.$wktStr), $tz);

                  $id            = $row->id ?? null;
                  $namaTamu      = $row->nama ?? '—';
                  $emailTamu     = $row->email ?? '—';
                  $hpTamu        = $row->no_hp ?? '—';
                  $namaInstansi  = $row->instansi_nama ?? '—';
                  $keperluan     = $row->keperluan ?? '—';
                  $jumlah        = $row->jumlah_peserta ?? $row->jumlah ?? '—';
                  $bertemu       = $row->bertemu_dengan ?? '—';
                  $dok           = $row->dokumen ?? null;

                  $dokUrl = null;
                  if ($dok) {
                    $dokUrl = \Illuminate\Support\Str::startsWith($dok, ['http://','https://'])
                            ? $dok
                            : (\Illuminate\Support\Str::startsWith($dok, 'storage/')
                                ? url($dok)
                                : \Illuminate\Support\Facades\Storage::url($dok));
                  }
                @endphp

                <tr class="border-b border-amber-100 hover:bg-amber-50/60 transition-colors">
                  <td class="py-4 pl-6 pr-4 sticky-col border-r border-amber-100">
                    <div class="flex items-center gap-3">
                      @if($id)
                        <form method="POST" action="{{ route('host.kunjungan.terima', $id) }}" class="inline">
                          @csrf
                          <button type="submit" class="ripple inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 text-white font-bold focus:outline-none focus:ring-2 focus:ring-brand-400/70 text-sm transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                            Terima
                          </button>
                        </form>
                        <button type="button" 
                                class="btn-tolak ripple inline-flex items-center justify-center gap-2 rounded-xl bg-amber-100 hover:bg-amber-200 border-2 border-amber-300 px-4 py-2 text-amber-800 font-bold focus:outline-none focus:ring-2 focus:ring-amber-300/80 text-sm transition-colors"
                                data-id="{{ $id }}"
                                data-nama="{{ $namaTamu }}">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                          </svg>
                          Tolak
                        </button>
                      @else
                        <span class="text-slate-400 text-sm font-medium">—</span>
                      @endif
                    </div>
                  </td>

                  <td class="py-4 pr-4 border-r border-amber-100 font-bold text-slate-900 text-center">{{ $namaTamu }}</td>
                  <td class="py-4 pr-4 border-r border-amber-100 text-slate-700 font-medium text-center">{{ $emailTamu }}</td>
                  <td class="py-4 pr-4 border-r border-amber-100 whitespace-nowrap text-slate-700 font-bold text-center">{{ $hpTamu }}</td>
                  <td class="py-4 pr-4 border-r border-amber-100 text-center text-slate-800 font-bold">{{ $namaInstansi }}</td>
                  <td class="py-4 pr-4 border-r border-amber-100 max-w-[320px] text-slate-700 font-medium text-center">
                    <span class="block truncate" title="{{ $keperluan }}">{{ $keperluan }}</span>
                  </td>
                  <td class="py-4 pr-4 border-r border-amber-100 text-center text-slate-800 font-bold">
                    {{ is_numeric($jumlah) ? number_format($jumlah) : ($jumlah ?? '—') }}
                  </td>
                  <td class="py-4 pr-4 border-r border-amber-100 text-slate-700 font-medium text-center">{{ $dt->translatedFormat('d M Y') }}</td>
                  <td class="py-4 pr-4 border-r border-amber-100 text-slate-800 font-bold text-center">{{ $dt->format('H:i') }} WITA</td>
                  <td class="py-4 pr-4 border-r border-amber-100 text-slate-700 font-medium text-center">{{ $bertemu }}</td>
                  <td class="py-4 pr-6 px-6">
                    @if($dokUrl)
                      <a href="{{ $dokUrl }}" target="_blank"
                         class="inline-flex items-center gap-2 rounded-xl bg-amber-100 px-3 py-2 text-sm font-bold hover:bg-amber-200 text-slate-800 border-2 border-amber-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                        Lihat
                      </a>
                    @else
                      <span class="text-slate-400 text-sm font-medium">—</span>
                    @endif
                  </td>
                </tr>
              @endif
            @empty
              <tr class="border-b border-amber-100">
                <td colspan="11" class="py-12 text-center">
                  <div class="flex flex-col items-center justify-center text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-400 mb-4" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-slate-600 mb-2">Tidak ada tamu menunggu verifikasi</h3>
                    <p class="text-slate-500">Semua pengajuan telah diproses</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection