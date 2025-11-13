@extends('layouts.default')
@section('content')
  <!-- HERO BACKDROP -->
  <section class="pt-24 sm:pt-28 pb-6 bg-brand-50 relative overflow-hidden">
    <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full border border-brand-200/60"></div>
    <div class="pointer-events-none absolute -right-20 -bottom-24 h-80 w-80 rounded-full border-4 border-brand-100/70"></div>
    <div class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-8 h-20 w-20 rounded-full pattern-dots opacity-30"></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 animate-fadeUp">Panel Resepsionis</h1>
      <p class="text-slate-600 mt-2 text-sm sm:text-base animate-fadeUp" style="animation-delay:.06s">
        Ringkasan aktivitas & tindakan cepat untuk melayani tamu.
        <span class="ml-2 text-xs text-slate-500">Tanggal: {{ $tanggal ?? now('Asia/Makassar')->toDateString() }}</span>
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
            <div class="grid gap-3 sm:gap-4 grid-cols-2 lg:grid-cols-4">
              <div class="rounded-2xl bg-white ring-1 ring-brand-100 p-4 sm:p-5 shadow-soft anim-on">
                <div class="text-xs text-slate-500">Total Tamu Hari Ini</div>
                <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-brand-700">
                  <span class="counter" data-target="{{ (int)($tamu_hari_ini ?? 0) }}">{{ (int)($tamu_hari_ini ?? 0) }}</span>
                </div>
              </div>
              <div class="rounded-2xl bg-white ring-1 ring-brand-100 p-4 sm:p-5 shadow-soft anim-on">
                <div class="text-xs text-slate-500">Menunggu Verifikasi</div>
                <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-brand-700">
                  <span class="counter" data-target="{{ (int)($menunggu ?? 0) }}">{{ (int)($menunggu ?? 0) }}</span>
                </div>
              </div>
              <div class="rounded-2xl bg-white ring-1 ring-brand-100 p-4 sm:p-5 shadow-soft anim-on">
                <div class="text-xs text-slate-500">Kunjungan Disetujui</div>
                <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-brand-700">
                  <span class="counter" data-target="{{ (int)($disetujui ?? 0) }}">{{ (int)($disetujui ?? 0) }}</span>
                </div>
              </div>
              <div class="rounded-2xl bg-white ring-1 ring-brand-100 p-4 sm:p-5 shadow-soft anim-on">
                <div class="text-xs text-slate-500">Kunjungan Ditolak</div>
                <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-brand-700">
                  <span class="counter" data-target="{{ (int)($ditolak ?? 0) }}">{{ (int)($ditolak ?? 0) }}</span>
                </div>
              </div>
            </div>

            <!-- Aksi cepat -->
            <div class="mt-4 sm:mt-6 rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm anim-on">
              <div class="flex items-center justify-between flex-wrap gap-3">
                <h3 class="font-semibold text-sm sm:text-base">Aksi Cepat</h3>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto">
                  <button id="btnTambah" class="ripple w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 px-4 py-2 text-white font-semibold shadow hover:shadow-lift hover:-translate-y-0.5 transition-transform text-sm sm:text-base">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 4a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V5a1 1 0 011-1z"/></svg>
                    Tambah Tamu Baru
                  </button>
                  <a href="{{ route('resepsionis.datatamu') }}"
                    class="ripple w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2 font-semibold hover:bg-slate-200 text-sm sm:text-base mt-2 sm:mt-0"
                    role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M4 3h12a1 1 0 011 1v2H3V4a1 1 0 011-1zm-1 5h14v8a1 1 0 01-1 1H4a1 1 0 01-1-1V8z"/>
                    </svg>
                    Lihat Data Tamu
                  </a>
                </div>
              </div>
            </div>

            <!-- Kunjungan Hari Ini (dinamis) -->
            <div class="mt-4 sm:mt-6">
              <h3 class="text-lg font-semibold">Kunjungan Hari Ini</h3>

              @php
                $badge = [
                  'menunggu' => 'bg-amber-50 ring-amber-200 text-amber-700',
                  'diterima' => 'bg-emerald-50 ring-emerald-200 text-emerald-700',
                  'ditolak'  => 'bg-rose-50 ring-rose-200 text-rose-700',
                  'selesai'  => 'bg-slate-50 ring-slate-200 text-slate-700',
                ];
                $label = [
                  'menunggu' => 'Menunggu',
                  'diterima' => 'Disetujui',
                  'ditolak'  => 'Ditolak',
                  'selesai'  => 'Selesai',
                ];
              @endphp

              <div class="mt-3 grid gap-3 sm:gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @forelse(($kunjungan_hari_ini ?? []) as $k)
                  <div class="relative rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm hover:shadow-lift transition-all dot-corner anim-on">
                    <div class="flex items-start justify-between">
                      <div class="flex-1 min-w-0">
                        <div class="text-sm text-slate-500">
                          {{ \Illuminate\Support\Carbon::parse($k->waktu_kunjungan)->timezone('Asia/Makassar')->format('H:i') }} WITA
                        </div>
                        <div class="mt-1 font-semibold text-slate-900 text-sm sm:text-base truncate">
                          {{ $k->tamu->nama ?? '—' }}
                        </div>
                        <div class="text-sm text-slate-600 truncate">
                          {{ $k->instansi_nama ?? $k->tamu->instansi_nama ?? '—' }} • {{ $k->keperluan }}
                        </div>
                      </div>

                      <span class="ml-3 inline-flex rounded-full px-2 py-0.5 text-xs ring-1 {{ $badge[$k->status_sekarang] ?? 'bg-slate-50 ring-slate-200 text-slate-700' }}">
                        {{ $label[$k->status_sekarang] ?? $k->status_sekarang }}
                      </span>
                    </div>

                    <div class="mt-3 text-sm text-slate-700">
                      Kontak:
                      @php $hp = $k->tamu->no_hp ?? null; @endphp
                      {{ $hp ? preg_replace('/(\d{3})(\d{3})(\d+)/', '$1•••$3', preg_replace('/\D/','',$hp)) : '—' }}
                    </div>

                    <div class="mt-4 flex items-center gap-2">
                      <button
                        type="button"
                        class="ripple rounded-xl bg-slate-100 px-3 py-1.5 text-xs hover:bg-slate-200 btnDetail"
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
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 text-center text-slate-600">
                      Tidak ada tamu berkunjung hari ini.
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
            <h2 class="text-xl sm:text-2xl font-bold anim-on">Data Tamu</h2>
            <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm anim-on">
              <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <form action="#" method="GET" class="grid gap-3 grid-cols-1 sm:grid-cols-4 w-full">
                  <div class="sm:col-span-2">
                    <label class="block text-sm font-medium">Kata kunci</label>
                    <input name="q" type="text" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="Nama/Instansi/Kode">
                  </div>
                  <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select name="status" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                      <option value="">Semua</option>
                      <option value="menunggu">Menunggu</option>
                      <option value="disetujui">Disetujui</option>
                      <option value="ditolak">Ditolak</option>
                      <option value="selesai">Selesai</option>
                    </select>
                  </div>
                  <div class="flex items-end">
                    <button type="submit" class="ripple w-full rounded-xl bg-brand-500 px-4 py-2 text-white font-semibold shadow hover:shadow-lift transition-transform text-sm">Cari</button>
                  </div>
                </form>
              </div>

              <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                  <thead>
                    <tr class="text-left text-slate-600">
                      <th class="py-2 pr-4">Tanggal</th>
                      <th class="py-2 pr-4">Nama</th>
                      <th class="py-2 pr-4">Instansi</th>
                      <th class="py-2 pr-4">Tujuan</th>
                      <th class="py-2 pr-4">Status</th>
                      <th class="py-2 pr-4">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="align-top">
                    <tr class="border-t">
                      <td class="py-3 pr-4">6 Nov 2025</td>
                      <td class="py-3 pr-4 font-medium">Rudi Hartono</td>
                      <td class="py-3 pr-4">SMA 1</td>
                      <td class="py-3 pr-4">Audiensi</td>
                      <td class="py-3 pr-4">
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs ring-1 bg-amber-50 ring-amber-200 text-amber-700">Menunggu</span>
                      </td>
                      <td class="py-3 pr-4">
                        <button class="ripple rounded-lg bg-brand-500/90 px-3 py-1.5 text-white shadow hover:shadow-lift text-xs">Proses</button>
                        <a href="#" class="ml-2 inline-block rounded-lg bg-slate-100 px-3 py-1.5 text-xs hover:bg-slate-200">Detail</a>
                      </td>
                    </tr>
                    <tr class="border-t">
                      <td class="py-3 pr-4">6 Nov 2025</td>
                      <td class="py-3 pr-4 font-medium">Dinas PU</td>
                      <td class="py-3 pr-4">Pemkot</td>
                      <td class="py-3 pr-4">Koordinasi</td>
                      <td class="py-3 pr-4">
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs ring-1 bg-emerald-50 ring-emerald-200 text-emerald-700">Disetujui</span>
                      </td>
                      <td class="py-3 pr-4">
                        <a href="#" class="inline-block rounded-lg bg-slate-100 px-3 py-1.5 text-xs hover:bg-slate-200">Detail</a>
                      </td>
                    </tr>
                    <!-- baris contoh -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- ====== VIEW 2: NOTIFIKASI ====== -->
        <section class="view">
          <div class="py-6">
            <h2 class="text-xl sm:text-2xl font-bold anim-on">Notifikasi</h2>
            <div class="mt-4 grid gap-4">
              <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm flex items-start gap-3 anim-on">
                <span class="mt-1 inline-flex h-3 w-3 rounded-full bg-red-500"></span>
                <div class="flex-1 min-w-0">
                  <div class="font-semibold text-sm sm:text-base">Verifikasi baru diperlukan</div>
                  <div class="text-sm text-slate-600">Pengajuan kunjungan dari SMA 1 memerlukan verifikasi.</div>
                </div>
                <div class="ml-auto text-xs text-slate-500 whitespace-nowrap">baru saja</div>
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm flex items-start gap-3 anim-on">
                <span class="mt-1 inline-flex h-3 w-3 rounded-full bg-emerald-500"></span>
                <div class="flex-1 min-w-0">
                  <div class="font-semibold text-sm sm:text-base">Kunjungan disetujui</div>
                  <div class="text-sm text-slate-600">Koordinasi Dinas PU telah disetujui.</div>
                </div>
                <div class="ml-auto text-xs text-slate-500 whitespace-nowrap">1 jam lalu</div>
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
      <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b bg-white/90 px-4 sm:px-6 py-4 sm:py-5 backdrop-blur">
        <div class="flex-1 min-w-0">
          <h3 class="text-lg sm:text-xl md:text-2xl font-extrabold truncate">Pengajuan Kunjungan Tamu</h3>
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
            <span class="text-sm font-medium whitespace-nowrap">Data Keperluan</span>
          </li>
          <li class="flex items-center gap-2 sm:gap-3 flex-shrink-0 opacity-80">
            <span id="step2State" class="grid h-7 w-7 sm:h-8 sm:w-8 place-items-center rounded-full border-2 border-slate-300 text-slate-500 font-semibold text-sm">2</span>
            <span class="text-sm font-medium whitespace-nowrap">Pihak Tujuan &amp; Dokumen</span>
          </li>
        </ol>
      </div>

      <form id="visitForm" action="{{ route('resepsionis.tambah.store') }}" method="POST" enctype="multipart/form-data" class="max-h-[70vh] sm:max-h-[75vh] overflow-y-auto px-4 sm:px-6 pb-4 sm:pb-6">
        @csrf

        <!-- STEP 1 -->
        <section id="step1" class="mt-4 sm:mt-6 space-y-4 sm:space-y-6">
          <div class="rounded-2xl border border-yellow-200 bg-brand-50 p-4 sm:p-5">
            <div class="mb-3 sm:mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <h4 class="text-base sm:text-lg font-semibold">Informasi Pemohon</h4>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
                <input required name="nama" type="text" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="Nama lengkap">
                @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium">Alamat Email <span class="text-red-500">*</span></label>
                <input required name="email" type="email" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="nama@email.com">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium">Nomor Handphone (WhatsApp) <span class="text-red-500">*</span></label>
                <div class="mt-1 flex rounded-xl border border-slate-300 bg-white shadow-sm focus-within:border-brand-500 focus-within:ring-1 focus-within:ring-brand-500">
                  <span class="inline-flex items-center rounded-l-xl bg-slate-50 px-3 text-slate-600 select-none text-sm">+62</span>
                  <input required name="no_hp" type="tel" class="w-full rounded-r-xl px-3 py-2 focus:outline-none text-sm" placeholder="81234567890">
                </div>
                <p class="mt-1 text-xs text-slate-500">Format: 812-3456-7890 (tanpa +62)</p>
                @error('no_hp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium">Jumlah Peserta <span class="text-red-500">*</span></label>
                <input required name="jumlah" type="number" min="1" max="50" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="cth: 10">
                <p class="mt-1 text-xs text-slate-500">Maksimal 50 orang per kunjungan</p>
                @error('jumlah') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <!-- Informasi Instansi/Organisasi -->
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5">
            <div class="mb-3 sm:mb-4 flex items-center gap-3">
              <div class="rounded-full bg-slate-500/10 p-2 text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
              </div>
              <h4 class="text-base sm:text-lg font-semibold">Informasi Instansi/Organisasi</h4>
            </div>

            <label class="block text-sm font-medium">Instansi/Daerah Asal <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-2 gap-2 sm:gap-3 sm:grid-cols-2 lg:grid-cols-4">
              <label class="group flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer text-xs sm:text-sm">
                <input type="radio" name="instansi_kategori" value="opd" class="peer" required><span>OPD</span>
              </label>
              <label class="group flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer text-xs sm:text-sm">
                <input type="radio" name="instansi_kategori" value="lembaga" class="peer"><span>Lembaga</span>
              </label>
              <label class="group flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer text-xs sm:text-sm">
                <input type="radio" name="instansi_kategori" value="perseorangan" class="peer"><span>Perseorangan</span>
              </label>
              <label class="group flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer text-xs sm:text-sm">
                <input type="radio" name="instansi_kategori" value="ormas" class="peer"><span>Ormas</span>
              </label>
            </div>
            @error('instansi_kategori') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror

            <div class="mt-4 grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium">Nama Instansi/Organisasi <span class="text-red-500">*</span></label>
                <input required name="instansi_nama" type="text" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="cth: Dinas Pendidikan">
                @error('instansi_nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium">Detail Keperluan <span class="text-red-500">*</span></label>
                <textarea required name="keperluan" rows="3" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="Tuliskan keperluan kunjungan..."></textarea>
                @error('keperluan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <!-- Jadwal -->
          <div class="rounded-2xl border border-yellow-200 bg-brand-50 p-4 sm:p-5">
            <div class="mb-3 sm:mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <h4 class="text-base sm:text-lg font-semibold">Jadwal Kunjungan</h4>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input required name="tanggal_kunjungan" type="date" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                @error('tanggal_kunjungan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium">Waktu Kunjungan <span class="text-red-500">*</span></label>
                <input required name="waktu_kunjungan" type="time" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                @error('waktu_kunjungan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3">
            <button type="button" id="toStep2" class="ripple rounded-xl bg-brand-500 px-4 sm:px-5 py-2 font-semibold text-white shadow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-transform focus:outline-none focus:ring-2 focus:ring-brand-300 text-sm sm:text-base">Lanjut</button>
          </div>
        </section>

        <!-- STEP 2 -->
        <section id="step2" class="mt-4 sm:mt-6 hidden space-y-4 sm:space-y-6">
          <div class="rounded-2xl border border-fuchsia-200 bg-fuchsia-50 p-4 sm:p-5">
            <div class="mb-3 sm:mb-4 flex items-center gap-3">
              <div class="rounded-full bg-fuchsia-500/20 p-2 text-fuchsia-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13 7H7v6h6V7z" /><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm10 12H5V5h10v10z" clip-rule="evenodd"/></svg>
              </div>
              <h4 class="text-base sm:text-lg font-semibold">Pihak yang Dituju</h4>
            </div>

            <label class="block text-sm font-medium">Kategori Pihak yang Dituju <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-2 sm:gap-3 sm:grid-cols-3">
              <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer text-xs sm:text-sm">
                <input type="radio" name="kategori_pihak_top" value="pimpinan" class="peer" required><span>Pimpinan</span>
              </label>
              <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer text-xs sm:text-sm">
                <input type="radio" name="kategori_pihak_top" value="akd" class="peer"><span>AKD</span>
              </label>
              <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer text-xs sm:text-sm">
                <input type="radio" name="kategori_pihak_top" value="sekretariat" class="peer"><span>Sekretariat</span>
              </label>
            </div>

            <!-- List dinamis -->
            <div id="pimpinanList" class="mt-4 hidden space-y-2 sm:space-y-3">
              <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Ketua DPRD" required><span>Ketua DPRD</span></label>
              <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Wakil Ketua 1"><span>Wakil Ketua 1</span></label>
              <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Wakil Ketua 2"><span>Wakil Ketua 2</span></label>
            </div>

            <div id="akdList" class="mt-4 hidden space-y-2 sm:space-y-3">
              <div class="grid grid-cols-1 gap-2 sm:gap-3 sm:grid-cols-2">
                <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Badan Kehormatan"><span>Badan Kehormatan</span></label>
                <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Badan Anggaran"><span>Badan Anggaran</span></label>
                <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Badan Pembentukan Peraturan Daerah"><span class="truncate">Badan Pembentukan Peraturan Daerah</span></label>
                <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Badan Musyawarah"><span>Badan Musyawarah</span></label>
                <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Komisi 1"><span>Komisi 1</span></label>
                <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Komisi 2"><span>Komisi 2</span></label>
                <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Komisi 3"><span>Komisi 3</span></label>
              </div>
            </div>

            <div id="sekretariatList" class="mt-4 hidden space-y-2 sm:space-y-3">
              <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Sekretaris"><span>Sekretaris</span></label>
              <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Bagian Umum dan Humas"><span>Bagian Umum dan Humas</span></label>
              <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Bagian Keuangan"><span>Bagian Keuangan</span></label>
              <label class="flex items-center gap-2 sm:gap-3 rounded-xl border bg-white px-3 sm:px-4 py-2 sm:py-3 shadow-sm ring-1 ring-slate-200 text-xs sm:text-sm"><input type="radio" name="kategori_pihak" value="Persidangan dan Perundang-undangan"><span class="truncate">Persidangan dan Perundang-undangan</span></label>
            </div>
          </div>

          <div class="rounded-2xl border border-yellow-200 bg-brand-50 p-4 sm:p-5">
            <div class="mb-3 sm:mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 7a4 4 0 118 0v4a2 2 0 11-4 0V7a2 2 0 10-4 0v6a4 4 0 108 0V9h2v4a6 6 0 11-12 0V7z"/></svg>
              </div>
              <h4 class="text-base sm:text-lg font-semibold">Upload Dokumen</h4>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:gap-5">
              <div>
                <label class="block text-sm font-medium">Surat Pemberitahuan/Surat Tugas (opsional)</label>
                <input type="file" name="dokumen" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-500 file:px-3 file:py-2 file:font-semibold file:text-white hover:file:bg-brand-600 text-sm">
                <p class="mt-1 text-xs text-slate-500">PDF/JPG/PNG maks 5MB.</p>
                @error('dokumen') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between flex-col sm:flex-row gap-3">
            <button type="button" id="backTo1" class="w-full sm:w-auto rounded-xl px-4 py-2 text-slate-700 hover:bg-slate-100 transition-colors text-sm sm:text-base order-2 sm:order-1">Kembali</button>
            <div class="flex items-center gap-3 w-full sm:w-auto order-1 sm:order-2">
              <button type="button" id="cancelBtn" class="w-1/2 sm:w-auto rounded-xl px-4 py-2 text-slate-600 hover:bg-slate-100 transition-colors text-sm sm:text-base">Batal</button>
              <button type="submit" class="ripple w-1/2 sm:w-auto rounded-xl bg-brand-500 px-4 sm:px-5 py-2 font-semibold text-white shadow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-transform focus:outline-none focus:ring-2 focus:ring-brand-300 text-sm sm:text-base">Kirim Pengajuan</button>
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
      <div class="flex items-center justify-between border-b bg-white/90 px-4 sm:px-5 py-3 sm:py-4 backdrop-blur">
        <h3 class="text-base sm:text-lg font-bold">Detail Tamu</h3>
        <button id="detailCloseBtn" class="rounded-full p-2 text-slate-500 hover:bg-slate-100" aria-label="Tutup">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
      </div>

      <div class="px-4 sm:px-5 py-3 sm:py-4">
        <dl class="space-y-3 text-sm">
          <div class="flex justify-between gap-4"><dt class="text-slate-500 text-sm">Nama</dt>            <dd id="nama" class="font-medium text-slate-900 text-sm text-right">—</dd></div>
          <div class="flex justify-between gap-4"><dt class="text-slate-500 text-sm">Email</dt>           <dd id="email" class="font-medium text-slate-900 text-sm break-all text-right">—</dd></div>
          <div class="flex justify-between gap-4"><dt class="text-slate-500 text-sm">Nomor HP</dt>        <dd id="no_hp" class="font-medium text-slate-900 text-sm break-all text-right">—</dd></div>
          <div class="flex justify-between gap-4"><dt class="text-slate-500 text-sm">Jumlah Peserta</dt>  <dd id="jumlah" class="font-medium text-slate-900 text-sm text-right">—</dd></div>
          <div class="flex justify-between gap-4"><dt class="text-slate-500 text-sm">Nama Instansi</dt>   <dd id="instansi_nama" class="font-medium text-slate-900 text-sm text-right">—</dd></div>
          <div class="flex justify-between gap-4"><dt class="text-slate-500 text-sm">Tanggal Kunjungan</dt><dd id="tanggal_kunjungan" class="font-medium text-slate-900 text-sm text-right">—</dd></div>
          <div class="flex justify-between gap-4"><dt class="text-slate-500 text-sm">Waktu Kunjungan</dt> <dd id="waktu_kunjungan" class="font-medium text-slate-900 text-sm text-right">—</dd></div>
          <div class="flex justify-between gap-4"><dt class="text-slate-500 text-sm">Bertemu Dengan</dt>  <dd id="subnama" class="font-medium text-slate-900 text-sm text-right">—</dd></div>
        </dl>
        <div class="mt-4 sm:mt-6 text-right">
          <button id="detailCloseBtn2" class="rounded-xl bg-slate-100 px-3 py-1.5 text-sm hover:bg-slate-200">Tutup</button>
        </div>
      </div>
    </div>
  </div>
@endsection