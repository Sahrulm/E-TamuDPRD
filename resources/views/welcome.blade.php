<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>E-Tamu DPRD – Kota Gorontalo</title>
  <link rel="icon" type="image/png" href="/img/logoDprd.png">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50:  '#FFF8E6',
              100: '#FFF1CC',
              200: '#FFE199',
              300: '#FFD166',
              400: '#FFC233',
              500: '#FFB300', /* kuning utama */
              600: '#E6A100',
              700: '#B37D00'
            },
            night: {
              900: '#0B1220',
              800: '#0F1930'
            }
          },
          boxShadow: {
            soft: '0 10px 30px -12px rgba(0,0,0,.12)',
            lift: '0 18px 40px -20px rgba(0,0,0,.25)',
            glow: '0 10px 30px -12px rgba(255,179,0,.45)'
          },
          keyframes: {
            fadeUp: { '0%': { opacity: 0, transform: 'translateY(12px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
            scaleIn: { '0%': { opacity: 0, transform: 'scale(.96)' }, '100%': { opacity: 1, transform: 'scale(1)' } },
            slideDown: { '0%': { opacity: 0, transform: 'translateY(-8px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
            pulseSoft: { '0%,100%': { transform: 'scale(1)' }, '50%': { transform: 'scale(1.03)' } },
            floaty: { '0%': { transform:'translateY(0)' }, '50%': { transform:'translateY(-6px)' }, '100%': { transform:'translateY(0)' } },
            marquee: { '0%': { transform:'translateX(0)' }, '100%': { transform:'translateX(-50%)' } }
          },
          animation: {
            fadeUp: 'fadeUp .5s ease-out both',
            scaleIn: 'scaleIn .28s ease-out both',
            slideDown: 'slideDown .25s ease-out both',
            pulseSoft: 'pulseSoft 1.8s ease-in-out infinite',
            floaty: 'floaty 6s ease-in-out infinite',
            marquee: 'marquee 24s linear infinite'
          }
        }
      }
    }
  </script>
  <style>
    /* Pola halus */
    .pattern-dots {
      background-image: radial-gradient(#FFE199 1px, transparent 1px);
      background-size: 14px 14px;
      background-position: 0 0;
    }
    /* Underline animasi untuk nav */
    .nav-link { position: relative; }
    .nav-link::after{
      content:''; position:absolute; left:0; bottom:-6px; height:2px; width:0%;
      background:#FFB300; transition: width .25s ease;
    }
    .nav-link:hover::after{ width:100%; }
    /* Garis halus */
    .hairline { height:1px; background: repeating-linear-gradient(90deg, rgba(0,0,0,.08) 0 8px, transparent 8px 16px); }

    /* Ripple tombol */
    .ripple { position: relative; overflow: hidden; }
    .ripple span.rp {
      position:absolute; border-radius:999px; transform:translate(-50%,-50%);
      pointer-events:none; opacity:.25; background:#000;
      animation:ripple .6s ease-out forwards;
    }
    @keyframes ripple{
      from{ width:0; height:0; opacity:.25 }
      to{ width:360px; height:360px; opacity:0 }
    }

    /* Kartu dekor lingkaran */
    .dot-corner::before{
      content:''; position:absolute; right:14px; top:14px; width:6px; height:6px; border-radius:999px; background:#FFE199;
      box-shadow: 0 0 0 6px rgba(255,227,153,.35), 0 0 0 14px rgba(255,227,153,.12);
    }

    /* Lock scroll saat modal terbuka */
    .modal-open { overflow: hidden; touch-action: none; }

    /* Kontainer konten umum (bukan navbar) */
    .container-x { margin-left:auto; margin-right:auto; max-width: 80rem; padding-left: clamp(1rem, 5vw, 3rem); padding-right: clamp(1rem, 5vw, 3rem); }

    /* NAVBAR TANPA MOTIF: blur lembut + spasi kiri/kanan dipersempit */
    .nav-wrap {
      background-color: rgba(255, 255, 255, .7);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }
    .nav-inner {
      padding-left: clamp(16px, 3vw, 42px);  /* dipersempit, tidak mepet */
      padding-right: clamp(16px, 3vw, 42px);
      max-width: none; /* full width bar */
      margin-left: 0;
      margin-right: 0;
    }
  </style>
</head>
<body class="antialiased text-slate-800 bg-white selection:bg-brand-200/60">

  <!-- NAV (tanpa motif, blur lembut, spasi kiri/kanan dipersempit) -->
  <header id="topbar" class="fixed inset-x-0 top-0 z-40 transition-all nav-wrap">
    <div class="nav-inner">
      <div class="flex items-center justify-between py-3">
        <!-- KIRI -->
        <div class="flex items-center gap-3">
          <img
            src="/img/logoDprd.png"
            alt="Logo DPRD"
            class="h-10 w-10 md:h-12 md:w-12 object-contain transition-transform duration-500"
          />
          <div class="leading-tight">
            <div class="font-extrabold">E-Tamu DPRD</div>
            <div class="text-xs text-slate-600">Kota Gorontalo</div>
          </div>
        </div>

        <!-- KANAN -->
        <nav class="hidden md:flex items-center gap-8 text-sm">
          <a href="#beranda" class="nav-link hover:text-brand-700 font-medium">Beranda</a>
          <a href="#layanan" class="nav-link hover:text-brand-700 font-medium">Layanan</a>
          <a href="#kontak" class="nav-link hover:text-brand-700 font-medium">Kontak</a>
        </nav>
      </div>
    </div>
    <div class="hairline"></div>
  </header>

  <!-- HERO (kuning full pada desktop) -->
  <section
    id="beranda"
    class="relative overflow-hidden pt-40 pb-24 bg-brand-50 flex items-center"
    style="min-height: calc(100vh - 64px);"
  >
    <!-- dekor kuning mengambang -->
    <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full border-4 border-brand-200/80 animate-floaty"></div>
    <div class="pointer-events-none absolute -right-20 -bottom-24 h-80 w-80 rounded-full border-4 border-brand-100/70 animate-floaty" style="animation-delay:1.2s"></div>
    <div class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-24 h-24 w-24 rounded-full pattern-dots opacity-30"></div>

    <!-- marquee aksen -->
    <div class="absolute inset-x-0 top-28 hidden md:block">
      <div class="overflow-hidden opacity-40">
        <div class="whitespace-nowrap animate-marquee">
          <span class="mx-8 text-brand-600 font-semibold">Pelayanan cepat</span>
          <span class="mx-8 text-brand-600 font-semibold">Transparan</span>
          <span class="mx-8 text-brand-600 font-semibold">Ramah</span>
          <span class="mx-8 text-brand-600 font-semibold">Profesional</span>
          <span class="mx-8 text-brand-600 font-semibold">Terpercaya</span>
        </div>
      </div>
    </div>

    <div class="container-x text-center">
      <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-sm font-medium ring-1 ring-brand-200 shadow-soft animate-scaleIn">
        <span class="inline-block h-2 w-2 rounded-full bg-brand-500 animate-pulseSoft"></span> Portal Kunjungan Resmi
      </span>

      <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900 animate-fadeUp mt-4">
        Selamat Datang di
        <span class="block mt-2 text-4xl sm:text-6xl text-brand-700">E-Tamu DPRD</span>
      </h1>
      <p class="mx-auto mt-6 max-w-3xl text-lg sm:text-xl text-slate-600 animate-fadeUp" style="animation-delay:.06s">
        Sistem digital untuk pengajuan kunjungan ke DPRD Kota Gorontalo. Mudah, cepat, dan terpercaya.
      </p>
      <div class="mt-10 animate-fadeUp" style="animation-delay:.12s">
        <button id="openModalBtnHero" class="ripple inline-flex items-center gap-2 rounded-2xl bg-brand-500 px-6 py-3 text-base font-semibold text-white shadow-glow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-transform focus:outline-none focus:ring-2 focus:ring-brand-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Ajukan Kunjungan Sekarang
        </button>
      </div>
    </div>
  </section>

  <!-- LAYANAN -->
  <section id="layanan" class="py-16">
    <div class="container-x">
      <h2 class="text-center text-3xl font-bold reveal text-brand-700">Layanan Kami</h2>
      <p class="mx-auto mt-3 max-w-2xl text-center text-slate-600 reveal">
        Kami hadir untuk memudahkan proses kunjungan Anda.
      </p>

      <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-lift transition-all hover:-translate-y-0.5 reveal dot-corner">
          <div class="flex items-start gap-3">
            <div class="rounded-xl bg-brand-100 p-2 text-brand-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 4a2 2 0 012-2h8a2 2 0 012 2v2H2V4z"/><path d="M2 9h12v7a2 2 0 01-2 2H4a2 2 0 01-2-2V9z"/><path d="M17 7h-3v2h3v3h2V9a2 2 0 00-2-2z"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Pengajuan Online</h3>
              <p class="mt-2 text-sm text-slate-600">
                Ajukan kunjungan secara online kapan saja dan dimana saja dengan mudah dan cepat.
              </p>
            </div>
          </div>
        </div>

        <div class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-lift transition-all hover:-translate-y-0.5 reveal dot-corner">
          <div class="flex items-start gap-3">
            <div class="rounded-xl bg-brand-100 p-2 text-brand-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/><path d="M18 9H2v5a2 2 0 002 2h5v-3H7a1 1 0 110-2h4a1 1 0 011 1v4h4a2 2 0 002-2V9z"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Tracking Real-time</h3>
              <p class="mt-2 text-sm text-slate-600">
                Pantau status pengajuan kunjungan Anda secara real-time melalui sistem kami.
              </p>
            </div>
          </div>
        </div>

        <div class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-lift transition-all hover:-translate-y-0.5 reveal dot-corner">
          <div class="flex items-start gap-3">
            <div class="rounded-xl bg-brand-100 p-2 text-brand-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/><path d="M8 16a2 2 0 104 0H8z"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Notifikasi Otomatis</h3>
              <p class="mt-2 text-sm text-slate-600">
                Dapatkan notifikasi otomatis melalui email dan WhatsApp untuk setiap update status.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Metrik -->
      <div class="mt-12 rounded-3xl bg-brand-50 p-8 ring-1 ring-brand-100 reveal">
        <div class="grid gap-8 text-center sm:grid-cols-2 lg:grid-cols-4">
          <div class="p-3 rounded-2xl bg-white/70 ring-1 ring-brand-100">
            <div class="text-3xl font-extrabold text-brand-700"><span class="counter" data-target="1250">0</span>+</div>
            <div class="mt-1 text-sm text-slate-600">Kunjungan Terlayani</div>
          </div>
          <div class="p-3 rounded-2xl bg-white/70 ring-1 ring-brand-100">
            <div class="text-3xl font-extrabold text-brand-700"><span class="counter" data-target="98">0</span>%</div>
            <div class="mt-1 text-sm text-slate-600">Tingkat Kepuasan</div>
          </div>
          <div class="p-3 rounded-2xl bg-white/70 ring-1 ring-brand-100">
            <div class="text-3xl font-extrabold text-brand-700">24/7</div>
            <div class="mt-1 text-sm text-slate-600">Layanan Online</div>
          </div>
          <div class="p-3 rounded-2xl bg-white/70 ring-1 ring-brand-100">
            <div class="text-3xl font-extrabold text-brand-700">&lt; 2 Jam</div>
            <div class="mt-1 text-sm text-slate-600">Waktu Respon</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- HUBUNGI KAMI -->
  <section id="kontak" class="bg-white py-16">
    <div class="container-x">
      <h2 class="text-center text-3xl font-bold reveal text-brand-700">Hubungi Kami</h2>
      <p class="mx-auto mt-3 max-w-2xl text-center text-slate-600 reveal">
        Tim kami siap membantu kebutuhan kunjungan Anda.
      </p>

      <div class="mt-10 grid gap-6 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-lift hover:-translate-y-0.5 transition-all reveal">
          <div class="text-sm font-semibold text-slate-500">Telepon</div>
          <div class="mt-1 text-lg font-bold text-slate-900">(0435) 821234</div>
          <a href="tel:+62435821234" class="mt-2 inline-block text-sm text-brand-700 hover:underline">Hubungi sekarang</a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-lift hover:-translate-y-0.5 transition-all reveal">
          <div class="text-sm font-semibold text-slate-500">Email</div>
          <div class="mt-1 text-lg font-bold text-slate-900">info@dprdgorontalo.go.id</div>
          <a href="mailto:info@dprdgorontalo.go.id" class="mt-2 inline-block text-sm text-brand-700 hover:underline">Kirim email</a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-lift hover:-translate-y-0.5 transition-all reveal">
          <div class="text-sm font-semibold text-slate-500">Alamat</div>
          <div class="mt-1 text-lg font-bold text-slate-900">Jl. Nani Wartabone No.1, Gorontalo</div>
          <a target="_blank" rel="noreferrer" href="https://maps.app.goo.gl/WCTqP2DNoK9yxGDs9" class="mt-2 inline-block text-sm text-brand-700 hover:underline">Lihat di Google Maps</a>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-night-900 text-slate-300 py-14">
    <div class="container-x grid gap-10 md:grid-cols-4">
      <div class="md:col-span-2">
        <div class="text-2xl font-extrabold text-white">E-Tamu DPRD<br><span class="text-brand-400">Kota Gorontalo</span></div>
        <p class="mt-3 max-w-md text-sm text-slate-400">
          Sistem digital untuk memudahkan pengajuan kunjungan ke DPRD Kota Gorontalo.
        </p>
      </div>

      <div>
        <div class="font-semibold text-white">Menu</div>
        <ul class="mt-3 space-y-2 text-sm">
          <li><a href="#beranda" class="hover:text-brand-300">Beranda</a></li>
          <li><a href="#layanan" class="hover:text-brand-300">Layanan</a></li>
          <li><a href="#kontak" class="hover:text-brand-300">Kontak</a></li>
        </ul>
      </div>

      <div>
        <div class="font-semibold text-white">Jam Operasional</div>
        <ul class="mt-3 space-y-2 text-sm text-slate-300">
          <li>Senin - Jumat: 08:00 - 16:00</li>
          <li>Sabtu: 08:00 - 12:00</li>
          <li>Minggu: Tutup</li>
        </ul>
      </div>
    </div>
    <div class="container-x mt-10 text-sm text-slate-500">
      © 2025 DPRD Kota Gorontalo. Semua hak dilindungi.
    </div>
  </footer>

  <!-- ========= MODAL MULTI-STEP (background blur saat dibuka) ========= -->
  <div
    id="applyModal"
    class="invisible opacity-0 fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 transition-opacity duration-200"
    aria-hidden="true"
  >
    <!-- backdrop dengan blur -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-md"></div>

    <!-- card -->
    <div
      id="modalCard"
      class="relative w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-black/5 transition-transform"
      role="dialog" aria-modal="true" aria-labelledby="modalTitle"
    >
      <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b bg-white/90 px-6 py-5 backdrop-blur">
        <div>
          <h3 id="modalTitle" class="text-2xl font-extrabold">Pengajuan Kunjungan Tamu</h3>
          <p class="mt-1 text-slate-600 text-sm">Silakan lengkapi formulir di bawah ini dengan data yang benar</p>
        </div>
        <button id="closeModalBtn" class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-300" aria-label="Tutup">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
      </div>

      <!-- Stepper -->
      <div class="px-6 pt-4">
        <ol class="flex items-center gap-6">
          <li class="flex items-center gap-3">
            <span id="step1State" class="grid h-8 w-8 place-items-center rounded-full border-2 border-brand-500 bg-brand-500 text-white font-semibold">1</span>
            <span class="text-sm font-medium">Data Keperluan</span>
          </li>
          <li class="flex items-center gap-3 opacity-80">
            <span id="step2State" class="grid h-8 w-8 place-items-center rounded-full border-2 border-slate-300 text-slate-500 font-semibold">2</span>
            <span class="text-sm font-medium">Pihak Tujuan &amp; Dokumen</span>
          </li>
        </ol>
      </div>

      <!-- Body -->
      <form id="visitForm" action="{{ route('tamu.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="max-h-[70vh] overflow-y-auto px-6 pb-6">
        @csrf

        <!-- STEP 1 -->
        <section id="step1" class="mt-6 space-y-6">
          <div class="rounded-2xl border border-yellow-200 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <h4 class="text-lg font-semibold">Informasi Pemohon</h4>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
                <input required name="nama" type="text" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="Nama lengkap">
                @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium">Alamat Email <span class="text-red-500">*</span></label>
                <input required name="email" type="email" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="nama@email.com">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium">Nomor Handphone (WhatsApp) <span class="text-red-500">*</span></label>
                <div class="mt-1 flex rounded-xl border border-slate-300 bg-white shadow-sm focus-within:border-brand-500 focus-within:ring-1 focus-within:ring-brand-500">
                  <span class="inline-flex items-center rounded-l-xl bg-slate-50 px-3 text-slate-600 select-none">+62</span>
                  <input required name="no_hp" type="tel" class="w-full rounded-r-xl px-3 py-2 focus:outline-none" placeholder="81234567890">
                </div>
                <p class="mt-1 text-xs text-slate-500">Format: 812-3456-7890 (tanpa +62)</p>
                @error('no_hp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium">Jumlah Peserta <span class="text-red-500">*</span></label>
                <input required name="jumlah" type="number" min="1" max="50" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="cth: 10">
                <p class="mt-1 text-xs text-slate-500">Maksimal 50 orang per kunjungan</p>
                @error('jumlah') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <!-- Informasi Instansi/Organisasi -->
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-slate-500/10 p-2 text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
              </div>
              <h4 class="text-lg font-semibold">Informasi Instansi/Organisasi</h4>
            </div>

            <label class="block text-sm font-medium">Instansi/Daerah Asal <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer">
                <input type="radio" name="instansi_kategori" value="opd" class="peer" required><span class="text-sm">OPD</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer">
                <input type="radio" name="instansi_kategori" value="lembaga" class="peer"><span class="text-sm">Lembaga</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer">
                <input type="radio" name="instansi_kategori" value="perseorangan" class="peer"><span class="text-sm">Perseorangan</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer">
                <input type="radio" name="instansi_kategori" value="ormas" class="peer"><span class="text-sm">Ormas</span>
              </label>
            </div>
            @error('instansi_kategori') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror

            <div class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium">Nama Instansi/Organisasi <span class="text-red-500">*</span></label>
                <input required name="instansi_nama" type="text" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="cth: Dinas Pendidikan">
                @error('instansi_nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium">Detail Keperluan <span class="text-red-500">*</span></label>
                <textarea required name="keperluan" rows="3" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="Tuliskan keperluan kunjungan..."></textarea>
                @error('keperluan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <!-- Jadwal -->
          <div class="rounded-2xl border border-yellow-200 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <h4 class="text-lg font-semibold">Jadwal Kunjungan</h4>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input required name="tanggal_kunjungan" type="date" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                @error('tanggal_kunjungan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium">Waktu Kunjungan <span class="text-red-500">*</span></label>
                <input required name="waktu_kunjungan" type="time" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                @error('waktu_kunjungan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3">
            <button type="button" id="toStep2" class="ripple rounded-xl bg-brand-500 px-5 py-2 font-semibold text-white shadow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-transform focus:outline-none focus:ring-2 focus:ring-brand-300">Lanjut</button>
          </div>
        </section>

        <!-- STEP 2 -->
        <section id="step2" class="mt-6 hidden space-y-6">
          <div class="rounded-2xl border border-fuchsia-200 bg-fuchsia-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-fuchsia-500/20 p-2 text-fuchsia-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13 7H7v6h6V7z" /><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm10 12H5V5h10v10z" clip-rule="evenodd"/></svg>
              </div>
              <h4 class="text-lg font-semibold">Pihak yang Dituju</h4>
            </div>

            <label class="block text-sm font-medium">Kategori Pihak yang Dituju <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer">
                <input type="radio" name="kategori_pihak_top" value="pimpinan" class="peer" required><span class="text-sm">Pimpinan</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer">
                <input type="radio" name="kategori_pihak_top" value="akd" class="peer"><span class="text-sm">AKD</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer">
                <input type="radio" name="kategori_pihak_top" value="sekretariat" class="peer"><span class="text-sm">Sekretariat</span>
              </label>
            </div>

            <!-- List dinamis -->
            <div id="pimpinanList" class="mt-4 hidden space-y-3">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Ketua DPRD" required><span>Ketua DPRD</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Wakil Ketua 1"><span>Wakil Ketua 1</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Wakil Ketua 2"><span>Wakil Ketua 2</span></label>
            </div>

            <div id="akdList" class="mt-4 hidden space-y-3">
              <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Badan Kehormatan"><span>Badan Kehormatan</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Badan Anggaran"><span>Badan Anggaran</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Badan Pembentukan Peraturan Daerah"><span>Badan Pembentukan Peraturan Daerah</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Badan Musyawarah"><span>Badan Musyawarah</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Komisi 1"><span>Komisi 1</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Komisi 2"><span>Komisi 2</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Komisi 3"><span>Komisi 3</span></label>
              </div>
            </div>

            <div id="sekretariatList" class="mt-4 hidden space-y-3">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Sekretaris"><span>Sekretaris</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Bagian Umum dan Humas"><span>Bagian Umum dan Humas</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Bagian Keuangan"><span>Bagian Keuangan</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Persidangan dan Perundang-undangan"><span>Persidangan dan Perundang-undangan</span></label>
            </div>
          </div>

          <div class="rounded-2xl border border-yellow-200 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 7a4 4 0 118 0v4a2 2 0 11-4 0V7a2 2 0 10-4 0v6a4 4 0 108 0V9h2v4a6 6 0 11-12 0V7z"/></svg>
              </div>
              <h4 class="text-lg font-semibold">Upload Dokumen</h4>
            </div>
            <div class="grid grid-cols-1 gap-5">
              <div>
                <label class="block text-sm font-medium">Surat Pemberitahuan/Surat Tugas (opsional)</label>
                <input type="file" name="dokumen" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-500 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-brand-600">
                <p class="mt-1 text-xs text-slate-500">PDF/JPG/PNG maks 5MB.</p>
                @error('dokumen') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <button type="button" id="backTo1" class="rounded-xl px-4 py-2 text-slate-700 hover:bg-slate-100 transition-colors">Kembali</button>
            <div class="flex items-center gap-3">
              <button type="button" id="cancelBtn" class="rounded-xl px-4 py-2 text-slate-600 hover:bg-slate-100 transition-colors">Batal</button>
              <button type="submit" class="ripple rounded-xl bg-brand-500 px-5 py-2 font-semibold text-white shadow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-transform focus:outline-none focus:ring-2 focus:ring-brand-300">Kirim Pengajuan</button>
            </div>
          </div>
        </section>
      </form>
    </div>
  </div>

  <!-- Toast -->
  <div id="toast" class="pointer-events-none fixed bottom-5 right-5 z-60 {{ session('success') ? '' : 'hidden' }} rounded-xl bg-emerald-600 px-4 py-3 text-white shadow-lg animate-slideDown">
    {{ session('success') ?? 'Pengajuan berhasil dikirim.' }}
  </div>

  <!-- ===== SCRIPTS ===== -->
  <script>
    // --- Modal (tengah, blur background & lock scroll) ---
    const modal   = document.getElementById('applyModal');
    const modalCard = document.getElementById('modalCard');
    const openers = [document.getElementById('openModalBtn'), document.getElementById('openModalBtnHero')].filter(Boolean);
    const closeBtn= document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const form    = document.getElementById('visitForm');
    const toast   = document.getElementById('toast');

    const step1   = document.getElementById('step1');
    const step2   = document.getElementById('step2');
    const toStep2 = document.getElementById('toStep2');
    const backTo1 = document.getElementById('backTo1');
    const step1State = document.getElementById('step1State');
    const step2State = document.getElementById('step2State');

    function openModal() {
      modal.classList.remove('invisible','opacity-0');
      modal.setAttribute('aria-hidden','false');
      document.body.classList.add('modal-open'); // kunci scroll BG
      step1.classList.remove('hidden'); step2.classList.add('hidden');
      step1State.classList.add('bg-brand-500','border-brand-500','text-white');
      step2State.classList.remove('bg-brand-500','text-white'); step2State.classList.add('border-slate-300','text-slate-500');
      // animasi kartu
      modalCard.style.transform = 'translateY(18px) scale(.98)';
      modalCard.style.opacity = '0';
      requestAnimationFrame(()=> {
        modalCard.style.transition = 'transform .22s ease-out, opacity .22s ease-out';
        modalCard.style.transform = 'translateY(0) scale(1)';
        modalCard.style.opacity = '1';
      });
      setTimeout(()=>modalCard.focus?.(), 50);
    }
    function closeModal() {
      modalCard.style.transform = 'translateY(12px) scale(.98)';
      modalCard.style.opacity = '0';
      setTimeout(()=>{
        modal.classList.add('opacity-0');
        modal.setAttribute('aria-hidden','true');
        document.body.classList.remove('modal-open'); // lepas kunci scroll
        setTimeout(()=>modal.classList.add('invisible'),160);
      },160);
    }
    openers.forEach(b=>b.addEventListener('click', openModal));
    closeBtn && closeBtn.addEventListener('click', closeModal);
    cancelBtn && cancelBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.getAttribute('aria-hidden') !== 'true') closeModal(); });

    // Step navigation + micro-animasi
    toStep2 && toStep2.addEventListener('click', () => {
      step1.classList.add('hidden'); step2.classList.remove('hidden');
      step1State.classList.remove('bg-brand-500','text-white'); step1State.classList.add('border-brand-500','text-brand-700');
      step2State.classList.remove('border-slate-300','text-slate-500'); step2State.classList.add('bg-brand-500','border-brand-500','text-white');
      step2.animate([{opacity:0, transform:'translateY(8px)'},{opacity:1, transform:'translateY(0)'}], {duration:200, easing:'ease-out'});
    });
    backTo1 && backTo1.addEventListener('click', () => {
      step2.classList.add('hidden'); step1.classList.remove('hidden');
      step2State.classList.remove('bg-brand-500','text-white'); step2State.classList.add('border-slate-300','text-slate-500');
      step1State.classList.add('bg-brand-500','border-brand-500','text-white');
      step1.animate([{opacity:0, transform:'translateY(8px)'},{opacity:1, transform:'translateY(0)'}], {duration:200, easing:'ease-out'});
    });

    // Kategori top -> sub list
    const topRadios = document.querySelectorAll('input[name="kategori_pihak_top"]');
    const lists = {
      pimpinan: document.getElementById('pimpinanList'),
      akd: document.getElementById('akdList'),
      sekretariat: document.getElementById('sekretariatList'),
    };

    function setGroupState(container, { hidden }) {
      container.classList.toggle('hidden', hidden);
      const radios = container.querySelectorAll('input[type="radio"][name="kategori_pihak"]');
      radios.forEach(r => { r.disabled = hidden; r.required = !hidden; });
      if (!hidden) {
        const anyChecked = [...radios].some(r => r.checked);
        if (!anyChecked && radios[0]) radios[0].checked = true;
        container.animate([{opacity:0, transform:'translateY(6px)'},{opacity:1, transform:'translateY(0)'}], {duration:180, easing:'ease-out'});
      }
    }
    Object.values(lists).forEach(el => setGroupState(el, { hidden: true }));
    topRadios.forEach(r => r.addEventListener('change', (e) => {
      Object.entries(lists).forEach(([key, el]) => setGroupState(el, { hidden: key !== e.target.value }));
    }));

    // Toast auto-hide
    @if(session('success'))
      (function(){ setTimeout(()=>toast.classList.add('hidden'), 3000); })();
    @endif

    // Counter metrik
    const counters = document.querySelectorAll('.counter');
    const runCounter = (el) => {
      const target = +el.dataset.target;
      const isLarge = target > 200;
      const duration = 1200;
      const start = performance.now();
      const step = (now) => {
        const p = Math.min((now - start) / duration, 1);
        const value = Math.floor(p * target);
        el.textContent = isLarge ? value.toLocaleString('id-ID') : value;
        if (p < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    };
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { runCounter(e.target); io.unobserve(e.target); } });
    }, { threshold: 0.6 });
    counters.forEach(c => io.observe(c));

    // Reveal on scroll
    const reveals = document.querySelectorAll('.reveal');
    const io2 = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('animate-fadeUp'); io2.unobserve(e.target); } });
    }, { threshold: .25 });
    reveals.forEach(el => io2.observe(el));

    // Header shadow saat scroll
    const topbar = document.getElementById('topbar');
    const setShadow = () => {
      if (window.scrollY > 6) topbar.classList.add('shadow');
      else topbar.classList.remove('shadow');
    };
    setShadow();
    window.addEventListener('scroll', setShadow, { passive: true });

    // Ripple tombol
    document.addEventListener('click', function(e){
      const target = e.target.closest('.ripple');
      if(!target) return;
      const rect = target.getBoundingClientRect();
      const span = document.createElement('span');
      span.className = 'rp';
      span.style.left = (e.clientX - rect.left) + 'px';
      span.style.top  = (e.clientY - rect.top) + 'px';
      target.appendChild(span);
      setTimeout(()=> span.remove(), 600);
    });

    // Smooth scroll nav
    (function () {
      const HEADER = document.getElementById('topbar');
      const LINKS = document.querySelectorAll('a[href^="#beranda"], a[href^="#layanan"], a[href^="#kontak"]');

      function easeInOutCubic(t) { return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; }
      function smoothScrollTo(targetY, duration = 550) {
        const startY = window.pageYOffset;
        const diff   = targetY - startY;
        const start  = performance.now();
        function step(now) {
          const p = Math.min((now - start) / duration, 1);
          const eased = easeInOutCubic(p);
          window.scrollTo(0, startY + diff * eased);
          if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
      }
      function getOffsetTop(el) {
        const rect = el.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const headerH = (HEADER?.offsetHeight || 0);
        return rect.top + scrollTop - headerH - 8;
      }

      LINKS.forEach(a => {
        a.addEventListener('click', (e) => {
          const id = a.getAttribute('href');
          const target = document.querySelector(id);
          if (!target) return;
          e.preventDefault();
          const y = getOffsetTop(target);
          smoothScrollTo(y, 550);
          history.pushState(null, '', id);
        });
      });
    })();

    // --- Set hero min-height agar kuning full layar pada desktop ---
    (function setHeroMinHeight() {
      const hero = document.getElementById('beranda');
      const topbar = document.getElementById('topbar');
      function applyHeroMinH() {
        const headerH = (topbar?.offsetHeight || 64);
        hero.style.minHeight = `calc(100vh - ${headerH}px)`;
      }
      let t;
      window.addEventListener('resize', () => { clearTimeout(t); t = setTimeout(applyHeroMinH, 80); });
      applyHeroMinH();
    })();
  </script>
</body>
</html>
