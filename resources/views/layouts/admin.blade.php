<!DOCTYPE html>
<html lang="id" x-data="app()" x-init="init()" x-bind:class="{'overflow-hidden': showAddUser || showAddEditTamu || showLogout}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Tamu DPRD – Admin</title>
  <link rel="icon" type="image/png" href="/img/logoDprd.png">

  <!-- CSRF untuk AJAX -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Tailwind + Alpine -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
  <!-- Chart.js untuk grafik dashboard -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50:'#FFF9EC', 100:'#FFF3D6', 200:'#FFE6AD', 300:'#FFD973',
              400:'#FFCA3A', 500:'#FFB300', 600:'#E6A100', 700:'#B37D00',
              800:'#805C00', 900:'#4D3800'
            },
            brown: {
              100: '#F7F3EF',
              200: '#E8D9C9',
              300: '#D4B99A',
              400: '#B8946A',
              500: '#9C7C4D',
              600: '#7D5E36',
              700: '#5E4525',
              800: '#3F2E17',
              900: '#2A1D0D'
            },
            night: { 900:'#0B1220', 800:'#0F1930' }
          },
          boxShadow: {
            soft: '0 10px 30px -12px rgba(0,0,0,.12)',
            lift: '0 18px 40px -20px rgba(0,0,0,.25)',
            'yellow-glow': '0 0 20px rgba(255, 179, 0, 0.4)',
            'brown-glow': '0 0 15px rgba(156, 124, 77, 0.3)'
          },
          backgroundImage: {
            'yellow-noise': "radial-gradient(1200px 600px at -10% -20%, rgba(255, 227, 140, .35), transparent 60%), radial-gradient(800px 500px at 110% 120%, rgba(255, 214, 102, .28), transparent 60%)",
            'yellow-gradient': 'linear-gradient(135deg, #FFB300 0%, #FFCA3A 100%)',
            'gold-gradient': 'linear-gradient(135deg, #FFB300 0%, #E6A100 100%)',
            'brown-gradient': 'linear-gradient(135deg, #9C7C4D 0%, #B8946A 100%)'
          },
          keyframes: {
            fadeUp:   { '0%': { opacity:0, transform:'translateY(12px)'}, '100%': { opacity:1, transform:'translateY(0)'} },
            scaleIn:  { '0%': { opacity:0, transform:'scale(.96)'},      '100%': { opacity:1, transform:'scale(1)'} },
            pulseSoft:{ '0%,100%': { transform:'scale(1)'}, '50%': { transform:'scale(1.05)' } },
            floaty:   { '0%,100%': { transform:'translateY(0)'}, '50%': { transform:'translateY(-6px)' } },
            shimmer:  { '0%': { backgroundPosition:'-200% 0' }, '100%': { backgroundPosition:'200% 0' } },
            bounceIn: { '0%': { opacity:0, transform:'scale(0.3)' }, '50%': { opacity:1, transform:'scale(1.05)' }, '70%': { transform:'scale(0.9)' }, '100%': { opacity:1, transform:'scale(1)' } },
            brownPulse: { '0%,100%': { boxShadow: '0 0 0 0 rgba(156, 124, 77, 0.7)' }, '50%': { boxShadow: '0 0 0 10px rgba(156, 124, 77, 0)' } }
          },
          animation: {
            fadeUp:'fadeUp .45s ease-out both',
            scaleIn:'scaleIn .28s ease-out both',
            pulseSoft:'pulseSoft 2s ease-in-out infinite',
            floaty:'floaty 6s ease-in-out infinite',
            shimmer:'shimmer 2.5s linear infinite',
            bounceIn:'bounceIn 0.6s ease-out both',
            brownPulse: 'brownPulse 2s infinite'
          }
        }
      }
    }
  </script>
  <style>
    html{ scrollbar-gutter: stable both-edges; }
    .hairline{height:1px;background:repeating-linear-gradient(90deg,rgba(0,0,0,.06)0 8px,transparent 8px 16px)}
    .ripple{position:relative;overflow:hidden}
    .ripple .rp{position:absolute;border-radius:999px;transform:translate(-50%,-50%);pointer-events:none;opacity:.25;background:#000;animation:ripple .6s ease-out forwards}
    @keyframes ripple{from{width:0;height:0;opacity:.25}to{width:360px;height:360px;opacity:0}}
    .scrollbar-thin::-webkit-scrollbar{height:10px;width:10px}
    .scrollbar-thin::-webkit-scrollbar-track{background:#fef3c7;border-radius:999px}
    .scrollbar-thin::-webkit-scrollbar-thumb{background:#facc15;border-radius:999px}
    .scrollbar-thin::-webkit-scrollbar-thumb:hover{background:#eab308}
    [x-cloak]{ display:none !important; }
    .shimmer {
      background: linear-gradient(90deg, rgba(255, 227, 140, .25), rgba(255, 227, 140, .55), rgba(255, 227, 140, .25));
      background-size: 200% 100%;
    }
    .yellow-glow {
      box-shadow: 0 0 20px rgba(255, 179, 0, 0.4);
    }
    .brown-glow {
      box-shadow: 0 0 15px rgba(156, 124, 77, 0.3);
    }
    .modal-open {
      overflow: hidden;
    }
    @media (max-width: 768px) {
      .mobile-card {
        padding: 1rem;
      }
      .mobile-stack {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
      }
    }
  </style>
</head>
<body class="min-h-screen overflow-y-scroll antialiased text-slate-800 bg-brand-50 bg-yellow-noise selection:bg-brand-200/60">

  <!-- ====== FORM LOGOUT TERSEMBUNYI ====== -->
  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
  </form>

  <!-- ================= FIXED SIDEBAR (LEFT) ================= -->
  <aside class="hidden md:flex fixed left-0 top-0 h-screen w-72 z-40">
    <div class="w-full h-full rounded-none border-r border-brand-300 bg-gradient-to-b from-brand-100 via-brand-50 to-brand-100 backdrop-blur-sm shadow-soft p-4 flex flex-col">
      <div class="flex items-center gap-3 mb-4 p-2 rounded-xl bg-yellow-gradient shadow-yellow-glow animate-pulseSoft">
        <img
          src="/img/logoDprd.png"
          alt="Logo DPRD"
          class="h-10 w-10 md:h-12 md:w-12 object-contain transition-transform duration-500 motion-safe:group-hover:scale-110 motion-safe:group-hover:rotate-6"
        />
        <div class="leading-tight">
          <div class="font-extrabold text-slate-900">E-Tamu DPRD</div>
          <div class="text-xs text-slate-700/80">Kota Gorontalo</div>
        </div>
      </div>
      <nav class="space-y-1">
        <a href="{{ route('admin.index') }}" 
           class="w-full text-left px-3 py-2 rounded-xl transition-all duration-300 block {{ request()->routeIs('admin.index') ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-200/70 hover:text-slate-900 hover:shadow-md' }}">
          <span class="inline-flex items-center gap-2">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 13h1v7c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-7h1c.6 0 1-.4 1-1s-.4-1-1-1h-1V4c0-1.1-.9-2-2-2H6C4.9 2 4 2.9 4 4v7H3c-.6 0-1 .4-1 1s.4 1 1 1zM6 4h12v7H6V4z"/>
            </svg>
            Dashboard
          </span>
        </a>
        <a href="{{ route('admin.users') }}" 
           class="w-full text-left px-3 py-2 rounded-xl transition-all duration-300 block {{ request()->routeIs('admin.users') ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-200/70 hover:text-slate-900 hover:shadow-md' }}">
          <span class="inline-flex items-center gap-2">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Kelola Pengguna
          </span>
        </a>
        <a href="{{ route('admin.tamu') }}" 
           class="w-full text-left px-3 py-2 rounded-xl transition-all duration-300 block {{ request()->routeIs('admin.tamu') ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-200/70 hover:text-slate-900 hover:shadow-md' }}">
          <span class="inline-flex items-center gap-2">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
            Kelola Tamu
          </span>
        </a>
      </nav>
      <div class="mt-auto pt-3">
        <button @click="showLogout=true"
                class="w-full text-left px-3 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 ring-1 ring-rose-200 transition-all duration-300 hover:shadow-md">
          <span class="inline-flex items-center gap-2">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
            </svg>
            Logout
          </span>
        </button>
      </div>
    </div>
  </aside>

  <!-- ================= NAVBAR (TOP) ================= -->
  <header id="topbar" class="fixed inset-x-0 top-0 z-30 bg-brand-100/80 backdrop-blur-md transition-all duration-300 md:pl-72">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between md:justify-end py-3">
        <div class="flex items-center gap-3 md:hidden">
          <button class="-ml-2 p-2 rounded-lg hover:bg-brand-300/70 transition-all duration-300" @click="sidebarOpen=true" aria-label="Open sidebar">
            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <a href="{{ route('admin.index') }}" class="flex items-center gap-3">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-gradient text-white font-bold shadow-yellow-glow animate-pulseSoft">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l1.8 4.9L19 9l-5.2 2.1L12 16l-1.8-4.9L5 9l5.2-2.1L12 2z"/>
              </svg>
            </span>
            <div class="leading-tight">
              <div class="font-extrabold text-slate-900">E-Tamu DPRD</div>
              <div class="text-xs text-slate-600">Kota Gorontalo</div>
            </div>
          </a>
        </div>

        <!-- Right: clock + role -->
        <div class="flex items-center gap-4 ml-auto">
          <div class="text-right leading-tight hidden sm:block">
            <div id="clock" class="font-semibold text-slate-900">--:--:-- WITA</div>
            <div id="date" class="text-xs text-slate-600">—</div>
          </div>

          <!-- User dropdown -->
          <div class="relative" x-data="{open:false}" @keydown.escape.window="open=false">
            <button class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-brand-300/80 transition-all duration-300" @click="open=!open" :aria-expanded="open">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-yellow-gradient text-white ring-1 ring-brand-400 shadow-yellow-glow animate-pulseSoft">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </span>
              <span class="text-sm hidden sm:inline">
                  @if(auth()->check())
                      {{ auth()->user()->full_name ?? auth()->user()->username ?? auth()->user()->email ?? 'Host' }}
                  @else
                      Host
                  @endif
              </span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>

            <div x-cloak x-show="open" x-transition.origin.top.right @click.outside="open=false"
                 class="absolute right-0 mt-2 w-52 rounded-xl border border-brand-300 bg-white/95 backdrop-blur-lg shadow-lg overflow-hidden">
              <div class="py-1">
                <button type="button" class="w-full text-left px-4 py-2 text-sm hover:bg-brand-100/80 flex items-center gap-2 transition-colors duration-300" @click="open=false; showLogout=true">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 4.5A1.5 1.5 0 014.5 3h5A1.5 1.5 0 0111 4.5v1a.5.5 0 01-1 0v-1a.5.5 0 00-.5-.5h-5a.5.5 0 00-.5.5v11a.5.5 0 00.5.5h5a2 2 0 002-2v-1a.5.5 0 011 0v1A1.5 1.5 0 019.5 17h-5A1.5 1.5 0 013 15.5v-11z" clip-rule="evenodd"/>
                    <path d="M13.854 10.354a.5.5 0 010-.708l2-2a.5.5 0 11.707.708L15.707 9.5H8.5a.5.5 0 010-1h7.207l.854-.854a.5.5 0 11.707.707l-2 2a.5.5 0 01-.707 0z"/>
                  </svg>
                  Logout
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="hairline"></div>
  </header>

  <!-- ================ MAIN ================ -->
  <div class="pt-20 md:pl-72">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="flex gap-6">

        <!-- Mobile Sidebar Drawer -->
        <div class="md:hidden fixed inset-0 z-50" x-cloak x-show="sidebarOpen" x-transition.opacity aria-hidden="true">
          <div class="absolute inset-0 bg-black/40" @click="sidebarOpen=false"></div>
          <aside class="absolute left-0 top-0 h-full w-72 bg-gradient-to-b from-brand-100 via-brand-50 to-brand-100 p-4 shadow-2xl" x-transition>
            <div class="flex items-center justify-between mb-3">
              <div class="font-bold text-slate-900">Menu</div>
              <button class="p-2 rounded-lg hover:bg-brand-200/80 transition-all duration-300" @click="sidebarOpen=false">✕</button>
            </div>
            <nav class="space-y-1">
              <a href="{{ route('admin.index') }}" 
                 class="w-full text-left px-3 py-2 rounded-xl transition-all duration-300 block {{ request()->routeIs('admin.index') ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-200/70 hover:text-slate-900 hover:shadow-md' }}"
                 @click="sidebarOpen=false">
                Dashboard
              </a>
              <a href="{{ route('admin.users') }}" 
                 class="w-full text-left px-3 py-2 rounded-xl transition-all duration-300 block {{ request()->routeIs('admin.users') ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-200/70 hover:text-slate-900 hover:shadow-md' }}"
                 @click="sidebarOpen=false">
                Kelola Pengguna
              </a>
              <a href="{{ route('admin.tamu') }}" 
                 class="w-full text-left px-3 py-2 rounded-xl transition-all duration-300 block {{ request()->routeIs('admin.tamu') ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-200/70 hover:text-slate-900 hover:shadow-md' }}"
                 @click="sidebarOpen=false">
                Kelola Tamu
              </a>
            </nav>
            <div class="mt-6">
              <button @click="showLogout=true; sidebarOpen=false"
                      class="w-full text-left px-3 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 ring-1 ring-rose-200 transition-all duration-300 hover:shadow-md">
                Logout
              </button>
            </div>
          </aside>
        </div>

        <!-- ============== MAIN CONTENT ============== -->
        <main class="flex-1 w-full space-y-8">
          @yield('content')
        </main>
      </div>
    </div>
  </div>

  <!-- ============== MODAL: Tambah/Edit Tamu (MULTI-STEP) ============== -->
  <div x-cloak x-show="showAddEditTamu"
       x-data
       @keydown.escape.window="showAddEditTamu=false"
       class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-all duration-200"
       :class="showAddEditTamu ? 'opacity-100 visible' : 'opacity-0 invisible'">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showAddEditTamu=false"></div>

    <div class="relative w-full max-w-4xl max-h-[90vh] overflow-hidden rounded-3xl bg-[#FFFDF5] shadow-2xl ring-1 ring-brand-300 transition-transform animate-scaleIn">
      <!-- Header sticky -->
      <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-brand-300 bg-[#FFFDF5]/95 px-6 py-5 backdrop-blur">
        <div>
          <h3 class="text-2xl font-extrabold flex items-center gap-2 text-slate-900">
            <svg class="h-6 w-6 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 7H5a2 2 0 00-2 2v7h18V9a2 2 0 00-2-2zM3 18h18v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1z"/>
            </svg>
            <span x-text="editingTamu ? 'Edit Kunjungan Tamu' : 'Pengajuan Kunjungan Tamu'"></span>
          </h3>
          <p class="mt-1 text-slate-700 text-sm">Silakan lengkapi formulir di bawah ini dengan data yang benar</p>
        </div>
        <button class="rounded-full p-2 text-slate-500 hover:bg-brand-100/80 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all duration-300" aria-label="Tutup" @click="showAddEditTamu=false">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
      </div>

      <!-- Stepper -->
      <div class="px-6 pt-4">
        <ol class="flex items-center gap-6 overflow-x-auto">
          <li class="flex items-center gap-3 flex-shrink-0">
            <span class="grid h-8 w-8 place-items-center rounded-full border-2 font-semibold transition-all duration-300"
                  :class="step===1 ? 'border-brand-500 bg-brand-500 text-white shadow-yellow-glow' : 'border-brand-300 bg-brand-100 text-brand-700'">1</span>
            <span class="text-sm font-medium text-slate-800">Data Keperluan</span>
          </li>
          <li class="flex items-center gap-3 flex-shrink-0">
            <span class="grid h-8 w-8 place-items-center rounded-full border-2 font-semibold transition-all duration-300"
                  :class="step===2 ? 'border-brand-500 bg-brand-500 text-white shadow-yellow-glow' : 'border-brand-300 bg-brand-100 text-brand-700'">2</span>
            <span class="text-sm font-medium text-slate-800">Pihak Tujuan &amp; Dokumen</span>
          </li>
        </ol>
      </div>

      <!-- Form -->
      <form @submit.prevent="saveTamu" class="max-h-[calc(90vh-180px)] overflow-y-auto px-6 pb-6">
        <!-- STEP 1 -->
        <section x-show="step===1" x-transition.opacity class="mt-6 space-y-6">
          <!-- Informasi Pemohon -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700 animate-bounceIn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Informasi Pemohon</h4>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-800">Nama Lengkap <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.nama" type="text" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300" placeholder="Nama lengkap">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Alamat Email <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.email" type="email" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300" placeholder="nama@email.com">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Nomor Handphone (WhatsApp) <span class="text-red-500">*</span></label>
                <div class="mt-1 flex rounded-xl border border-brand-400 bg-white shadow-sm focus-within:border-brand-600 focus-within:ring-2 focus-within:ring-brand-500 transition-all duration-300">
                  <span class="inline-flex items-center rounded-l-xl bg-brand-100 px-3 text-slate-700 select-none">+62</span>
                  <input required x-model="formTamu.no_hp" type="tel" class="w-full rounded-r-xl px-3 py-2 focus:outline-none" placeholder="81234567890">
                </div>
                <p class="mt-1 text-xs text-slate-600">Format: 812-3456-7890 (tanpa +62)</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Jumlah Peserta <span class="text-red-500">*</span></label>
                <input required x-model.number="formTamu.jumlah_peserta" type="number" min="1" max="50" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300" placeholder="cth: 10">
                <p class="mt-1 text-xs text-slate-600">Maksimal 50 orang per kunjungan</p>
              </div>
            </div>
          </div>

          <!-- Jadwal -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700 animate-bounceIn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Jadwal Kunjungan</h4>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-800">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.tanggal_kunjungan" type="date" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Waktu Kunjungan <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.waktu_kunjungan" type="time" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3">
            <button type="button" @click="step=2" class="ripple rounded-xl bg-yellow-gradient px-5 py-2 font-semibold text-white shadow-yellow-glow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brand-500">Lanjut</button>
          </div>
        </section>

        <!-- STEP 2 -->
        <section x-show="step===2" x-transition.opacity class="mt-6 space-y-6">
          <!-- Pihak Tujuan -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700 animate-bounceIn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13 7H7v6h6V7z" /><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm10 12H5V5h10v10z" clip-rule="evenodd"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Pihak yang Dituju</h4>
            </div>

            <label class="block text-sm font-medium text-slate-800">Kategori Pihak yang Dituju <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 cursor-pointer hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_kategori" value="Pimpinan" required @change="formTamu.bertemu_sub=''; updateBertemuText()"><span class="text-sm text-slate-800">Pimpinan</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 cursor-pointer hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_kategori" value="AKD" @change="formTamu.bertemu_sub=''; updateBertemuText()"><span class="text-sm text-slate-800">AKD</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 cursor-pointer hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_kategori" value="Sekretariat" @change="formTamu.bertemu_sub=''; updateBertemuText()"><span class="text-sm text-slate-800">Sekretariat</span>
              </label>
            </div>

            <!-- List dinamis -->
            <div class="mt-4 space-y-3" x-show="formTamu.bertemu_kategori==='Pimpinan'">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Ketua DPRD" @change="updateBertemuText()" required><span class="text-slate-800">Ketua DPRD</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Wakil ketua 1" @change="updateBertemuText()"><span class="text-slate-800">Wakil ketua 1</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Wakil ketua 2" @change="updateBertemuText()"><span class="text-slate-800">Wakil ketua 2</span></label>
            </div>

            <div class="mt-4" x-show="formTamu.bertemu_kategori==='AKD'">
              <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Badan Kehormatan" @change="updateBertemuText()" required><span class="text-slate-800">Badan Kehormatan</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Badan Anggaran" @change="updateBertemuText()"><span class="text-slate-800">Badan Anggaran</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Badan Pembentukan Peraturan Daerah" @change="updateBertemuText()"><span class="text-slate-800">Badan Pembentukan Peraturan Daerah</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Badan Musyawarah" @change="updateBertemuText()"><span class="text-slate-800">Badan Musyawarah</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Komisi 1" @change="updateBertemuText()"><span class="text-slate-800">Komisi 1</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Komisi 2" @change="updateBertemuText()"><span class="text-slate-800">Komisi 2</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Komisi 3" @change="updateBertemuText()"><span class="text-slate-800">Komisi 3</span></label>
              </div>
            </div>

            <div class="mt-4 space-y-3" x-show="formTamu.bertemu_kategori==='Sekretariat'">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Sekretaris" @change="updateBertemuText()" required><span class="text-slate-800">Sekretaris</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Bagian Umum dan Humas" @change="updateBertemuText()"><span class="text-slate-800">Bagian Umum dan Humas</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Bagian Keuangan" @change="updateBertemuText()"><span class="text-slate-800">Bagian Keuangan</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300"><input type="radio" x-model="formTamu.bertemu_sub" value="Persidangan dan Perundang-undangan" @change="updateBertemuText()"><span class="text-slate-800">Persidangan dan Perundang-undangan</span></label>
            </div>

            <!-- Gabungan (readonly) -->
            <div class="mt-4">
              <label class="block text-sm font-medium text-slate-800">Bertemu Dengan (otomatis)</label>
              <input x-model="formTamu.bertemu_dengan" type="text" readonly class="mt-1 w-full rounded-xl border border-brand-400 px-3 py-2 bg-brand-100 text-slate-700">
              <p class="text-xs text-slate-600 mt-1">Otomatis dari pilihan kategori & sub-kategori.</p>
            </div>
          </div>

          <!-- Status -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5 shadow-sm">
            <label class="block text-sm font-medium text-slate-800">Status</label>
            <select x-model="formTamu.status_sekarang" class="mt-1 w-full rounded-xl border border-brand-400 px-3 py-2 bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
              <option value="menunggu">Menunggu</option>
              <option value="ditolak">Ditolak</option>
              <option value="diterima">Diterima</option>
              <option value="selesai">Selesai</option>
            </select>
          </div>

          <div class="flex items-center justify-between">
            <button type="button" @click="step=1" class="rounded-xl px-4 py-2 text-slate-800 hover:bg-brand-50 transition-all duration-300">Kembali</button>
            <div class="flex items-center gap-3">
              <button type="button" @click="showAddEditTamu=false" class="rounded-xl px-4 py-2 text-slate-700 bg-[#FFFDF5] hover:bg-brand-50 transition-all duration-300">Batal</button>
              <button type="submit" class="ripple rounded-xl bg-yellow-gradient px-5 py-2 font-semibold text-white shadow-yellow-glow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brand-500">Simpan</button>
            </div>
          </div>
        </section>
      </form>
    </div>
  </div>

  <!-- ============== MODAL: Logout Confirm ============== -->
  <div x-cloak x-show="showLogout" x-transition.opacity class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-[#FFFDF5] rounded-2xl p-6 shadow-2xl animate-scaleIn ring-1 ring-brand-300">
      <div class="flex items-start justify-between">
        <h3 class="text-lg font-bold flex items-center gap-2 text-slate-900">
          <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
          </svg>
          Konfirmasi Logout
        </h3>
        <button class="ripple rounded-lg px-2 py-1 text-slate-500 hover:bg-brand-100/80 transition-all duration-300" @click="showLogout=false">✕</button>
      </div>
      <p class="mt-3 text-slate-700">Yakin untuk logout?</p>
      <div class="mt-6 flex justify-end gap-2">
        <button type="button" class="rounded-xl border border-brand-400 px-4 py-2 font-semibold text-slate-800 bg-[#FFFDF5] hover:bg-brand-100/80 transition-all duration-300" @click="showLogout=false">Tidak</button>
        <button type="button" class="ripple rounded-xl bg-rose-600 px-4 py-2 text-white font-semibold hover:bg-rose-700 transition-all duration-300" @click="doLogout()">Ya, Logout</button>
      </div>
    </div>
  </div>

  <!-- ============== SCRIPTS ============== -->
  <script>
    const CSRF = document.head.querySelector('meta[name="csrf-token"]').content;

    async function api(url, opts = {}) {
      const headers = opts.headers || {};
      if (!(opts.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
      }
      headers['X-Requested-With'] = 'XMLHttpRequest';
      headers['X-CSRF-TOKEN'] = CSRF;

      const res = await fetch(url, { credentials: 'same-origin', ...opts, headers });
      if (!res.ok) {
        const text = await res.text().catch(()=> '');
        throw new Error(`HTTP ${res.status} ${res.statusText}\n${text}`);
      }
      try { return await res.json(); } catch { return {}; }
    }

    function doLogout(){
      const form = document.getElementById('logout-form');
      if(form){ form.submit(); }
    }

    function app(){
      return {
        sidebarOpen:false,
        showAddUser:false,
        showAddEditTamu:false,
        showLogout:false,
        editingUser:false,
        editingTamu:false,

        step:1,

        users:[],
        tamu:[],
        chartData: [], // DATA BARU: khusus untuk chart

        dashboardRange: 7,
        metrics: { totalTamu:0, totalSelesai:0, totalUser:0, todayVisits: 0 },
        statusCounts: { menunggu: 0, diterima: 0, ditolak: 0, selesai: 0 },
        _chart: null,

        // Pagination state (Kelola Tamu)
        currentPage: 1,
        pageSize: 10,

        filter: { status:'', month:'', q:'' },
        filterUser: { role:'', q:'' },

        formUser: { id:null, full_name:'', email:'', password:'', role:'admin' },
        formTamu: {
          id:null, nama:'', email:'', no_hp:'',
          jumlah_peserta:1, instansi_kategori:'', instansi_nama:'',
          tanggal_kunjungan:todayISO(), waktu_kunjungan:'08:00',
          bertemu_kategori:'', bertemu_sub:'', bertemu_dengan:'',
          dokumen:null, dokumen_name:'', status_sekarang:'menunggu',
          tamu_id: null
        },

        async init(){
          try {
            console.log('🚀 Aplikasi diinisialisasi...');
            await this.loadPageData();
            console.log('✅ Init selesai');
          } catch(e) {
            console.error('❌ Init error', e);
          }
        },

        async loadPageData() {
          const path = window.location.pathname;
          
          if (path.includes('users')) {
            await this.loadUsers();
          } else if (path.includes('tamu')) {
            await this.loadKunjungan();
          } else {
            await this.loadDashboardData();
          }
        },

        // PERBAIKAN: Load dashboard data dengan chart data
        async loadDashboardData() {
          try {
            console.log('📊 Loading dashboard data...');
            
            const data = await api(`/admin/api/dashboard-data?range=${this.dashboardRange}`);
            console.log('📊 Data dari API:', data);
            
            this.metrics = data.metrics || {};
            this.statusCounts = data.statusCounts || {};
            this.tamu = data.tamu || [];
            this.chartData = data.chartData || []; // DATA CHART DARI API
            
            console.log('✅ Dashboard data loaded');
            console.log('📈 Metrics:', this.metrics);
            console.log('📊 Status counts:', this.statusCounts);
            console.log('👥 Tamu records:', this.tamu.length);
            console.log('📅 Chart data points:', this.chartData.length);

            await this.loadUsers();
            
            // Inisialisasi chart setelah data tersedia
            this.$nextTick(() => {
              console.log('🔄 DOM ready, initializing chart...');
              setTimeout(() => {
                this.initChart();
              }, 300);
            });
            
          } catch (error) {
            console.error('❌ Error loading dashboard data:', error);
          }
        },

        async loadUsers(){ 
          try {
            this.users = await api('/admin/api/users'); 
          } catch (error) {
            console.error('Error loading users:', error);
            this.users = [];
          }
        },

        async loadKunjungan(){ 
          try {
            this.tamu = await api('/admin/api/kunjungan'); 
          } catch (error) {
            console.error('Error loading kunjungan:', error);
            this.tamu = [];
          }
        },

        computeMetrics(){
          const totalUser = (this.users||[]).length;
          const totalSelesai = (this.tamu||[]).filter(r => (r.status_sekarang||'').toLowerCase()==='selesai').length;
          const uniq = new Set((this.tamu||[]).map(r=>r.tamu_id).filter(Boolean));
          const totalTamu = uniq.size;
          const today = todayISO();
          const todayVisits = (this.tamu||[]).filter(r => r.tanggal_kunjungan === today).length;
          this.metrics = { totalTamu, totalSelesai, totalUser, todayVisits };
        },

        computeStatusCounts(){
          const statuses = ['menunggu', 'diterima', 'ditolak', 'selesai'];
          const counts = {};
          statuses.forEach(status => {
            counts[status] = (this.tamu||[]).filter(r => (r.status_sekarang||'').toLowerCase() === status).length;
          });
          this.statusCounts = counts;
        },

        // PERBAIKAN: Fungsi initChart yang menggunakan data chart dari API
        initChart() {
          try {
            console.log('🔄 Inisialisasi chart...');
            
            // Pastikan kita berada di dashboard
            if (window.location.pathname.includes('users') || window.location.pathname.includes('tamu')) {
              console.log('❌ Bukan di dashboard, skip init chart');
              return;
            }
            
            // Cari canvas
            let canvas = this.$refs.chartHarian;
            if (!canvas) {
              canvas = document.getElementById('chartHarian');
            }
            
            if (!canvas) {
              console.warn('Canvas chart tidak ditemukan di DOM');
              return;
            }

            // Hancurkan chart lama jika ada
            if (this._chart) {
              console.log('🗑️ Hancurkan chart lama');
              this._chart.destroy();
              this._chart = null;
            }

            const ctx = canvas.getContext('2d');
            if (!ctx) {
              console.warn('Tidak bisa mendapatkan context dari canvas');
              return;
            }

            // Gunakan data chart dari API
            const chartData = this.prepareChartDataFromAPI();
            
            console.log('📊 Chart data siap:', chartData);

            // Buat chart baru
            this._chart = new Chart(ctx, {
              type: 'bar',
              data: chartData,
              options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                  legend: {
                    position: 'top',
                    labels: {
                      usePointStyle: true,
                      padding: 15,
                      font: { size: 12 }
                    }
                  },
                  tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                          label += ': ';
                        }
                        if (context.parsed.y !== null) {
                          label += context.parsed.y;
                        }
                        return label;
                      }
                    }
                  }
                },
                scales: {
                  x: {
                    stacked: false,
                    grid: { display: false },
                    ticks: {
                      maxRotation: 45,
                      minRotation: 45
                    }
                  },
                  y: {
                    stacked: false,
                    beginAtZero: true,
                    ticks: { precision: 0 }
                  }
                }
              }
            });
            
            console.log('✅ Chart berhasil diinisialisasi');
          } catch (error) {
            console.error('❌ Error dalam initChart:', error);
          }
        },

        // FUNGSI BARU: Siapkan data chart dari API
        prepareChartDataFromAPI() {
          try {
            console.log('🔄 Menyiapkan data chart dari API...');
            
            if (!this.chartData || this.chartData.length === 0) {
              console.warn('❌ Data chart dari API kosong');
              return this.getFallbackChartData();
            }

            const labels = this.chartData.map(item => item.label);
            
            const datasets = [
              { 
                label: 'Menunggu', 
                data: this.chartData.map(item => item.menunggu || 0), 
                backgroundColor: '#f59e0b', 
                borderColor: '#f59e0b', 
                borderWidth: 1
              },
              { 
                label: 'Diterima', 
                data: this.chartData.map(item => item.diterima || 0), 
                backgroundColor: '#10b981', 
                borderColor: '#10b981', 
                borderWidth: 1
              },
              { 
                label: 'Ditolak', 
                data: this.chartData.map(item => item.ditolak || 0), 
                backgroundColor: '#ef4444', 
                borderColor: '#ef4444', 
                borderWidth: 1
              },
              { 
                label: 'Selesai', 
                data: this.chartData.map(item => item.selesai || 0), 
                backgroundColor: '#64748b', 
                borderColor: '#64748b', 
                borderWidth: 1
              }
            ];

            console.log('📊 Data chart dari API:', {
              labels: labels,
              datasets: datasets.map(d => ({ label: d.label, data: d.data }))
            });

            return {
              labels: labels,
              datasets: datasets
            };
            
          } catch (error) {
            console.error('💥 Error preparing chart data from API:', error);
            return this.getFallbackChartData();
          }
        },

        // Fallback data jika API error
        getFallbackChartData() {
          console.log('🔄 Menggunakan fallback chart data');
          
          const labels = [];
          const today = new Date();
          
          for (let i = this.dashboardRange - 1; i >= 0; i--) {
            const date = new Date(today);
            date.setDate(date.getDate() - i);
            const label = new Intl.DateTimeFormat('id-ID', { 
              day: '2-digit', 
              month: 'short' 
            }).format(date);
            labels.push(label);
          }

          return {
            labels: labels,
            datasets: [
              { label: 'Menunggu', data: Array(this.dashboardRange).fill(0), backgroundColor: '#f59e0b' },
              { label: 'Diterima', data: Array(this.dashboardRange).fill(0), backgroundColor: '#10b981' },
              { label: 'Ditolak', data: Array(this.dashboardRange).fill(0), backgroundColor: '#ef4444' },
              { label: 'Selesai', data: Array(this.dashboardRange).fill(0), backgroundColor: '#64748b' }
            ]
          };
        },

        // PERBAIKAN: Fungsi refreshChart
        async refreshChart() {
          console.log('🔄 RefreshChart dipanggil, range:', this.dashboardRange);
          
          try {
            // Reload data dengan range baru
            await this.loadDashboardData();
            
            console.log('✅ Data di-refresh, chart akan diupdate');
            
          } catch (error) {
            console.error('❌ Error refresh chart:', error);
          }
        },

        // ... (FUNGSI LAINNYA TETAP SAMA)
        openAddUser(){
          this.editingUser = false;
          this.formUser = {
            id: null, 
            full_name: '', 
            email: '', 
            password: '',
            new_password: '',
            no_wa: '',
            role: 'admin',
            is_active: true
          };
          this.showAddUser = true;
        },

        editUser(user){
          this.editingUser = true;
          this.formUser = {
            id: user.id,
            full_name: user.full_name || '',
            email: user.email || '',
            password: '',
            new_password: '',
            no_wa: user.no_wa || '',
            role: user.role || 'admin',
            is_active: user.is_active !== undefined ? user.is_active : true
          };
          this.showAddUser = true;
        },

        async saveUser(){
          try{
            // Validasi password untuk user baru
            if(!this.editingUser){
              const pwd = (this.formUser.password||'');
              if(pwd.length < 8){
                alert('Password minimal 8 karakter.');
                return;
              }
            }

            let url, method;
            
            if(this.editingUser){
              url = `/admin/api/users/${this.formUser.id}`;
              method = 'PUT';
            } else {
              url = '/admin/api/users';
              method = 'POST';
            }

            // PERBAIKAN: Sertakan semua field yang diperlukan
            const payload = {
              full_name: this.formUser.full_name || null,
              email: this.formUser.email,
              role: this.formUser.role,
              no_wa: this.formUser.no_wa || null, // TAMBAHKAN INI
              is_active: this.formUser.is_active // TAMBAHKAN INI
            };

            // Tambahkan password jika ada
            if (this.editingUser && this.formUser.new_password) {
              payload.password = this.formUser.new_password;
            } else if (!this.editingUser) {
              payload.password = this.formUser.password;
            }

            console.log('📤 Mengirim data user:', payload); // Debug log

            const response = await api(url, {
              method: method,
              body: JSON.stringify(payload)
            });

            console.log('📥 Response dari server:', response); // Debug log

            this.showAddUser=false;
            await this.loadUsers();
            this.computeMetrics();
            if(this._chart) this.refreshChart();
            
            alert(this.editingUser ? 'User berhasil diperbarui!' : 'User berhasil ditambahkan!');
          }catch(e){ 
            console.error('❌ Error saveUser:', e); // Debug log
            alert('Gagal menyimpan user:\n\n' + e.message); 
          }
        },

        normalized(v){ return (v||'').toString().toLowerCase().trim(); },
        
        filteredUsers(){
          const role = this.normalized(this.filterUser.role);
          const q    = this.normalized(this.filterUser.q);
          return (this.users||[]).filter(u=>{
            const matchRole = role ? this.normalized(u.role) === role : true;
            const name = this.normalized(u.full_name || '');
            const matchName = q ? name.includes(q) : true;
            return matchRole && matchName;
          });
        },

        openAddTamu(){
          this.editingTamu=false;
          this.step=1;
          this.currentPage = 1;
          this.formTamu={
            id:null, nama:'', email:'', no_hp:'',
            jumlah_peserta:1, instansi_kategori:'', instansi_nama:'',
            tanggal_kunjungan:todayISO(), waktu_kunjungan:'08:00',
            bertemu_kategori:'', bertemu_sub:'', bertemu_dengan:'',
            dokumen:null, dokumen_name:'', status_sekarang:'menunggu',
            tamu_id: null
          };
          this.showAddEditTamu=true;
        },

        editTamu(row){
          this.editingTamu=true;
          this.step=1;

          this.formTamu = {
            id: row.id,
            nama: row.nama || '',
            email: row.email || '',
            no_hp: row.no_hp || '',
            jumlah_peserta: row.jumlah_peserta || 1,
            instansi_kategori: row.instansi_kategori || '',
            instansi_nama: row.instansi_nama || '',
            tanggal_kunjungan: row.tanggal_kunjungan || todayISO(),
            waktu_kunjungan: row.waktu_kunjungan || '08:00',
            bertemu_kategori: row.bertemu_kategori || '',
            bertemu_sub: row.bertemu_sub || '',
            bertemu_dengan: row.bertemu_dengan || '',
            dokumen: null,
            dokumen_name: '',
            status_sekarang: row.status_sekarang || 'menunggu',
            tamu_id: row.tamu_id || null
          };

          this.showAddEditTamu=true;
          
          this.$nextTick(() => {
            this.updateBertemuText();
          });
        },

        updateBertemuText(){
          const k = (this.formTamu.bertemu_kategori||'').trim();
          const s = (this.formTamu.bertemu_sub||'').trim();
          this.formTamu.bertemu_dengan = k && s ? `${k} - ${s}` : (k || s || '');
        },

        async saveTamu(){
          try{
            if (!this.formTamu.nama || !this.formTamu.email || !this.formTamu.no_hp) {
              alert('Nama, email, dan nomor HP wajib diisi!');
              return;
            }

            this.updateBertemuText();

            const fd = new FormData();
            
            fd.append('nama', this.formTamu.nama);
            fd.append('email', this.formTamu.email);
            fd.append('no_hp', this.formTamu.no_hp);
            if (this.formTamu.instansi_kategori) fd.append('instansi_kategori', this.formTamu.instansi_kategori);
            if (this.formTamu.instansi_nama) fd.append('instansi_nama', this.formTamu.instansi_nama);

            fd.append('jumlah_peserta', this.formTamu.jumlah_peserta);
            fd.append('tanggal_kunjungan', this.formTamu.tanggal_kunjungan);
            fd.append('waktu_kunjungan', this.formTamu.waktu_kunjungan);
            fd.append('status_sekarang', this.formTamu.status_sekarang);

            fd.append('bertemu_kategori', this.formTamu.bertemu_kategori || '');
            fd.append('bertemu_sub', this.formTamu.bertemu_sub || '');

            if (this.formTamu.dokumen instanceof File) {
              fd.append('dokumen', this.formTamu.dokumen);
            }

            if (this.editingTamu && this.formTamu.tamu_id) {
              fd.append('tamu_id', this.formTamu.tamu_id);
            }

            let url = '/admin/api/kunjungan';
            let method = 'POST';

            if (this.editingTamu && this.formTamu.id) {
              method = 'POST';
              fd.append('_method', 'PUT');
              url = `/admin/api/kunjungan/${this.formTamu.id}`;
            }

            const res = await fetch(url, {
              method: method,
              headers: { 
                'X-CSRF-TOKEN': CSRF, 
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: fd,
              credentials: 'same-origin'
            });

            if(!res.ok){
              const msg = await res.text().catch(()=> '');
              console.error('Response error:', res.status, msg);
              throw new Error(`HTTP ${res.status} ${res.statusText}\n${msg}`);
            }

            const result = await res.json();
            
            this.showAddEditTamu=false;
            await this.loadKunjungan();
            this.computeMetrics();
            this.computeStatusCounts();
            if(this._chart) this.refreshChart();
            this.currentPage = 1;
            
            alert(this.editingTamu ? 'Data tamu berhasil diperbarui!' : 'Data tamu berhasil ditambahkan!');
            
          }catch(e){
            console.error('Error saveTamu:', e);
            alert('Gagal menyimpan data tamu: ' + e.message);
          }
        },

        async confirmDelete(type, item){
          const ok = confirm(`Yakin hapus ${type==='user'?'user':'tamu'} ini?`);
          if(!ok) return;
          try{
            if(type==='user'){
              await api(`/admin/api/users/${item.id}`, { method:'DELETE' });
              await this.loadUsers();
              this.computeMetrics();
              if(this._chart) this.refreshChart();
            }else{
              await api(`/admin/api/kunjungan/${item.id}`, { method:'DELETE' });
              await this.loadKunjungan();
              this.computeMetrics();
              this.computeStatusCounts();
              if(this._chart) this.refreshChart();
              const total = this.filteredTamu().length;
              const maxPage = total === 0 ? 1 : Math.ceil(total / this.pageSize);
              if(this.currentPage > maxPage) this.currentPage = maxPage;
            }
            
            alert(`${type==='user'?'User':'Tamu'} berhasil dihapus!`);
          }catch(e){ 
            alert('Gagal menghapus:\n\n' + e.message); 
            console.error(e); 
          }
        },

        onDokumenChange(e){
          const f = e.target.files?.[0];
          if(!f) return;
          this.formTamu.dokumen = f;
          this.formTamu.dokumen_name = f.name;
        },

        resetFilters(){
          this.filter = { status:'', month:'', q:'' };
          this.currentPage = 1;
        },

        matchesSearch(row){
          const q = this.normalized(this.filter.q);
          if(!q) return true;
          const hay = [
            row.nama, row.email, row.instansi_nama, row.no_hp, row.bertemu_dengan
          ].map(v=>this.normalized(v)).join(' ');
          return hay.includes(q);
        },
        matchesStatus(row){
          if(!this.filter.status) return true;
          return (row.status_sekarang||'').toString().toLowerCase() === this.filter.status;
        },
        matchesMonth(row){
          if(!this.filter.month) return true;
          return (row.tanggal_kunjungan||'').startsWith(this.filter.month);
        },

        filteredTamu(){
          const all = (this.tamu||[]).filter(row =>
            this.matchesStatus(row) && this.matchesMonth(row) && this.matchesSearch(row)
          );
          const total = all.length;
          const maxPage = total === 0 ? 1 : Math.ceil(total / this.pageSize);
          if(this.currentPage > maxPage) this.currentPage = maxPage;
          if(this.currentPage < 1) this.currentPage = 1;
          return all;
        },
        paginatedTamu(){
          const all = this.filteredTamu();
          const start = (this.currentPage - 1) * this.pageSize;
          return all.slice(start, start + this.pageSize);
        },
        totalPages(){
          const total = this.filteredTamu().length;
          return total === 0 ? 1 : Math.ceil(total / this.pageSize);
        },
        goToPage(page){
          const total = this.totalPages();
          const p = Math.min(Math.max(1, page), total);
          this.currentPage = p;
        },
        nextPage(){
          this.goToPage(this.currentPage + 1);
        },
        prevPage(){
          this.goToPage(this.currentPage - 1);
        },
        pageInfoStart(){
          const total = this.filteredTamu().length;
          if(total === 0) return 0;
          return (this.currentPage - 1) * this.pageSize + 1;
        },
        pageInfoEnd(){
          const total = this.filteredTamu().length;
          if(total === 0) return 0;
          return Math.min(this.currentPage * this.pageSize, total);
        },

        formatDate(d){
          try{ 
            const dt = new Date(d+'T00:00:00'); 
            return new Intl.DateTimeFormat('id-ID',{day:'2-digit',month:'short',year:'numeric'}).format(dt); 
          }catch{ return d; }
        },
        formatTime(t){ return (t||'00:00').toString().slice(0,5); },
        badgeClass(st){
          const map={
            menunggu: 'bg-amber-50 ring-amber-200 text-amber-700',
            ditolak : 'bg-rose-50 ring-rose-200 text-rose-700',
            diterima: 'bg-emerald-50 ring-emerald-200 text-emerald-700',
            selesai : 'bg-slate-50 ring-slate-200 text-slate-700',
          };
          return map[st]||'bg-slate-50 ring-slate-200 text-slate-700';
        },
        labelStatus(st){ 
          const m={
            menunggu:'Menunggu',
            diterima:'Diterima',
            ditolak:'Ditolak',
            selesai:'Selesai'
          }; 
          return m[st]||String(st).charAt(0).toUpperCase()+String(st).slice(1); 
        },
      }
    }

    const TZ='Asia/Makassar';
    function todayISO(){ 
      const d=new Date(); 
      const y=d.getFullYear(), 
            m=String(d.getMonth()+1).padStart(2,'0'), 
            dd=String(d.getDate()).padStart(2,'0'); 
      return `${y}-${m}-${dd}`; 
    }

    function updateClock(){
      const now = new Date();
      const time = new Intl.DateTimeFormat('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false,timeZone:TZ}).format(now);
      const date = new Intl.DateTimeFormat('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric',timeZone:TZ}).format(now);
      const clockEl = document.getElementById('clock');
      const dateEl  = document.getElementById('date');
      if(clockEl) clockEl.textContent = time + ' WITA';
      if(dateEl)  dateEl.textContent  = date.charAt(0).toUpperCase()+date.slice(1);
    }
    document.addEventListener('alpine:init', ()=>{ updateClock(); setInterval(updateClock, 1000); });

    const topbarObserver = ()=>{
      const topbar = document.getElementById('topbar');
      function setShadow(){
        if (window.scrollY > 6) topbar.classList.add('shadow','bg-brand-200/95');
        else topbar.classList.remove('shadow','bg-brand-200/95');
      }
      setShadow(); window.addEventListener('scroll', setShadow, { passive:true });
    }
    window.addEventListener('load', topbarObserver, { once:true });

    document.addEventListener('click', function(e){
      const target = e.target.closest('.ripple'); if(!target) return;
      const rect = target.getBoundingClientRect();
      const span = document.createElement('span');
      span.className = 'rp';
      span.style.left = (e.clientX - rect.left) + 'px';
      span.style.top  = (e.clientY - rect.top)  + 'px';
      target.appendChild(span);
      setTimeout(()=>span.remove(), 600);
    });
  </script>
</body>
</html>