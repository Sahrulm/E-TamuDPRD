@extends('layouts.resepsionis')

@section('title', 'E-Tamu DPRD – Dashboard')

@section('content')
  <!-- HERO BACKDROP -->
  <section class="pt-24 sm:pt-28 pb-6 bg-gradient-to-br from-brand-50 to-brand-100 relative overflow-hidden">
    <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full border border-brand-300/60"></div>
    <div class="pointer-events-none absolute -right-20 -bottom-24 h-80 w-80 rounded-full border-4 border-brand-200/70"></div>
    <div class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-8 h-20 w-20 rounded-full pattern-dots opacity-40"></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 animate-fadeUp">Panel Resepsionis</h1>
      <p class="text-slate-700 mt-2 text-sm sm:text-base animate-fadeUp" style="animation-delay:.06s">
        Ringkasan aktivitas & tindakan cepat untuk melayani tamu.
        <span class="ml-2 text-xs text-slate-600 font-medium">Tanggal: {{ $tanggal ?? now('Asia/Makassar')->toDateString() }}</span>
      </p>
    </div>
  </section>

  <!-- VIEWS (Slider) -->
  <main class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8 pb-20">
    <div id="viewsWrap" class="views-wrap">
      <div id="views" class="views">
        <!-- ====== VIEW 0: DASHBOARD ====== -->
        <section class="view">
          <div class="py-4 sm:py-6">
            <!-- Stats (dinamis dari controller) -->
            <div class="grid gap-4 sm:gap-5 grid-cols-2 lg:grid-cols-4">
              <div class="rounded-2xl bg-white ring-2 ring-brand-200 p-5 shadow-lift anim-on hover:shadow-yellow-glow transition-all duration-300">
                <div class="flex items-center gap-3">
                  <div class="rounded-xl bg-brand-500/20 p-2 text-brand-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                  </div>
                  <div>
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Total Tamu Hari Ini</div>
                    <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-brand-700">
                      <span class="counter" data-target="{{ (int)($tamu_hari_ini ?? 0) }}">{{ (int)($tamu_hari_ini ?? 0) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="rounded-2xl bg-white ring-2 ring-amber-200 p-5 shadow-lift anim-on hover:shadow-yellow-glow transition-all duration-300">
                <div class="flex items-center gap-3">
                  <div class="rounded-xl bg-amber-500/20 p-2 text-amber-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 16h2v2h-2zm2-12h-2v8h2z"/>
                    </svg>
                  </div>
                  <div>
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Menunggu Verifikasi</div>
                    <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-amber-700">
                      <span class="counter" data-target="{{ (int)($menunggu ?? 0) }}">{{ (int)($menunggu ?? 0) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="rounded-2xl bg-white ring-2 ring-emerald-200 p-5 shadow-lift anim-on hover:shadow-yellow-glow transition-all duration-300">
                <div class="flex items-center gap-3">
                  <div class="rounded-xl bg-emerald-500/20 p-2 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                  </div>
                  <div>
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Kunjungan Disetujui</div>
                    <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-emerald-700">
                      <span class="counter" data-target="{{ (int)($disetujui ?? 0) }}">{{ (int)($disetujui ?? 0) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="rounded-2xl bg-white ring-2 ring-rose-200 p-5 shadow-lift anim-on hover:shadow-yellow-glow transition-all duration-300">
                <div class="flex items-center gap-3">
                  <div class="rounded-xl bg-rose-500/20 p-2 text-rose-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                  </div>
                  <div>
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Kunjungan Ditolak</div>
                    <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-rose-700">
                      <span class="counter" data-target="{{ (int)($ditolak ?? 0) }}">{{ (int)($ditolak ?? 0) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Aksi cepat -->
            <div class="mt-6 sm:mt-8 rounded-2xl border-2 border-brand-200 bg-white p-5 sm:p-6 shadow-lift anim-on">
              <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                  <h3 class="font-bold text-lg text-slate-900">Aksi Cepat</h3>
                  <p class="text-sm text-slate-600 mt-1">Kelola kunjungan tamu dengan cepat dan efisien</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                  <button id="btnTambah" class="ripple w-full sm:w-auto inline-flex items-center justify-center gap-3 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-5 py-3 text-white font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                    Tambah Tamu Baru
                  </button>
                  <a href="{{ route('resepsionis.datatamu') }}"
                    class="ripple w-full sm:w-auto inline-flex items-center justify-center gap-3 rounded-xl bg-white border-2 border-brand-300 px-5 py-3 font-bold text-brand-700 hover:bg-brand-50 hover:border-brand-400 transition-all duration-300 text-sm"
                    role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                    </svg>
                    Lihat Data Tamu
                  </a>
                </div>
              </div>
            </div>

            <!-- Kunjungan Hari Ini (dinamis) -->
            <div class="mt-6 sm:mt-8">
              <div class="flex items-center gap-3 mb-4">
                <h3 class="text-xl font-bold text-slate-900">Kunjungan Hari Ini</h3>
                <span class="rounded-full bg-brand-500 px-3 py-1 text-xs font-bold text-white">
                  {{ count($kunjungan_hari_ini ?? []) }}
                </span>
              </div>

              @php
                $badge = [
                  'menunggu' => 'bg-amber-100 border-amber-300 text-amber-800',
                  'diterima' => 'bg-emerald-100 border-emerald-300 text-emerald-800',
                  'ditolak'  => 'bg-rose-100 border-rose-300 text-rose-800',
                  'selesai'  => 'bg-slate-100 border-slate-300 text-slate-800',
                ];
                $label = [
                  'menunggu' => 'Menunggu',
                  'diterima' => 'Disetujui',
                  'ditolak'  => 'Ditolak',
                  'selesai'  => 'Selesai',
                ];
              @endphp

              <div class="mt-4 grid gap-4 sm:gap-5 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @forelse(($kunjungan_hari_ini ?? []) as $k)
                  <div class="relative rounded-2xl border-2 border-slate-200 bg-white p-5 shadow-sm hover:shadow-lift hover:border-brand-300 transition-all duration-300 dot-corner anim-on">
                    <div class="flex items-start justify-between">
                      <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                          </svg>
                          {{ \Illuminate\Support\Carbon::parse($k->waktu_kunjungan)->timezone('Asia/Makassar')->format('H:i') }} WITA
                        </div>
                        <div class="mt-2 font-bold text-slate-900 text-base truncate">
                          {{ $k->tamu->nama ?? '—' }}
                        </div>
                        <div class="text-sm text-slate-700 truncate mt-1">
                          {{ $k->instansi_nama ?? $k->tamu->instansi_nama ?? '—' }}
                        </div>
                        <div class="text-sm text-slate-600 mt-1">
                          {{ $k->keperluan }}
                        </div>
                      </div>

                      <span class="ml-3 inline-flex rounded-full px-3 py-1 text-xs font-bold border {{ $badge[$k->status_sekarang] ?? 'bg-slate-100 border-slate-300 text-slate-800' }}">
                        {{ $label[$k->status_sekarang] ?? $k->status_sekarang }}
                      </span>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                      <div class="flex items-center gap-2 text-sm text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        @php $hp = $k->tamu->no_hp ?? null; @endphp
                        {{ $hp ? preg_replace('/(\d{3})(\d{3})(\d+)/', '$1•••$3', preg_replace('/\D/','',$hp)) : '—' }}
                      </div>
                      
                      <button
                        type="button"
                        class="ripple rounded-xl bg-brand-500 px-4 py-2 text-xs font-bold text-white hover:bg-brand-600 btnDetail transition-colors"
                        data-nama="{{ $k->tamu->nama ?? '—' }}"
                        data-email="{{ $k->tamu->email ?? '—' }}"
                        data-nohp="{{ $k->tamu->no_hp ?? '—' }}"
                        data-jumlah-peserta="{{ $k->jumlah_peserta ? (string) $k->jumlah_peserta : '—' }}"
                        data-instansi="{{ $k->instansi_nama ?? ($k->tamu->instansi_nama ?? '—') }}"
                        data-tanggal="{{ \Illuminate\Support\Carbon::parse($k->tanggal_kunjungan)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}"
                        data-waktu="{{ \Illuminate\Support\Carbon::parse($k->waktu_kunjungan)->timezone('Asia/Makassar')->format('H:i') }} WITA"
                        data-bertemu-dengan="{{ $k->sub_kategori?? ($k->sub_kategori ?? '—') }}"
                      >
                        Detail
                      </button>
                    </div>
                  </div>
                @empty
                  <div class="col-span-full">
                    <div class="rounded-2xl border-2 border-slate-200 bg-white p-8 text-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                      </svg>
                      <h4 class="mt-4 text-lg font-semibold text-slate-700">Tidak ada tamu berkunjung hari ini</h4>
                      <p class="text-slate-600 mt-2">Semua tamu telah terlayani dengan baik</p>
                    </div>
                  </div>
                @endforelse
              </div>
            </div>
          </div>
        </section>

        <!-- ====== VIEW 1: DATA TAMU (dummy lokal) ====== -->
        <section class="view">
          <div class="py-6">
            <h2 class="text-2xl font-bold text-slate-900 anim-on">Data Tamu</h2>
            <div class="mt-6 rounded-2xl border-2 border-slate-200 bg-white p-6 shadow-lift anim-on">
              <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <form action="#" method="GET" class="grid gap-4 grid-cols-1 sm:grid-cols-4 w-full">
                  <div class="sm:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kata kunci</label>
                    <input name="q" type="text" class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-sm" placeholder="Nama/Instansi/Kode">
                  </div>
                  <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-sm">
                      <option value="">Semua</option>
                      <option value="menunggu">Menunggu</option>
                      <option value="disetujui">Disetujui</option>
                      <option value="ditolak">Ditolak</option>
                      <option value="selesai">Selesai</option>
                    </select>
                  </div>
                  <div class="flex items-end">
                    <button type="submit" class="ripple w-full rounded-xl bg-brand-500 px-4 py-3 text-white font-bold shadow hover:shadow-lift transition-all text-sm flex items-center justify-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                      </svg>
                      Cari
                    </button>
                  </div>
                </form>
              </div>

              <div class="mt-6 overflow-x-auto rounded-xl border-2 border-slate-200">
                <table class="min-w-full text-sm">
                  <thead class="bg-slate-50">
                    <tr class="text-left text-slate-700 border-b-2 border-slate-200">
                      <th class="py-4 px-4 font-bold">Tanggal</th>
                      <th class="py-4 px-4 font-bold">Nama</th>
                      <th class="py-4 px-4 font-bold">Instansi</th>
                      <th class="py-4 px-4 font-bold">Tujuan</th>
                      <th class="py-4 px-4 font-bold">Status</th>
                      <th class="py-4 px-4 font-bold">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="align-top">
                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                      <td class="py-4 px-4 font-medium">6 Nov 2025</td>
                      <td class="py-4 px-4 font-bold text-slate-900">Rudi Hartono</td>
                      <td class="py-4 px-4">SMA 1</td>
                      <td class="py-4 px-4">Audiensi</td>
                      <td class="py-4 px-4">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold border bg-amber-100 border-amber-300 text-amber-800">Menunggu</span>
                      </td>
                      <td class="py-4 px-4">
                        <button class="ripple rounded-xl bg-brand-500 px-4 py-2 text-white font-bold shadow hover:shadow-lift text-xs">Proses</button>
                        <a href="#" class="ml-2 inline-block rounded-xl bg-slate-100 px-4 py-2 text-xs font-bold hover:bg-slate-200 transition-colors">Detail</a>
                      </td>
                    </tr>
                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                      <td class="py-4 px-4 font-medium">6 Nov 2025</td>
                      <td class="py-4 px-4 font-bold text-slate-900">Dinas PU</td>
                      <td class="py-4 px-4">Pemkot</td>
                      <td class="py-4 px-4">Koordinasi</td>
                      <td class="py-4 px-4">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold border bg-emerald-100 border-emerald-300 text-emerald-800">Disetujui</span>
                      </td>
                      <td class="py-4 px-4">
                        <a href="#" class="inline-block rounded-xl bg-slate-100 px-4 py-2 text-xs font-bold hover:bg-slate-200 transition-colors">Detail</a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- ====== VIEW 2: NOTIFIKASI ====== -->
        <section class="view">
          <div class="py-6">
            <h2 class="text-2xl font-bold text-slate-900 anim-on">Notifikasi</h2>
            <div class="mt-6 grid gap-4">
              <div class="rounded-2xl border-2 border-amber-200 bg-amber-50 p-5 shadow-sm flex items-start gap-4 anim-on hover:shadow-md transition-all">
                <span class="mt-1 inline-flex h-4 w-4 rounded-full bg-amber-500"></span>
                <div class="flex-1 min-w-0">
                  <div class="font-bold text-slate-900 text-base">Verifikasi baru diperlukan</div>
                  <div class="text-sm text-slate-700 mt-1">Pengajuan kunjungan dari SMA 1 memerlukan verifikasi.</div>
                </div>
                <div class="ml-auto text-xs font-medium text-amber-700 whitespace-nowrap">baru saja</div>
              </div>
              <div class="rounded-2xl border-2 border-emerald-200 bg-emerald-50 p-5 shadow-sm flex items-start gap-4 anim-on hover:shadow-md transition-all">
                <span class="mt-1 inline-flex h-4 w-4 rounded-full bg-emerald-500"></span>
                <div class="flex-1 min-w-0">
                  <div class="font-bold text-slate-900 text-base">Kunjungan disetujui</div>
                  <div class="text-sm text-slate-700 mt-1">Koordinasi Dinas PU telah disetujui.</div>
                </div>
                <div class="ml-auto text-xs font-medium text-emerald-700 whitespace-nowrap">1 jam lalu</div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>

  <!-- ========= MODAL MULTI-STEP (TAMBAH TAMU) ========= -->
  <div id="applyModal" class="invisible opacity-0 fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 md:p-6 transition-all duration-200">
    <!-- overlay dengan blur + gelap -->
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

    <div id="modalCard" class="relative w-full max-w-4xl overflow-hidden rounded-2xl sm:rounded-3xl bg-white shadow-2xl ring-1 ring-black/5 transition-transform mx-2 sm:mx-4">
      <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b-2 border-brand-200 bg-white/90 px-4 sm:px-6 py-4 sm:py-5 backdrop-blur">
        <div class="flex-1 min-w-0">
          <h3 class="text-lg sm:text-xl md:text-2xl font-extrabold text-slate-900 truncate">Pengajuan Kunjungan Tamu</h3>
          <p class="mt-1 text-slate-600 text-xs sm:text-sm hidden sm:block">Silakan lengkapi formulir di bawah ini dengan data yang benar</p>
        </div>
        <button id="closeModalBtn" class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-300 flex-shrink-0" aria-label="Tutup">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
      </div>

      <div class="px-4 sm:px-6 pt-4">
        <ol class="flex items-center gap-4 sm:gap-6 overflow-x-auto pb-2">
          <li class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
            <span id="step1State" class="grid h-7 w-7 sm:h-8 sm:w-8 place-items-center rounded-full border-2 border-brand-500 bg-brand-500 text-white font-semibold text-sm">1</span>
            <span class="text-sm font-bold text-slate-900 whitespace-nowrap">Data Keperluan</span>
          </li>
          <li class="flex items-center gap-2 sm:gap-3 flex-shrink-0 opacity-80">
            <span id="step2State" class="grid h-7 w-7 sm:h-8 sm:w-8 place-items-center rounded-full border-2 border-slate-300 text-slate-500 font-semibold text-sm">2</span>
            <span class="text-sm font-medium text-slate-600 whitespace-nowrap">Pihak Tujuan &amp; Dokumen</span>
          </li>
        </ol>
      </div>

      <form id="visitForm" action="{{ route('resepsionis.tambah.store') }}" method="POST" enctype="multipart/form-data" class="max-h-[70vh] sm:max-h-[75vh] overflow-y-auto px-4 sm:px-6 pb-4 sm:pb-6">
        @csrf

        <!-- STEP 1 -->
        <section id="step1" class="mt-4 sm:mt-6 space-y-4 sm:space-y-6">
          <div class="rounded-2xl border-2 border-brand-200 bg-brand-50 p-4 sm:p-5">
            <div class="mb-3 sm:mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <h4 class="text-base sm:text-lg font-bold text-slate-900">Informasi Pemohon</h4>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input required name="nama" type="text" class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-sm" placeholder="Nama lengkap">
                @error('nama') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                <input required name="email" type="email" class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-sm" placeholder="nama@email.com">
                @error('email') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Handphone (WhatsApp) <span class="text-red-500">*</span></label>
                <div class="flex rounded-xl border-2 border-slate-300 bg-white shadow-sm focus-within:border-brand-500 focus-within:ring-2 focus-within:ring-brand-200">
                  <span class="inline-flex items-center rounded-l-xl bg-slate-50 px-4 text-slate-600 select-none text-sm font-medium">+62</span>
                  <input required name="no_hp" type="tel" class="w-full rounded-r-xl px-4 py-3 focus:outline-none text-sm" placeholder="81234567890">
                </div>
                <p class="mt-1 text-xs text-slate-600 font-medium">Format: 812-3456-7890 (tanpa +62)</p>
                @error('no_hp') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Peserta <span class="text-red-500">*</span></label>
                <input required name="jumlah" type="number" min="1" max="50" class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-sm" placeholder="cth: 10">
                <p class="mt-1 text-xs text-slate-600 font-medium">Maksimal 50 orang per kunjungan</p>
                @error('jumlah') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <!-- Informasi Instansi/Organisasi -->
          <div class="rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 sm:p-5">
            <div class="mb-3 sm:mb-4 flex items-center gap-3">
              <div class="rounded-full bg-slate-500/20 p-2 text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              </div>
              <h4 class="text-base sm:text-lg font-bold text-slate-900">Informasi Instansi/Organisasi</h4>
            </div>

            <label class="block text-sm font-bold text-slate-700 mb-3">Instansi/Daerah Asal <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 lg:grid-cols-4">
              <label class="group flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 has-checked:border-brand-500 cursor-pointer text-sm font-medium">
                <input type="radio" name="instansi_kategori" value="opd" class="peer" required><span>OPD</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 has-checked:border-brand-500 cursor-pointer text-sm font-medium">
                <input type="radio" name="instansi_kategori" value="lembaga" class="peer"><span>Lembaga</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 has-checked:border-brand-500 cursor-pointer text-sm font-medium">
                <input type="radio" name="instansi_kategori" value="perseorangan" class="peer"><span>Perseorangan</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 has-checked:border-brand-500 cursor-pointer text-sm font-medium">
                <input type="radio" name="instansi_kategori" value="ormas" class="peer"><span>Ormas</span>
              </label>
            </div>
            @error('instansi_kategori') <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror

            <div class="mt-4 grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2">
              <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Instansi/Organisasi <span class="text-red-500">*</span></label>
                <input required name="instansi_nama" type="text" class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-sm" placeholder="cth: Dinas Pendidikan">
                @error('instansi_nama') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Detail Keperluan <span class="text-red-500">*</span></label>
                <textarea required name="keperluan" rows="3" class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-sm" placeholder="Tuliskan keperluan kunjungan..."></textarea>
                @error('keperluan') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <!-- Jadwal -->
          <div class="rounded-2xl border-2 border-brand-200 bg-brand-50 p-4 sm:p-5">
            <div class="mb-3 sm:mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <h4 class="text-base sm:text-lg font-bold text-slate-900">Jadwal Kunjungan</h4>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input required name="tanggal_kunjungan" type="date" class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-sm">
                @error('tanggal_kunjungan') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Waktu Kunjungan <span class="text-red-500">*</span></label>
                <input required name="waktu_kunjungan" type="time" class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 text-sm">
                @error('waktu_kunjungan') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3">
            <button type="button" id="toStep2" class="ripple rounded-xl bg-brand-500 px-5 py-3 font-bold text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition-all focus:outline-none focus:ring-2 focus:ring-brand-300 text-sm">Lanjut</button>
          </div>
        </section>

        <!-- STEP 2 -->
        <section id="step2" class="mt-4 sm:mt-6 hidden space-y-4 sm:space-y-6">
          <div class="rounded-2xl border-2 border-purple-200 bg-purple-50 p-4 sm:p-5">
            <div class="mb-3 sm:mb-4 flex items-center gap-3">
              <div class="rounded-full bg-purple-500/20 p-2 text-purple-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/></svg>
              </div>
              <h4 class="text-base sm:text-lg font-bold text-slate-900">Pihak yang Dituju</h4>
            </div>

            <label class="block text-sm font-bold text-slate-700 mb-3">Kategori Pihak yang Dituju <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
              <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer text-sm font-medium">
                <input type="radio" name="kategori_pihak_top" value="pimpinan" class="peer" required><span>Pimpinan</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer text-sm font-medium">
                <input type="radio" name="kategori_pihak_top" value="akd" class="peer"><span>AKD</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer text-sm font-medium">
                <input type="radio" name="kategori_pihak_top" value="sekretariat" class="peer"><span>Sekretariat</span>
              </label>
            </div>

            <!-- List dinamis -->
            <div id="pimpinanList" class="mt-4 hidden space-y-3">
              <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Ketua DPRD" required><span>Ketua DPRD</span></label>
              <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Wakil Ketua 1"><span>Wakil Ketua 1</span></label>
              <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Wakil Ketua 2"><span>Wakil Ketua 2</span></label>
            </div>

            <div id="akdList" class="mt-4 hidden space-y-3">
              <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Badan Kehormatan"><span>Badan Kehormatan</span></label>
                <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Badan Anggaran"><span>Badan Anggaran</span></label>
                <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Badan Pembentukan Peraturan Daerah"><span class="truncate">Badan Pembentukan Peraturan Daerah</span></label>
                <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Badan Musyawarah"><span>Badan Musyawarah</span></label>
                <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Komisi 1"><span>Komisi 1</span></label>
                <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Komisi 2"><span>Komisi 2</span></label>
                <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Komisi 3"><span>Komisi 3</span></label>
              </div>
            </div>

            <div id="sekretariatList" class="mt-4 hidden space-y-3">
              <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Sekretaris"><span>Sekretaris</span></label>
              <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Bagian Umum dan Humas"><span>Bagian Umum dan Humas</span></label>
              <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Bagian Keuangan"><span>Bagian Keuangan</span></label>
              <label class="flex items-center gap-3 rounded-xl border-2 bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 text-sm font-medium"><input type="radio" name="kategori_pihak" value="Persidangan dan Perundang-undangan"><span class="truncate">Persidangan dan Perundang-undangan</span></label>
            </div>
          </div>

          <div class="rounded-2xl border-2 border-brand-200 bg-brand-50 p-4 sm:p-5">
            <div class="mb-3 sm:mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
              </div>
              <h4 class="text-base sm:text-lg font-bold text-slate-900">Upload Dokumen</h4>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:gap-5">
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Surat Pemberitahuan/Surat Tugas (opsional)</label>
                <input type="file" name="dokumen" 
                      class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-500 file:px-4 file:py-2 file:font-bold file:text-white hover:file:bg-brand-600 text-sm"
                      accept=".pdf,.jpg,.jpeg,.png">
                <p class="mt-1 text-xs text-slate-600 font-medium">PDF/JPG/PNG maks 5MB. Opsional.</p>
                @error('dokumen') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between flex-col sm:flex-row gap-4">
            <button type="button" id="backTo1" class="w-full sm:w-auto rounded-xl px-5 py-3 text-slate-700 hover:bg-slate-100 transition-colors text-sm font-bold order-2 sm:order-1 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
              </svg>
              Kembali
            </button>
            <div class="flex items-center gap-3 w-full sm:w-auto order-1 sm:order-2">
              <button type="button" id="cancelBtn" class="w-1/2 sm:w-auto rounded-xl px-5 py-3 text-slate-600 hover:bg-slate-100 transition-colors text-sm font-bold">Batal</button>
              <button type="submit" class="ripple w-1/2 sm:w-auto rounded-xl bg-brand-500 px-5 py-3 font-bold text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition-all focus:outline-none focus:ring-2 focus:ring-brand-300 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
                Kirim Pengajuan
              </button>
            </div>
          </div>
        </section>
      </form>
    </div>
  </div>

  <!-- ========= MODAL DETAIL TAMU ========= -->
  <div id="detailModal" class="invisible opacity-0 fixed inset-0 z-[55] flex items-center justify-center p-3 sm:p-4 transition-all duration-200" aria-hidden="true">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <div id="detailCard" class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 mx-2 sm:mx-4">
      <div class="flex items-center justify-between border-b-2 border-brand-200 bg-white/90 px-5 py-4 backdrop-blur">
        <h3 class="text-lg font-bold text-slate-900">Detail Tamu</h3>
        <button id="detailCloseBtn" class="rounded-full p-2 text-slate-500 hover:bg-slate-100" aria-label="Tutup">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
      </div>

      <div class="px-5 py-4">
        <dl class="space-y-4 text-sm">
          <div class="flex justify-between gap-4 items-center"><dt class="text-slate-600 font-bold text-sm">Nama</dt>            <dd id="nama" class="font-bold text-slate-900 text-sm text-right">—</dd></div>
          <div class="flex justify-between gap-4 items-center"><dt class="text-slate-600 font-bold text-sm">Email</dt>           <dd id="email" class="font-bold text-slate-900 text-sm break-all text-right">—</dd></div>
          <div class="flex justify-between gap-4 items-center"><dt class="text-slate-600 font-bold text-sm">Nomor HP</dt>        <dd id="no_hp" class="font-bold text-slate-900 text-sm break-all text-right">—</dd></div>
          <div class="flex justify-between gap-4 items-center"><dt class="text-slate-600 font-bold text-sm">Jumlah Peserta</dt>  <dd id="jumlah" class="font-bold text-slate-900 text-sm text-right">—</dd></div>
          <div class="flex justify-between gap-4 items-center"><dt class="text-slate-600 font-bold text-sm">Nama Instansi</dt>   <dd id="instansi_nama" class="font-bold text-slate-900 text-sm text-right">—</dd></div>
          <div class="flex justify-between gap-4 items-center"><dt class="text-slate-600 font-bold text-sm">Tanggal Kunjungan</dt><dd id="tanggal_kunjungan" class="font-bold text-slate-900 text-sm text-right">—</dd></div>
          <div class="flex justify-between gap-4 items-center"><dt class="text-slate-600 font-bold text-sm">Waktu Kunjungan</dt> <dd id="waktu_kunjungan" class="font-bold text-slate-900 text-sm text-right">—</dd></div>
          <div class="flex justify-between gap-4 items-center"><dt class="text-slate-600 font-bold text-sm">Bertemu Dengan</dt>  <dd id="subnama" class="font-bold text-slate-900 text-sm text-right">—</dd></div>
        </dl>
        <div class="mt-6 text-right">
          <button id="detailCloseBtn2" class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-bold hover:bg-slate-200 transition-colors">Tutup</button>
        </div>
      </div>
    </div>
  </div>
@endsection