<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>E-Tamu DPRD – Kota Gorontalo</title>
  <link rel="icon" type="image/png" href="/img/logoDprd.png">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50:  '#FFFDF0',
              100: '#FFF9D9',
              200: '#FFF3B2',
              300: '#FFEC8A',
              400: '#FFE563',
              500: '#FFDD3C', /* kuning yang lebih cerah */
              600: '#E6C736',
              700: '#B3992A'
            },
            night: {
              900: '#0B1220',
              800: '#0F1930'
            }
          },
          boxShadow: {
            soft: '0 4px 12px -2px rgba(0,0,0,.08)',
            lift: '0 12px 24px -8px rgba(255, 221, 60, 0.3)',
            glow: '0 8px 20px -6px rgba(255, 221, 60, 0.5)'
          },
          keyframes: {
            fadeUp: { '0%': { opacity: 0, transform: 'translateY(12px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
            scaleIn: { '0%': { opacity: 0, transform: 'scale(.96)' }, '100%': { opacity: 1, transform: 'scale(1)' } },
            slideDown: { '0%': { opacity: 0, transform: 'translateY(-8px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
            pulseSoft: { '0%,100%': { transform: 'scale(1)' }, '50%': { transform: 'scale(1.03)' } },
            floaty: { '0%': { transform:'translateY(0)' }, '50%': { transform:'translateY(-6px)' }, '100%': { transform:'translateY(0)' } },
            marquee: { '0%': { transform:'translateX(0)' }, '100%': { transform:'translateX(-50%)' } },
            flip: { '0%': { transform:'rotateY(0)' }, '100%': { transform:'rotateY(180deg)' } },
            flipBack: { '0%': { transform:'rotateY(180deg)' }, '100%': { transform:'rotateY(0)' } },
            glow: { '0%,100%': { boxShadow: '0 0 5px rgba(255,221,60,0.5)' }, '50%': { boxShadow: '0 0 20px rgba(255,221,60,0.8)' } },
            textShine: { '0%': { backgroundPosition: '0% 50%' }, '100%': { backgroundPosition: '100% 50%' } },
            slideInLeft: { '0%': { opacity: 0, transform: 'translateX(-20px)' }, '100%': { opacity: 1, transform: 'translateX(0)' } },
            slideInRight: { '0%': { opacity: 0, transform: 'translateX(20px)' }, '100%': { opacity: 1, transform: 'translateX(0)' } },
            cardSlideUp: { '0%': { opacity: 0, transform: 'translateY(30px) scale(0.9)' }, '100%': { opacity: 1, transform: 'translateY(0) scale(1)' } },
            fadeInDown: { '0%': { opacity: 0, transform: 'translateY(-20px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
            bounceIn: { '0%': { opacity: 0, transform: 'scale(0.3)' }, '50%': { opacity: 1, transform: 'scale(1.05)' }, '70%': { transform: 'scale(0.9)' }, '100%': { opacity: 1, transform: 'scale(1)' } }
          },
          animation: {
            fadeUp: 'fadeUp .5s ease-out both',
            scaleIn: 'scaleIn .28s ease-out both',
            slideDown: 'slideDown .25s ease-out both',
            pulseSoft: 'pulseSoft 1.8s ease-in-out infinite',
            floaty: 'floaty 6s ease-in-out infinite',
            marquee: 'marquee 24s linear infinite',
            flip: 'flip 0.6s ease-out forwards',
            flipBack: 'flipBack 0.6s ease-out forwards',
            glow: 'glow 2s ease-in-out infinite',
            textShine: 'textShine 3s ease-in-out infinite alternate',
            slideInLeft: 'slideInLeft 0.3s ease-out',
            slideInRight: 'slideInRight 0.3s ease-out',
            cardSlideUp: 'cardSlideUp 0.6s ease-out both',
            fadeInDown: 'fadeInDown 0.5s ease-out both',
            bounceIn: 'bounceIn 0.6s ease-out'
          }
        }
      }
    }
  </script>
  <style>
    /* Pola halus yang lebih ringan */
    .pattern-dots {
      background-image: radial-gradient(#FFEC8A 1px, transparent 1px);
      background-size: 16px 16px;
      background-position: 0 0;
    }
    
    /* Navbar dengan background kuning */
    .nav-wrap {
      background-color: #FFDD3C;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    /* Underline animasi untuk nav */
    .nav-link { 
      position: relative; 
      color: rgba(0,0,0,0.7);
      transition: color 0.2s;
    }
    .nav-link:hover { 
      color: rgba(0,0,0,0.9);
    }
    .nav-link::after{
      content:''; position:absolute; left:0; bottom:-6px; height:2px; width:0%;
      background:rgba(0,0,0,0.7); transition: width .25s ease;
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

    /* Kartu dengan aksen kuning */
    .card-yellow {
      border-left: 4px solid #FFDD3C;
      transition: all 0.3s ease;
    }
    
    .card-yellow:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px -8px rgba(255, 221, 60, 0.3);
    }

    /* Lock scroll saat modal terbuka */
    .modal-open { overflow: hidden; touch-action: none; }

    /* Kontainer konten umum */
    .container-x { margin-left:auto; margin-right:auto; max-width: 80rem; padding-left: clamp(1rem, 5vw, 3rem); padding-right: clamp(1rem, 5vw, 3rem); }

    /* Efek gradasi kuning untuk hero */
    .hero-gradient {
      background: linear-gradient(135deg, #FFDD3C 0%, #FFE563 30%, #FFF9D9 100%);
    }
    
    /* Animasi untuk elemen reveal */
    .reveal {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s ease;
    }
    
    .reveal.active {
      opacity: 1;
      transform: translateY(0);
    }
    
    /* Efek hover untuk metrik */
    .metric-card {
      transition: all 0.3s ease;
    }
    
    .metric-card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 20px -6px rgba(255, 221, 60, 0.3);
    }
    
    /* Perpanjang background kuning ke bawah */
    .extended-yellow {
      padding-bottom: 15rem; /* Tambahkan padding bawah lebih banyak */
    }
    
    /* ========= STYLING BARU UNTUK KARTU KONTRIBUTOR ========= */
    .contributor-modal-bg {
      background: linear-gradient(135deg, #FFDD3C 0%, #FFE563 30%, #FFF9D9 100%);
    }
    
    .contributor-cards-container {
      display: flex;
      justify-content: center;
      gap: 30px;
      flex-wrap: wrap;
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.8s ease;
    }
    
    .contributor-cards-container.show {
      opacity: 1;
      transform: translateY(0);
    }
    
    .contributor-card {
      background: linear-gradient(135deg, #FFDD3C 0%, #FFE563 50%, #FFF9D9 100%);
      border-radius: 20px;
      padding: 30px;
      width: 300px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      transition: all 0.4s ease;
      transform: translateY(50px) scale(0.9);
      opacity: 0;
      position: relative;
      overflow: hidden;
    }
    
    .contributor-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1, #FFA500);
      background-size: 400% 100%;
      animation: gradientShift 3s ease infinite;
    }
    
    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    
    .contributor-card.show {
      transform: translateY(0) scale(1);
      opacity: 1;
    }
    
    .contributor-card:nth-child(1) {
      transition-delay: 0.2s;
    }
    
    .contributor-card:nth-child(2) {
      transition-delay: 0.4s;
    }
    
    .contributor-card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .contributor-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      margin: 0 auto 20px;
      overflow: hidden;
      border: 4px solid rgba(255, 255, 255, 0.8);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 2.5rem;
      color: white;
    }
    
    .contributor-avatar i {
      opacity: 0.9;
    }
    
    .contributor-name {
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 10px;
      color: #0B1220;
      text-align: center;
      background: linear-gradient(90deg, #0B1220, #1a365d, #0B1220);
      background-size: 200% auto;
      color: transparent;
      -webkit-background-clip: text;
      background-clip: text;
      animation: textShine 5s linear infinite;
    }
    
    .contributor-position {
      font-size: 1.1rem;
      color: rgba(11, 18, 32, 0.8);
      margin-bottom: 5px;
      text-align: center;
      font-weight: 600;
    }
    
    .contributor-university {
      font-size: 1rem;
      color: rgba(11, 18, 32, 0.7);
      margin-bottom: 20px;
      text-align: center;
    }
    
    .contributor-social-icons {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 20px;
    }
    
    .contributor-social-icons a {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.8);
      color: #0B1220;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .contributor-social-icons a:hover {
      background: rgba(255, 255, 255, 1);
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    
    /* Tombol untuk modal kontributor */
    .contributor-btn {
      background: linear-gradient(to right, #FFDD3C, #FFE563);
      border: none;
      padding: 12px 30px;
      font-size: 1rem;
      font-weight: bold;
      color: #0B1220;
      border-radius: 50px;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    
    .contributor-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      background: linear-gradient(to right, #FFE563, #FFDD3C);
    }
    
    /* ========= PERBAIKAN UNTUK RESPONSIVITAS ========= */
    
    /* Menu mobile */
    .mobile-menu {
      position: fixed;
      top: 0;
      right: -100%;
      width: 80%;
      max-width: 300px;
      height: 100vh;
      background: white;
      z-index: 50;
      transition: right 0.3s ease;
      box-shadow: -5px 0 15px rgba(0,0,0,0.1);
      padding: 80px 20px 20px;
      overflow-y: auto;
    }
    
    .mobile-menu.open {
      right: 0;
    }
    
    .mobile-menu-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 45;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }
    
    .mobile-menu-overlay.active {
      opacity: 1;
      visibility: visible;
    }
    
    /* Perbaikan layout untuk mobile */
    @media (max-width: 768px) {
      .container-x {
        padding-left: 1rem;
        padding-right: 1rem;
      }
      
      .hero-gradient {
        padding-top: 100px;
        padding-bottom: 60px;
      }
      
      .extended-yellow {
        padding-bottom: 15rem;
      }
      
      .contributor-card {
        width: 100%;
        max-width: 280px;
        padding: 25px;
      }
      
      .contributor-cards-container {
        gap: 20px;
      }
      
      .contributor-avatar {
        width: 100px;
        height: 100px;
        font-size: 2rem;
      }
      
      .contributor-name {
        font-size: 1.4rem;
      }
      
      .contributor-position {
        font-size: 1rem;
      }
      
      .contributor-university {
        font-size: 0.9rem;
      }
      
      /* Perbaikan modal untuk mobile */
      #applyModal, #contributorModal, #confirmationModal {
        padding: 1rem;
        align-items: flex-start;
      }
      
      #modalCard, #contributorModalCard, #confirmationModalCard {
        max-height: 90vh;
        overflow-y: auto;
      }
      
      /* Perbaikan form untuk mobile */
      .grid-cols-1, .grid-cols-2, .sm\\:grid-cols-2, .lg\\:grid-cols-4 {
        grid-template-columns: 1fr !important;
      }
      
      /* Perbaikan spacing untuk mobile */
      .py-16 {
        padding-top: 3rem;
        padding-bottom: 3rem;
      }
      
      .pt-40 {
        padding-top: 100px;
      }
      
      /* Perbaikan tombol untuk mobile */
      button, .ripple {
        min-height: 44px; /* Ukuran touch-friendly */
      }
      
      /* Perbaikan font untuk mobile */
      h1 {
        font-size: 2rem !important;
      }
      
      h2 {
        font-size: 1.75rem !important;
      }
      
      .text-3xl {
        font-size: 1.5rem !important;
      }
      
      .text-5xl {
        font-size: 2.5rem !important;
      }
    }
    
    @media (max-width: 480px) {
      .contributor-card {
        padding: 20px;
      }
      
      .container-x {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
      }
    }
    
    /* Perbaikan untuk tablet */
    @media (min-width: 769px) and (max-width: 1024px) {
      .container-x {
        padding-left: 2rem;
        padding-right: 2rem;
      }
    }
    
    /* Perbaikan untuk aksesibilitas */
    .focus\:outline-none:focus {
      outline: 2px solid #FFDD3C;
      outline-offset: 2px;
    }
    
    /* Perbaikan untuk loading state */
    .loading {
      pointer-events: none;
      opacity: 0.7;
    }
    
    /* Perbaikan untuk scrollbar */
    ::-webkit-scrollbar {
      width: 6px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #FFDD3C;
      border-radius: 3px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #E6C736;
    }
    
    /* Perbaikan untuk animasi yang lebih smooth */
    * {
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    
    /* Perbaikan untuk kontras warna */
    .text-slate-700 {
      color: #334155;
    }
    
    .text-slate-600 {
      color: #475569;
    }
    
    /* Perbaikan untuk spacing yang lebih konsisten */
    .section-spacing {
      padding-top: 4rem;
      padding-bottom: 4rem;
    }
    
    /* Perbaikan untuk gambar responsif */
    img {
      max-width: 100%;
      height: auto;
    }

    /* Loading spinner untuk tombol */
    #submitSpinner {
      border-top-color: transparent;
    }

    /* State loading untuk tombol */
    .btn-loading {
      position: relative;
      color: transparent !important;
      pointer-events: none;
    }

    .btn-loading .spinner-text {
      visibility: hidden;
    }

    /* Progress bar untuk loading */
    #progressBar {
      height: 4px;
      background: linear-gradient(90deg, #FFDD3C, #FFE563, #FFDD3C);
      background-size: 200% 100%;
      animation: progress 2s ease-in-out infinite;
      width: 100%;
      position: absolute;
      top: 0;
      left: 0;
      border-radius: 2px;
      display: none;
    }

    #progressBar.loading {
      display: block;
    }
  </style>
</head>
<body class="antialiased text-slate-800 bg-white selection:bg-brand-200/60">

  <!-- NAV (dengan background kuning) -->
  <header id="topbar" class="fixed inset-x-0 top-0 z-40 transition-all nav-wrap">
    <div class="container-x">
      <div class="flex items-center justify-between py-3">
        <!-- KIRI -->
        <div class="flex items-center gap-3">
          <img
            src="/img/logoDprd.png"
            alt="Logo DPRD"
            class="h-10 w-10 md:h-12 md:w-12 object-contain transition-transform duration-500"
          />
          <div class="leading-tight">
            <div class="font-extrabold text-slate-900">E-Tamu DPRD</div>
            <div class="text-xs text-slate-700">Kota Gorontalo</div>
          </div>
        </div>

        <!-- KANAN - Desktop -->
        <nav class="hidden md:flex items-center gap-8 text-sm">
          <a href="#beranda" class="nav-link font-medium">Beranda</a>
          <a href="#layanan" class="nav-link font-medium">Layanan</a>
          <a href="#kontak" class="nav-link font-medium">Kontak</a>
          <a href="#kontributor" class="nav-link font-medium" id="openContributorBtn">Kontributor</a>
        </nav>

        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="md:hidden rounded-lg p-2 text-slate-900 hover:bg-white/20 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300" aria-label="Buka menu">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </header>

  <!-- Mobile Menu -->
  <div id="mobileMenuOverlay" class="mobile-menu-overlay"></div>
  <div id="mobileMenu" class="mobile-menu">
    <button id="closeMobileMenuBtn" class="absolute top-4 right-4 rounded-full p-2 text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Tutup menu">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
    </button>
    
    <nav class="flex flex-col space-y-6">
      <a href="#beranda" class="nav-link font-medium text-lg py-2 border-b border-slate-100">Beranda</a>
      <a href="#layanan" class="nav-link font-medium text-lg py-2 border-b border-slate-100">Layanan</a>
      <a href="#kontak" class="nav-link font-medium text-lg py-2 border-b border-slate-100">Kontak</a>
      <a href="#kontributor" class="nav-link font-medium text-lg py-2 border-b border-slate-100" id="openContributorBtnMobile">Kontributor</a>
      <button id="openModalBtnMobile" class="ripple mt-4 w-full rounded-xl bg-yellow-500 px-5 py-3 font-semibold text-white shadow hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-slate-300">
        Ajukan Kunjungan
      </button>
    </nav>
  </div>

  <!-- HERO (dengan gradasi kuning dan diperpanjang ke bawah) -->
  <section
    id="beranda"
    class="relative overflow-hidden pt-32 md:pt-40 hero-gradient flex items-center extended-yellow"
    style="min-height: calc(100vh - 64px);"
  >
    <!-- dekor kuning mengambang -->
    <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full border-4 border-white/30 animate-floaty"></div>
    <div class="pointer-events-none absolute -right-20 -bottom-24 h-80 w-80 rounded-full border-4 border-white/20 animate-floaty" style="animation-delay:1.2s"></div>
    <div class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-24 h-24 w-24 rounded-full pattern-dots opacity-40"></div>

    <!-- marquee aksen -->
    <div class="absolute inset-x-0 top-28 hidden md:block">
      <div class="overflow-hidden opacity-60">
        <div class="whitespace-nowrap animate-marquee">
          <span class="mx-8 text-slate-800 font-semibold">Pelayanan cepat</span>
          <span class="mx-8 text-slate-800 font-semibold">Transparan</span>
          <span class="mx-8 text-slate-800 font-semibold">Ramah</span>
          <span class="mx-8 text-slate-800 font-semibold">Profesional</span>
          <span class="mx-8 text-slate-800 font-semibold">Terpercaya</span>
        </div>
      </div>
    </div>

    <div class="container-x text-center">
      <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-1.5 text-sm font-medium ring-1 ring-white/50 shadow-soft animate-scaleIn">
        <span class="inline-block h-2 w-2 rounded-full bg-slate-800 animate-pulseSoft"></span> Portal Kunjungan Resmi
      </span>

      <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900 animate-fadeUp mt-4">
        Selamat Datang di
        <span class="block mt-2 text-4xl sm:text-6xl text-slate-900">E-Tamu DPRD</span>
      </h1>
      <p class="mx-auto mt-6 max-w-3xl text-lg sm:text-xl text-slate-700 animate-fadeUp" style="animation-delay:.06s">
        Sistem digital untuk pengajuan kunjungan ke DPRD Kota Gorontalo. Mudah, cepat, dan terpercaya.
      </p>
      <div class="mt-10 animate-fadeUp" style="animation-delay:.12s">
        <button id="openModalBtnHero" class="ripple inline-flex items-center gap-2 rounded-2xl bg-yellow-500 px-6 py-3 text-base font-semibold text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition-all focus:outline-none focus:ring-2 focus:ring-yellow-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Ajukan Kunjungan Sekarang
        </button>
      </div>
    </div>
  </section>

  <!-- LAYANAN -->
  <section id="layanan" class="py-16 bg-slate-50 section-spacing">
    <div class="container-x">
      <h2 class="text-center text-3xl font-bold text-slate-900 reveal">Layanan Kami</h2>
      <p class="mx-auto mt-3 max-w-2xl text-center text-slate-600 reveal">
        Kami hadir untuk memudahkan proses kunjungan Anda.
      </p>

      <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="card-yellow rounded-xl bg-white p-6 shadow-sm reveal">
          <div class="flex items-start gap-3">
            <div class="rounded-xl bg-brand-100 p-2 text-slate-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 4a2 2 0 012-2h8a2 2 0 012 2v2H2V4z"/><path d="M2 9h12v7a2 2 0 01-2 2H4a2 2 0 01-2-2V9z"/><path d="M17 7h-3v2h3v3h2V9a2 2 0 00-2-2z"/></svg>
            </div>
            <div>
              <h3 class="font-semibold text-slate-900">Pengajuan Online</h3>
              <p class="mt-2 text-sm text-slate-600">
                Ajukan kunjungan secara online kapan saja dan dimana saja dengan mudah dan cepat.
              </p>
            </div>
          </div>
        </div>

        <div class="card-yellow rounded-xl bg-white p-6 shadow-sm reveal">
          <div class="flex items-start gap-3">
            <div class="rounded-xl bg-brand-100 p-2 text-slate-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/><path d="M18 9H2v5a2 2 0 002 2h5v-3H7a1 1 0 110-2h4a1 1 0 011 1v4h4a2 2 0 002-2V9z"/></svg>
            </div>
            <div>
              <h3 class="font-semibold text-slate-900">Tracking Real-time</h3>
              <p class="mt-2 text-sm text-slate-600">
                Pantau status pengajuan kunjungan Anda secara real-time melalui sistem kami.
              </p>
            </div>
          </div>
        </div>

        <div class="card-yellow rounded-xl bg-white p-6 shadow-sm reveal">
          <div class="flex items-start gap-3">
            <div class="rounded-xl bg-brand-100 p-2 text-slate-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/><path d="M8 16a2 2 0 104 0H8z"/></svg>
            </div>
            <div>
              <h3 class="font-semibold text-slate-900">Notifikasi Otomatis</h3>
              <p class="mt-2 text-sm text-slate-600">
                Dapatkan notifikasi otomatis melalui email dan WhatsApp untuk setiap update status.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Metrik -->
      <div class="mt-12 rounded-2xl bg-white p-8 shadow-sm reveal">
        <div class="grid gap-8 text-center sm:grid-cols-2 lg:grid-cols-4">
          <div class="metric-card p-3 rounded-xl bg-brand-50">
            <div class="text-3xl font-extrabold text-slate-900"><span class="counter" data-target="1250">0</span>+</div>
            <div class="mt-1 text-sm text-slate-600">Kunjungan Terlayani</div>
          </div>
          <div class="metric-card p-3 rounded-xl bg-brand-50">
            <div class="text-3xl font-extrabold text-slate-900"><span class="counter" data-target="98">0</span>%</div>
            <div class="mt-1 text-sm text-slate-600">Tingkat Kepuasan</div>
          </div>
          <div class="metric-card p-3 rounded-xl bg-brand-50">
            <div class="text-3xl font-extrabold text-slate-900">24/7</div>
            <div class="mt-1 text-sm text-slate-600">Layanan Online</div>
          </div>
          <div class="metric-card p-3 rounded-xl bg-brand-50">
            <div class="text-3xl font-extrabold text-slate-900">&lt; 2 Jam</div>
            <div class="mt-1 text-sm text-slate-600">Waktu Respon</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- HUBUNGI KAMI -->
  <section id="kontak" class="bg-white py-16 section-spacing">
    <div class="container-x">
      <h2 class="text-center text-3xl font-bold text-slate-900 reveal">Hubungi Kami</h2>
      <p class="mx-auto mt-3 max-w-2xl text-center text-slate-600 reveal">
        Tim kami siap membantu kebutuhan kunjungan Anda.
      </p>

      <div class="mt-10 grid gap-6 sm:grid-cols-3">
        <div class="card-yellow rounded-xl bg-white p-6 shadow-sm reveal">
          <div class="text-sm font-semibold text-slate-500">Telepon</div>
          <div class="mt-1 text-lg font-bold text-slate-900">(+62)85256912116</div>
          <a href="tel:+62485256912116" class="mt-2 inline-block text-sm text-slate-700 hover:underline">Hubungi sekarang</a>
        </div>

        <div class="card-yellow rounded-xl bg-white p-6 shadow-sm reveal">
          <div class="text-sm font-semibold text-slate-500">Email</div>
          <div class="mt-1 text-lg font-bold text-slate-900">dekotgorontalo@gmail.com</div>
          <a href="https://dprd.gorontalokota.go.id/kontak" class="mt-2 inline-block text-sm text-slate-700 hover:underline">Kirim email</a>
        </div>

        <div class="card-yellow rounded-xl bg-white p-6 shadow-sm reveal">
          <div class="text-sm font-semibold text-slate-500">Alamat</div>
          <div class="mt-1 text-lg font-bold text-slate-900">Jl. Prof. Dr. Jhon Ario Katili, Gorontalo</div>
          <a target="_blank" rel="noreferrer" href="https://maps.app.goo.gl/WCTqP2DNoK9yxGDs9" class="mt-2 inline-block text-sm text-slate-700 hover:underline">Lihat di Google Maps</a>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-slate-900 text-slate-300 py-14">
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
          <li><a href="#kontributor" class="hover:text-brand-300" id="openContributorBtnFooter">Kontributor</a></li>
        </ul>
      </div>

      <div>
        <div class="font-semibold text-white">Jam Operasional</div>
        <ul class="mt-3 space-y-2 text-sm text-slate-300">
          <li>Senin - Kamis: 08:00 - 16:00</li>
          <li>Jumat: 08:00 - 16:30</li>
          {{-- <li>Minggu: Tutup</li> --}}
        </ul>
      </div>
    </div>
    <div class="container-x mt-10 text-sm text-slate-500">
      © 2025 DPRD Kota Gorontalo. Semua hak dilindungi.
    </div>
  </footer>

  <!-- ========= MODAL KONFIRMASI PENGIRIMAN ========= -->
  <div
    id="confirmationModal"
    class="invisible opacity-0 fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 transition-opacity duration-200"
    aria-hidden="true"
  >
    <!-- backdrop dengan blur -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-md"></div>

    <!-- card -->
    <div
      id="confirmationModalCard"
      class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 transition-transform"
      role="dialog" aria-modal="true" aria-labelledby="confirmationModalTitle"
    >
      <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b bg-white/90 px-6 py-5 backdrop-blur">
        <div>
          <h3 id="confirmationModalTitle" class="text-2xl font-extrabold text-slate-900">Pengajuan Berhasil</h3>
          <p class="mt-1 text-sm text-slate-600">Konfirmasi pengiriman</p>
        </div>
        <button id="closeConfirmationModalBtn" class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-300" aria-label="Tutup">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-6 py-6">
        <div class="mb-6 flex items-center justify-center">
          <div class="rounded-full bg-emerald-100 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        
        <div class="space-y-4 text-center">
          <p class="text-lg font-medium text-slate-900">
            Pengajuan Anda telah berhasil terkirim.
          </p>
          
          <div class="rounded-xl bg-blue-50 p-4 border border-blue-200">
            <p class="text-sm text-blue-800">
              <span class="font-semibold">Silakan tunggu 2x24 jam</span> untuk menerima notifikasi via WhatsApp sesuai keputusan pihak yang dituju.
            </p>
          </div>
          
          <div class="rounded-xl bg-amber-50 p-4 border border-amber-200">
            <p class="text-sm text-amber-800">
              <span class="font-semibold">Pastikan nomor yang Anda masukkan dapat menerima pesan WhatsApp.</span>
            </p>
            <p class="text-xs text-amber-700 mt-1">
              Notifikasi akan dikirim ke nomor: <span id="confirmedPhoneNumber" class="font-mono font-bold"></span>
            </p>
          </div>
          
          <div class="pt-4">
            <button id="confirmUnderstandBtn" class="ripple w-full rounded-xl bg-yellow-500 px-6 py-3 font-semibold text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition-all focus:outline-none focus:ring-2 focus:ring-yellow-300">
              Ya, Saya Mengerti
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========= MODAL KONTRIBUTOR BARU ========= -->
  <div
    id="contributorModal"
    class="invisible opacity-0 fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 transition-opacity duration-200 contributor-modal-bg"
    aria-hidden="true"
  >
    <!-- backdrop dengan blur -->
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>

    <!-- card -->
    <div
      id="contributorModalCard"
      class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-white/95 backdrop-blur-md shadow-2xl ring-1 ring-white/20 transition-transform"
      role="dialog" aria-modal="true" aria-labelledby="contributorModalTitle"
    >
      <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-white/20 bg-white/80 px-6 py-5 backdrop-blur">
        <div>
          <h3 id="contributorModalTitle" class="text-2xl font-extrabold text-slate-900 animate-fadeInDown">Tim Kontributor</h3>
          <p class="mt-1 text-slate-600 text-sm">Terima kasih kepada tim pengembang yang berdedikasi</p>
        </div>
        <button id="closeContributorModalBtn" class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-300" aria-label="Tutup">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
      </div>

      <!-- Body Kontributor Baru -->
      <div class="max-h-[70vh] overflow-y-auto px-6 pb-6 pt-4">
        <div class="contributor-cards-container" id="contributorCardsContainer">
          <!-- Kartu 1 -->
          <div class="contributor-card" id="contributorCard1">
            <div class="contributor-avatar">
              <i class="fas fa-user"></i>
            </div>
            <h2 class="contributor-name">Sahrul M.</h2>
            <p class="contributor-position">Teknik Informatika</p>
            <p class="contributor-university">Universitas Negeri Gorontalo</p>
            <div class="contributor-social-icons">
              <a href="https://www.linkedin.com/in/sahrul-m-9928a2308/"><i class="fab fa-linkedin"></i></a>
              <a href="https://x.com/arulshrul_"><i class="fab fa-twitter"></i></a>
              <a href="https://www.instagram.com/arulshrul_/"><i class="fab fa-instagram"></i></a>
            </div>
          </div>
          
          <!-- Kartu 2 -->
          <div class="contributor-card" id="contributorCard2">
            <div class="contributor-avatar">
              <i class="fas fa-user"></i>
            </div>
            <h2 class="contributor-name">Ais Syafarinah Idris</h2>
            <p class="contributor-position">Teknik Informatika</p>
            <p class="contributor-university">Universitas Negeri Gorontalo</p>
            <div class="contributor-social-icons">
              <a href="https://www.linkedin.com/in/ais-syafarinah-498740289/"><i class="fab fa-linkedin"></i></a>
              <a href="https://x.com/hushayz?s=20"><i class="fab fa-twitter"></i></a>
              <a href="https://www.instagram.com/aissyafarinah/"><i class="fab fa-instagram"></i></a>
            </div>
          </div>
        </div>

        <div class="mt-6 text-center">
          <p class="text-slate-600 text-sm">Terima kasih atas kontribusi dalam pengembangan sistem E-Tamu DPRD Kota Gorontalo</p>
        </div>
      </div>
    </div>
  </div>

  <!-- ========= MODAL MULTI-STEP ========= -->
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
      class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 transition-transform"
      role="dialog" aria-modal="true" aria-labelledby="modalTitle"
    >
      <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b bg-white/90 px-6 py-5 backdrop-blur">
        <div>
          <h3 id="modalTitle" class="text-2xl font-extrabold text-slate-900">Pengajuan Kunjungan Tamu</h3>
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
            <span class="text-sm font-medium text-slate-900">Data Keperluan</span>
          </li>
          <li class="flex items-center gap-3 opacity-80">
            <span id="step2State" class="grid h-8 w-8 place-items-center rounded-full border-2 border-slate-300 text-slate-500 font-semibold">2</span>
            <span class="text-sm font-medium text-slate-700">Pihak Tujuan &amp; Dokumen</span>
          </li>
        </ol>
      </div>

      <!-- Body -->
      <form id="visitForm" action="{{ route('tamu.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="max-h-[70vh] overflow-y-auto px-6 pb-6">
        @csrf

        <!-- STEP 1 -->
        <section id="step1" class="mt-6 space-y-6">
          <div class="rounded-xl border border-brand-200 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-slate-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Informasi Pemohon</h4>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input required name="nama" type="text" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="Nama lengkap">
                @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700">Alamat Email <span class="text-red-500">*</span></label>
                <input required name="email" type="email" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="nama@email.com">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700">Nomor Handphone (WhatsApp) <span class="text-red-500">*</span></label>
                <div class="mt-1 flex rounded-lg border border-slate-300 bg-white shadow-sm focus-within:border-brand-500 focus-within:ring-1 focus-within:ring-brand-500">
                  <span class="inline-flex items-center rounded-l-lg bg-slate-50 px-3 text-slate-600 select-none">+62</span>
                  <input required name="no_hp" type="tel" id="no_hp" class="w-full rounded-r-lg px-3 py-2 focus:outline-none" placeholder="81234567890">
                </div>
                <p class="mt-1 text-xs text-slate-500">Format: 812-3456-7890 (tanpa +62)</p>
                @error('no_hp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700">Jumlah Peserta <span class="text-red-500">*</span></label>
                <input required name="jumlah" type="number" min="1" max="50" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="cth: 10">
                <p class="mt-1 text-xs text-slate-500">Maksimal 50 orang per kunjungan</p>
                @error('jumlah') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <!-- Informasi Instansi/Organisasi -->
          <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-slate-500/10 p-2 text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Informasi Instansi/Organisasi</h4>
            </div>

            <label class="block text-sm font-medium text-slate-700">Instansi/Daerah Asal <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
              <label class="group flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer">
                <input type="radio" name="instansi_kategori" value="opd" class="peer" required><span class="text-sm text-slate-700">OPD</span>
              </label>
              <label class="group flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer">
                <input type="radio" name="instansi_kategori" value="lembaga" class="peer"><span class="text-sm text-slate-700">Lembaga</span>
              </label>
              <label class="group flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer">
                <input type="radio" name="instansi_kategori" value="perseorangan" class="peer"><span class="text-sm text-slate-700">Perseorangan</span>
              </label>
              <label class="group flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 has-checked:ring-brand-400 cursor-pointer">
                <input type="radio" name="instansi_kategori" value="ormas" class="peer"><span class="text-sm text-slate-700">Ormas</span>
              </label>
            </div>
            @error('instansi_kategori') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror

            <div class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-700">Nama Instansi/Organisasi <span class="text-red-500">*</span></label>
                <input required name="instansi_nama" type="text" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="cth: Dinas Pendidikan">
                @error('instansi_nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Detail Keperluan <span class="text-red-500">*</span></label>
                <textarea required name="keperluan" rows="3" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="Tuliskan keperluan kunjungan..."></textarea>
                @error('keperluan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <!-- Jadwal -->
          <div class="rounded-xl border border-brand-200 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-slate-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Jadwal Kunjungan</h4>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-700">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input 
                  required 
                  name="tanggal_kunjungan" 
                  type="date" 
                  id="tanggal_kunjungan"
                  min="{{ date('Y-m-d') }}"
                  class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                >
                <p class="mt-1 text-xs text-slate-500">Hanya hari Senin-Jumat (tidak termasuk Sabtu/Minggu)</p>
                @error('tanggal_kunjungan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700">Waktu Kunjungan <span class="text-red-500">*</span></label>
                <input 
                  required 
                  name="waktu_kunjungan" 
                  type="time"
                  id="waktu_kunjungan"
                  min="08:00"
                  max="16:30"
                  class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                >
                <p class="mt-1 text-xs text-slate-500">
                  Pilih waktu antara 08:00 - 16:30
                </p>
                @error('waktu_kunjungan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3">
            <button type="button" id="toStep2" class="ripple rounded-lg bg-yellow-500 px-5 py-2 font-semibold text-white shadow hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all focus:outline-none focus:ring-2 focus:ring-slate-300">Lanjut</button>
          </div>
        </section>

        <!-- STEP 2 -->
        <section id="step2" class="mt-6 hidden space-y-6">
          <div class="rounded-xl border border-fuchsia-200 bg-fuchsia-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-fuchsia-500/20 p-2 text-fuchsia-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13 7H7v6h6V7z" /><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm10 12H5V5h10v10z" clip-rule="evenodd"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Pihak yang Dituju</h4>
            </div>

            <label class="block text-sm font-medium text-slate-700">Kategori Pihak yang Dituju <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
              <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer">
                <input type="radio" name="kategori_pihak_top" value="pimpinan" class="peer" required><span class="text-sm text-slate-700">Pimpinan</span>
              </label>
              <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer">
                <input type="radio" name="kategori_pihak_top" value="akd" class="peer"><span class="text-sm text-slate-700">AKD</span>
              </label>
              <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 cursor-pointer">
                <input type="radio" name="kategori_pihak_top" value="sekretariat" class="peer"><span class="text-sm text-slate-700">Sekretariat</span>
              </label>
            </div>

            <!-- List dinamis -->
            <div id="pimpinanList" class="mt-4 hidden space-y-3">
              <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Ketua DPRD" required><span class="text-slate-700">Ketua DPRD</span></label>
              <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Wakil Ketua 1"><span class="text-slate-700">Wakil Ketua 1</span></label>
              <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Wakil Ketua 2"><span class="text-slate-700">Wakil Ketua 2</span></label>
            </div>

            <div id="akdList" class="mt-4 hidden space-y-3">
              <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Badan Kehormatan"><span class="text-slate-700">Badan Kehormatan</span></label>
                <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Badan Anggaran"><span class="text-slate-700">Badan Anggaran</span></label>
                <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Badan Pembentukan Peraturan Daerah"><span class="text-slate-700">Badan Pembentukan Peraturan Daerah</span></label>
                <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Badan Musyawarah"><span class="text-slate-700">Badan Musyawarah</span></label>
                <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Komisi 1"><span class="text-slate-700">Komisi 1</span></label>
                <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Komisi 2"><span class="text-slate-700">Komisi 2</span></label>
                <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Komisi 3"><span class="text-slate-700">Komisi 3</span></label>
              </div>
            </div>

            <div id="sekretariatList" class="mt-4 hidden space-y-3">
              <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Sekretaris"><span class="text-slate-700">Sekretaris</span></label>
              <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Bagian Umum dan Humas"><span class="text-slate-700">Bagian Umum dan Humas</span></label>
              <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Bagian Keuangan"><span class="text-slate-700">Bagian Keuangan</span></label>
              <label class="flex items-center gap-3 rounded-lg border bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200"><input type="radio" name="kategori_pihak" value="Persidangan dan Perundang-undangan"><span class="text-slate-700">Persidangan dan Perundang-undangan</span></label>
            </div>
          </div>

          <div class="rounded-xl border border-brand-200 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-slate-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 7a4 4 0 118 0v4a2 2 0 11-4 0V7a2 2 0 10-4 0v6a4 4 0 108 0V9h2v4a6 6 0 11-12 0V7z"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Upload Dokumen</h4>
            </div>
            <div class="grid grid-cols-1 gap-5">
              <div>
                <label class="block text-sm font-medium text-slate-700">Surat Pemberitahuan/Surat Tugas (opsional)</label>
                <input type="file" name="dokumen" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 file:mr-4 file:rounded-lg file:border-0 file:bg-yellow-500 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-slate-800">
                <p class="mt-1 text-xs text-slate-500">PDF/JPG/PNG maks 5MB.</p>
                @error('dokumen') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <button type="button" id="backTo1" class="rounded-lg px-4 py-2 text-slate-700 hover:bg-slate-100 transition-colors">Kembali</button>
            <div class="flex items-center gap-3">
              <button type="button" id="cancelBtn" class="rounded-lg px-4 py-2 text-slate-600 hover:bg-slate-100 transition-colors">Batal</button>
              <button 
                type="submit" 
                id="submitBtn" 
                class="ripple rounded-lg bg-yellow-500 px-5 py-2 font-semibold text-white shadow hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all focus:outline-none focus:ring-2 focus:ring-slate-300 flex items-center justify-center gap-2 min-h-[44px]"
                data-loading-text="Mengirim..."
              >
                <span id="submitText">Kirim Pengajuan</span>
                <span id="submitSpinner" class="hidden h-5 w-5 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
              </button>
            </div>
          </div>
        </section>
      </form>
    </div>
  </div>

  <!-- Toast -->
  <div id="toast" class="pointer-events-none fixed bottom-5 right-5 z-60 {{ session('success') ? '' : 'hidden' }} rounded-xl bg-emerald-600 px-4 py-3 text-white shadow-lg animate-slideDown">
    {{ session('success')}}
  </div>

  <!-- ===== SCRIPTS ===== -->
  <script>
    // --- Mobile Menu ---
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeMobileMenuBtn = document.getElementById('closeMobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    
    function openMobileMenu() {
      mobileMenu.classList.add('open');
      mobileMenuOverlay.classList.add('active');
      document.body.classList.add('modal-open');
    }
    
    function closeMobileMenu() {
      mobileMenu.classList.remove('open');
      mobileMenuOverlay.classList.remove('active');
      document.body.classList.remove('modal-open');
    }
    
    mobileMenuBtn.addEventListener('click', openMobileMenu);
    closeMobileMenuBtn.addEventListener('click', closeMobileMenu);
    mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    
    // Tutup mobile menu saat klik link
    document.querySelectorAll('#mobileMenu a').forEach(link => {
      link.addEventListener('click', closeMobileMenu);
    });

    // --- Modal Konfirmasi Pengiriman ---
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmationModalCard = document.getElementById('confirmationModalCard');
    const closeConfirmationModalBtn = document.getElementById('closeConfirmationModalBtn');
    const confirmUnderstandBtn = document.getElementById('confirmUnderstandBtn');
    const confirmedPhoneNumber = document.getElementById('confirmedPhoneNumber');
    
    function openConfirmationModal(phoneNumber) {
      closeModal(); // Tutup modal pengajuan
      
      // Tampilkan nomor telepon yang dikonfirmasi
      if (phoneNumber) {
        confirmedPhoneNumber.textContent = phoneNumber;
      }
      
      confirmationModal.classList.remove('invisible','opacity-0');
      confirmationModal.setAttribute('aria-hidden','false');
      document.body.classList.add('modal-open');
      
      // Animasi kartu modal
      confirmationModalCard.style.transform = 'translateY(18px) scale(.98)';
      confirmationModalCard.style.opacity = '0';
      requestAnimationFrame(()=> {
        confirmationModalCard.style.transition = 'transform .22s ease-out, opacity .22s ease-out';
        confirmationModalCard.style.transform = 'translateY(0) scale(1)';
        confirmationModalCard.style.opacity = '1';
      });
      
      setTimeout(()=>confirmationModalCard.focus?.(), 50);
    }
    
    function closeConfirmationModal() {
      confirmationModalCard.style.transform = 'translateY(12px) scale(.98)';
      confirmationModalCard.style.opacity = '0';
      setTimeout(()=>{
        confirmationModal.classList.add('opacity-0');
        confirmationModal.setAttribute('aria-hidden','true');
        document.body.classList.remove('modal-open');
        setTimeout(()=>confirmationModal.classList.add('invisible'),160);
      },160);
    }
    
    // Event listeners untuk modal konfirmasi
    closeConfirmationModalBtn && closeConfirmationModalBtn.addEventListener('click', closeConfirmationModal);
    confirmUnderstandBtn && confirmUnderstandBtn.addEventListener('click', closeConfirmationModal);
    confirmationModal.addEventListener('click', e => { if (e.target === confirmationModal) closeConfirmationModal(); });
    document.addEventListener('keydown', e => { 
      if (e.key === 'Escape' && confirmationModal.getAttribute('aria-hidden') !== 'true') closeConfirmationModal(); 
    });

    // --- Modal Kontributor Baru ---
    const contributorModal = document.getElementById('contributorModal');
    const contributorModalCard = document.getElementById('contributorModalCard');
    const openContributorBtn = document.getElementById('openContributorBtn');
    const openContributorBtnMobile = document.getElementById('openContributorBtnMobile');
    const openContributorBtnFooter = document.getElementById('openContributorBtnFooter');
    const closeContributorModalBtn = document.getElementById('closeContributorModalBtn');
    const contributorCardsContainer = document.getElementById('contributorCardsContainer');
    const contributorCard1 = document.getElementById('contributorCard1');
    const contributorCard2 = document.getElementById('contributorCard2');
    
    function openContributorModal() {
      closeMobileMenu(); // Tutup mobile menu jika terbuka
      contributorModal.classList.remove('invisible','opacity-0');
      contributorModal.setAttribute('aria-hidden','false');
      document.body.classList.add('modal-open');
      
      // Animasi kartu modal
      contributorModalCard.style.transform = 'translateY(18px) scale(.98)';
      contributorModalCard.style.opacity = '0';
      requestAnimationFrame(()=> {
        contributorModalCard.style.transition = 'transform .22s ease-out, opacity .22s ease-out';
        contributorModalCard.style.transform = 'translateY(0) scale(1)';
        contributorModalCard.style.opacity = '1';
      });
      
      // Tampilkan kartu kontributor dengan animasi
      setTimeout(() => {
        contributorCardsContainer.classList.add('show');
      }, 300);
      
      setTimeout(() => {
        contributorCard1.classList.add('show');
      }, 500);
      
      setTimeout(() => {
        contributorCard2.classList.add('show');
      }, 700);
      
      setTimeout(()=>contributorModalCard.focus?.(), 50);
    }
    
    function closeContributorModal() {
      // Sembunyikan kartu kontributor
      contributorCardsContainer.classList.remove('show');
      contributorCard1.classList.remove('show');
      contributorCard2.classList.remove('show');
      
      contributorModalCard.style.transform = 'translateY(12px) scale(.98)';
      contributorModalCard.style.opacity = '0';
      setTimeout(()=>{
        contributorModal.classList.add('opacity-0');
        contributorModal.setAttribute('aria-hidden','true');
        document.body.classList.remove('modal-open');
        setTimeout(()=>contributorModal.classList.add('invisible'),160);
      },160);
    }
    
    // Event listeners untuk modal kontributor
    openContributorBtn && openContributorBtn.addEventListener('click', openContributorModal);
    openContributorBtnMobile && openContributorBtnMobile.addEventListener('click', openContributorModal);
    openContributorBtnFooter && openContributorBtnFooter.addEventListener('click', openContributorModal);
    closeContributorModalBtn && closeContributorModalBtn.addEventListener('click', closeContributorModal);
    contributorModal.addEventListener('click', e => { if (e.target === contributorModal) closeContributorModal(); });
    document.addEventListener('keydown', e => { 
      if (e.key === 'Escape' && contributorModal.getAttribute('aria-hidden') !== 'true') closeContributorModal(); 
    });

    // --- Modal (tengah, blur background & lock scroll) ---
    const modal   = document.getElementById('applyModal');
    const modalCard = document.getElementById('modalCard');
    const openers = [
      document.getElementById('openModalBtn'), 
      document.getElementById('openModalBtnHero'),
      document.getElementById('openModalBtnMobile')
    ].filter(Boolean);
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
      closeMobileMenu(); // Tutup mobile menu jika terbuka
      modal.classList.remove('invisible','opacity-0');
      modal.setAttribute('aria-hidden','false');
      document.body.classList.add('modal-open'); // kunci scroll BG
      step1.classList.remove('hidden'); step2.classList.add('hidden');
      step1State.classList.add('bg-brand-500','border-brand-500','text-white');
      step2State.classList.remove('bg-brand-500','text-white'); step2State.classList.add('border-slate-300','text-slate-500');
      
      // Reset validasi tanggal
      setTimeout(() => {
        validateTanggalKunjungan();
        validateWaktuKunjungan();
      }, 100);
      
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
      // Validasi step 1 sebelum lanjut
      if (!validateStep1()) return;
      
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

    // Validasi step 1
    function validateStep1() {
      const requiredFields = form.querySelectorAll('#step1 [required]');
      let isValid = true;
      
      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          field.reportValidity();
          isValid = false;
        }
      });
      
      // Validasi tanggal dan waktu khusus
      if (!validateTanggalKunjungan() || !validateWaktuKunjungan()) {
        isValid = false;
      }
      
      return isValid;
    }

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

    // Reveal on scroll - versi yang lebih ringan
    const reveals = document.querySelectorAll('.reveal');
    const revealOnScroll = () => {
      reveals.forEach(el => {
        const rect = el.getBoundingClientRect();
        const isVisible = (rect.top <= window.innerHeight * 0.85);
        if (isVisible) {
          el.classList.add('active');
        }
      });
    };
    window.addEventListener('scroll', revealOnScroll, { passive: true });
    revealOnScroll(); // Jalankan sekali saat halaman dimuat

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

    // --- Validasi Tanggal (non-Sabtu/Minggu) dan Waktu (08:00-16:30) ---
    function validateTanggalKunjungan() {
      const tanggalInput = document.getElementById('tanggal_kunjungan');
      const waktuInput = document.getElementById('waktu_kunjungan');
      const today = new Date();
      const selectedDate = new Date(tanggalInput.value);
      
      // Reset validasi
      tanggalInput.setCustomValidity('');
      waktuInput.setCustomValidity('');
      
      // Validasi: tidak boleh hari Sabtu (6) atau Minggu (0)
      const dayOfWeek = selectedDate.getDay();
      if (dayOfWeek === 0 || dayOfWeek === 6) {
        tanggalInput.setCustomValidity('Tidak dapat memilih hari Sabtu atau Minggu. Silakan pilih hari Senin-Jumat.');
        tanggalInput.reportValidity();
        return false;
      }
      
      // Validasi waktu jika tanggal adalah hari ini
      if (tanggalInput.value === today.toISOString().split('T')[0]) {
        const now = new Date();
        const minTime = new Date(now.getTime() + 60 * 60 * 1000); // 1 jam dari sekarang
        const minTimeString = minTime.getHours().toString().padStart(2, '0') + ':' + 
                             minTime.getMinutes().toString().padStart(2, '0');
        
        // Jika waktu minimum melebihi 16:30
        if (minTimeString > '16:30') {
          tanggalInput.setCustomValidity('Tidak bisa memilih hari ini karena sudah melewati jam kerja');
          tanggalInput.reportValidity();
          waktuInput.disabled = true;
          return false;
        } else {
          waktuInput.min = minTimeString > '08:00' ? minTimeString : '08:00';
          waktuInput.max = '16:30';
          waktuInput.disabled = false;
        }
      } else {
        // Untuk hari selain hari ini
        waktuInput.min = '08:00';
        waktuInput.max = '16:30';
        waktuInput.disabled = false;
      }
      
      return true;
    }

    function validateWaktuKunjungan() {
      const waktuInput = document.getElementById('waktu_kunjungan');
      
      // Reset validasi
      waktuInput.setCustomValidity('');
      
      // Validasi rentang waktu
      if (waktuInput.value) {
        if (waktuInput.value < '08:00' || waktuInput.value > '16:30') {
          waktuInput.setCustomValidity('Waktu kunjungan harus antara 08:00 - 16:30');
          waktuInput.reportValidity();
          return false;
        }
      }
      
      return true;
    }

    // Event listeners untuk validasi
    document.getElementById('tanggal_kunjungan')?.addEventListener('change', function() {
      validateTanggalKunjungan();
      validateWaktuKunjungan();
    });

    document.getElementById('waktu_kunjungan')?.addEventListener('change', validateWaktuKunjungan);

    // --- Handle form submission untuk menampilkan modal konfirmasi ---
    form?.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Ambil nomor telepon untuk ditampilkan di modal konfirmasi
      const phoneInput = document.getElementById('no_hp');
      const phoneNumber = phoneInput ? '+62' + phoneInput.value : '';
      
      // Simulasikan pengiriman form
      const formData = new FormData(form);
      
      // Kirim form via fetch
      fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
      })
      .then(response => {
        if (response.ok) {
          // Tampilkan modal konfirmasi
          openConfirmationModal(phoneNumber);
          
          // Reset form
          form.reset();
          
          // Reset step
          step2.classList.add('hidden');
          step1.classList.remove('hidden');
          step1State.classList.add('bg-brand-500','border-brand-500','text-white');
          step2State.classList.remove('bg-brand-500','text-white'); 
          step2State.classList.add('border-slate-300','text-slate-500');
          
          // Reset kategori pihak
          Object.values(lists).forEach(el => setGroupState(el, { hidden: true }));
          document.querySelectorAll('input[name="kategori_pihak_top"]').forEach(r => r.checked = false);
          
          // Tampilkan toast success
          toast.textContent = 'Pengajuan berhasil dikirim.';
          toast.classList.remove('hidden');
          setTimeout(() => toast.classList.add('hidden'), 3000);
        } else {
          // Handle error
          toast.textContent = 'Terjadi kesalahan saat mengirim pengajuan.';
          toast.classList.remove('hidden');
          setTimeout(() => toast.classList.add('hidden'), 3000);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        toast.textContent = 'Terjadi kesalahan saat mengirim pengajuan.';
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
      });
    });

    // --- Optimasi untuk mobile: Hindari zoom pada input ---
    document.addEventListener('DOMContentLoaded', function() {
      let viewport = document.querySelector("meta[name=viewport]");
      if (viewport) {
        viewport.setAttribute("content", "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0");
      }
      
      // Inisialisasi validasi tanggal
      validateTanggalKunjungan();
    });
  </script>
</body>
</html>