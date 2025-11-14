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
            night: { 900:'#0B1220', 800:'#0F1930' }
          },
          boxShadow: {
            soft: '0 10px 30px -12px rgba(0,0,0,.12)',
            lift: '0 18px 40px -20px rgba(0,0,0,.25)',
            'yellow-glow': '0 0 20px rgba(255, 179, 0, 0.3)'
          },
          backgroundImage: {
            'yellow-noise': "radial-gradient(1200px 600px at -10% -20%, rgba(255, 227, 140, .35), transparent 60%), radial-gradient(800px 500px at 110% 120%, rgba(255, 214, 102, .28), transparent 60%)",
            'yellow-gradient': 'linear-gradient(135deg, #FFB300 0%, #FFCA3A 100%)',
            'gold-gradient': 'linear-gradient(135deg, #FFB300 0%, #E6A100 100%)'
          },
          keyframes: {
            fadeUp:   { '0%': { opacity:0, transform:'translateY(12px)'}, '100%': { opacity:1, transform:'translateY(0)'} },
            scaleIn:  { '0%': { opacity:0, transform:'scale(.96)'},      '100%': { opacity:1, transform:'scale(1)'} },
            pulseSoft:{ '0%,100%': { transform:'scale(1)'}, '50%': { transform:'scale(1.05)' } },
            floaty:   { '0%,100%': { transform:'translateY(0)'}, '50%': { transform:'translateY(-6px)' } },
            shimmer:  { '0%': { backgroundPosition:'-200% 0' }, '100%': { backgroundPosition:'200% 0' } },
            bounceIn: { '0%': { opacity:0, transform:'scale(0.3)' }, '50%': { opacity:1, transform:'scale(1.05)' }, '70%': { transform:'scale(0.9)' }, '100%': { opacity:1, transform:'scale(1)' } }
          },
          animation: {
            fadeUp:'fadeUp .45s ease-out both',
            scaleIn:'scaleIn .28s ease-out both',
            pulseSoft:'pulseSoft 2s ease-in-out infinite',
            floaty:'floaty 6s ease-in-out infinite',
            shimmer:'shimmer 2.5s linear infinite',
            bounceIn:'bounceIn 0.6s ease-out both'
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
    <div class="w-full h-full rounded-none border-r border-brand-200 bg-gradient-to-b from-brand-100 via-brand-50 to-brand-100 backdrop-blur-sm shadow-soft p-4 flex flex-col">
      <div class="flex items-center gap-3 mb-4 p-2 rounded-xl bg-yellow-gradient shadow-yellow-glow">
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
        <button @click="active='dashboard'" :class="active==='dashboard' ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-100/70 hover:text-brand-800'"
                class="w-full text-left px-3 py-2 rounded-xl transition">
          <span class="inline-flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 12l9-9 9 9v8a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-8z"/></svg>
            Dashboard
          </span>
        </button>
        <button @click="active='users'" :class="active==='users' ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-100/70 hover:text-brand-800'"
                class="w-full text-left px-3 py-2 rounded-xl transition">
          <span class="inline-flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm7 9a1 1 0 01-1 1H6a1 1 0 01-1-1 7 7 0 0114 0z"/></svg>
            Kelola Pengguna
          </span>
        </button>
        <button @click="active='tamu'" :class="active==='tamu' ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-100/70 hover:text-brand-800'"
                class="w-full text-left px-3 py-2 rounded-xl transition">
          <span class="inline-flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19a1 1 0 001 1h10v-2.5C12 14.17 6.33 13 4 13zm8 6h6a1 1 0 001-1v-1.5c0-2.33-4.67-3.5-7-3.5-1.05 0-2.04.12-2.93.33A6.51 6.51 0 0116 19z"/></svg>
            Kelola Tamu
          </span>
        </button>
      </nav>
      <div class="mt-auto pt-3">
        <button @click="showLogout=true"
                class="w-full text-left px-3 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 ring-1 ring-rose-200 transition">
          <span class="inline-flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8a2 2 0 00-2 2v4h2V5h8v14h-8v-4h-2v4a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2z"/></svg>
            Logout
          </span>
        </button>
      </div>
    </div>
  </aside>

  <!-- ================= NAVBAR (TOP) ================= -->
  <header id="topbar" class="fixed inset-x-0 top-0 z-30 bg-brand-100/80 backdrop-blur-md transition-all md:pl-72">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between md:justify-end py-3">
        <div class="flex items-center gap-3 md:hidden">
          <button class="-ml-2 p-2 rounded-lg hover:bg-brand-200/70" @click="sidebarOpen=true" aria-label="Open sidebar">
            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <a href="#" class="flex items-center gap-3" @click.prevent="scrollTo({top:0,behavior:'smooth'})">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-gradient text-white font-bold shadow-yellow-glow">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l1.8 4.9L19 9l-5.2 2.1L12 16l-1.8-4.9L5 9l5.2-2.1L12 2z"/></svg>
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
            <button class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-brand-200/80" @click="open=!open" :aria-expanded="open">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-yellow-gradient text-white ring-1 ring-brand-300 shadow-yellow-glow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.761 0 5-2.462 5-5.5S14.761 1 12 1 7 3.462 7 6.5 9.239 12 12 12zm0 2c-3.866 0-7 2.582-7 5.769V22h14v-2.231C19 16.582 15.866 14 12 14z"/></svg>
              </span>
              <span class="text-sm hidden sm:inline text-slate-800">Admin</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>

            <div x-cloak x-show="open" x-transition.origin.top.right @click.outside="open=false"
                 class="absolute right-0 mt-2 w-52 rounded-xl border border-brand-200 bg-[#FFFDF5] shadow-lg overflow-hidden">
              <div class="py-1">
                <button type="button" class="w-full text-left px-4 py-2 text-sm hover:bg-brand-50/80 flex items-center gap-2" @click="open=false; showLogout=true">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4.5A1.5 1.5 0 014.5 3h5A1.5 1.5 0 0111 4.5v1a.5.5 0 01-1 0v-1a.5.5 0 00-.5-.5h-5a.5.5 0 00-.5.5v11a.5.5 0 00.5.5h5a2 2 0 002-2v-1a.5.5 0 011 0v1A1.5 1.5 0 019.5 17h-5A1.5 1.5 0 013 15.5v-11z" clip-rule="evenodd"/><path d="M13.854 10.354a.5.5 0 010-.708l2-2a.5.5 0 11.707.708L15.707 9.5H8.5a.5.5 0 010-1h7.207l.854-.854a.5.5 0 11.707.707l-2 2a.5.5 0 01-.707 0z"/></svg>
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
              <button class="p-2 rounded-lg hover:bg-brand-200/80" @click="sidebarOpen=false">✕</button>
            </div>
            <nav class="space-y-1">
              <button @click="active='dashboard'; sidebarOpen=false" :class="active==='dashboard' ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-100/70 hover:text-brand-800'"
                      class="w-full text-left px-3 py-2 rounded-xl transition">Dashboard</button>
              <button @click="active='users'; sidebarOpen=false" :class="active==='users' ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-100/70 hover:text-brand-800'"
                      class="w-full text-left px-3 py-2 rounded-xl transition">Kelola Pengguna</button>
              <button @click="active='tamu'; sidebarOpen=false" :class="active==='tamu' ? 'bg-brand-500 text-white shadow-yellow-glow' : 'hover:bg-brand-100/70 hover:text-brand-800'"
                      class="w-full text-left px-3 py-2 rounded-xl transition">Kelola Tamu</button>
            </nav>
            <div class="mt-6">
              <button @click="showLogout=true; sidebarOpen=false"
                      class="w-full text-left px-3 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 ring-1 ring-rose-200 transition">
                Logout
              </button>
            </div>
          </aside>
        </div>

        <!-- ============== MAIN CONTENT ============== -->
        <main class="flex-1 w-full space-y-8">

          <!-- HERO -->
          <section class="pt-2 pb-2 rounded-2xl border border-brand-200 relative overflow-hidden animate-fadeUp bg-gradient-to-br from-brand-100 via-[#FFFDF5] to-brand-100 shadow-soft">
            <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full border border-brand-300/60 animate-floaty"></div>
            <div class="pointer-events-none absolute -right-20 -bottom-24 h-80 w-80 rounded-full border-4 border-brand-200/70 animate-floaty" style="animation-delay:.8s"></div>
            <div class="absolute inset-x-0 top-0 h-1 shimmer animate-shimmer"></div>
            <div class="px-5 py-5">
              <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-2">
                <svg class="h-6 w-6 text-brand-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l1.8 4.9L19 9l-5.2 2.1L12 16l-1.8-4.9L5 9l5.2-2.1L12 2z"/></svg>
                Panel Admin
              </h1>
              <p class="text-slate-700 mt-1">Kelola pengguna & seluruh data tamu DPRD Kota Gorontalo.</p>
            </div>
          </section>

          <!-- ========= DASHBOARD ========= -->
          <section x-cloak x-show="active==='dashboard'" x-transition>
            <div class="rounded-2xl border border-brand-200 bg-brand-50/80 backdrop-blur p-5 shadow-soft space-y-6">
              <!-- Toolbar kecil: rentang hari -->
              <div class="flex flex-wrap items-center gap-3 justify-between">
                <h2 class="text-lg font-bold flex items-center gap-2 text-slate-900">
                  <svg class="h-5 w-5 text-brand-500" viewBox="0 0 24 24" fill="currentColor"><path d="M3 12l9-9 9 9v8a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-8z"/></svg>
                  Dashboard
                </h2>
                <div class="flex items-center gap-2">
                  <label class="text-sm text-slate-700">Rentang:</label>
                  <select x-model.number="dashboardRange"
                          @change="refreshChart()"
                          class="rounded-xl border border-brand-300 px-3 py-2 text-sm bg-[#FFFDF5] focus:border-brand-500 focus:ring-brand-400 focus:ring-2">
                    <option :value="7">7 hari</option>
                    <option :value="14">14 hari</option>
                    <option :value="30">30 hari</option>
                  </select>
                </div>
              </div>

              <!-- Kartu Metrik -->
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-2xl border border-brand-300 bg-yellow-gradient p-5 shadow-yellow-glow text-white">
                  <div class="text-sm font-medium">Total Tamu</div>
                  <div class="mt-1 text-3xl font-extrabold" x-text="metrics.totalTamu"></div>
                  <div class="mt-2 text-xs flex items-center gap-1">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19a1 1 0 001 1h10v-2.5C12 14.17 6.33 13 4 13zm8 6h6a1 1 0 001-1v-1.5c0-2.33-4.67-3.5-7-3.5-1.05 0-2.04.12-2.93.33A6.51 6.51 0 0116 19z"/></svg>
                    <span>Jumlah tamu</span>
                  </div>
                </div>
                <div class="rounded-2xl border border-brand-300 bg-yellow-gradient p-5 shadow-yellow-glow text-white">
                  <div class="text-sm font-medium">Total Tamu Selesai</div>
                  <div class="mt-1 text-3xl font-extrabold" x-text="metrics.totalSelesai"></div>
                  <div class="mt-2 text-xs flex items-center gap-1">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    <span>Kunjungan selesai</span>
                  </div>
                </div>
                <div class="rounded-2xl border border-brand-300 bg-yellow-gradient p-5 shadow-yellow-glow text-white">
                  <div class="text-sm font-medium">Total User</div>
                  <div class="mt-1 text-3xl font-extrabold" x-text="metrics.totalUser"></div>
                  <div class="mt-2 text-xs flex items-center gap-1">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm7 9a1 1 0 01-1 1H6a1 1 0 01-1-1 7 7 0 0114 0z"/></svg>
                    <span>Jumlah pengguna</span>
                  </div>
                </div>
                <div class="rounded-2xl border border-brand-300 bg-yellow-gradient p-5 shadow-yellow-glow text-white">
                  <div class="text-sm font-medium">Kunjungan Hari Ini</div>
                  <div class="mt-1 text-3xl font-extrabold" x-text="metrics.todayVisits"></div>
                  <div class="mt-2 text-xs flex items-center gap-1">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Kunjungan hari ini</span>
                  </div>
                </div>
              </div>

              <!-- Grafik Perbandingan Tamu per Hari -->
              <div class="rounded-2xl border border-brand-300 bg-[#FFF9EC] p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                  <h3 class="font-semibold text-slate-900">Perbandingan Tamu per Hari (berdasarkan status)</h3>
                </div>
                <div class="h-80">
                  <canvas id="chartHarian" x-ref="chartHarian" class="h-full w-full"></canvas>
                </div>
              </div>

              <!-- Statistik Status Tamu -->
              <div class="rounded-2xl border border-brand-300 bg-[#FFF9EC] p-5 shadow-sm">
                <h3 class="font-semibold text-slate-900 mb-4">Statistik Status Tamu</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                  <div class="rounded-xl bg-amber-50 p-4 text-center border border-amber-200">
                    <div class="text-2xl font-bold text-amber-700" x-text="statusCounts.menunggu"></div>
                    <div class="text-sm text-amber-800">Menunggu</div>
                  </div>
                  <div class="rounded-xl bg-emerald-50 p-4 text-center border border-emerald-200">
                    <div class="text-2xl font-bold text-emerald-700" x-text="statusCounts.diterima"></div>
                    <div class="text-sm text-emerald-800">Diterima</div>
                  </div>
                  <div class="rounded-xl bg-rose-50 p-4 text-center border border-rose-200">
                    <div class="text-2xl font-bold text-rose-700" x-text="statusCounts.ditolak"></div>
                    <div class="text-sm text-rose-800">Ditolak</div>
                  </div>
                  <div class="rounded-xl bg-slate-50 p-4 text-center border border-slate-200">
                    <div class="text-2xl font-bold text-slate-700" x-text="statusCounts.selesai"></div>
                    <div class="text-sm text-slate-800">Selesai</div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- ========= KELOLA PENGGUNA ========= -->
          <section x-cloak x-show="active==='users'" x-transition>
            <div class="rounded-2xl border border-brand-200/80 bg-brand-50/80 backdrop-blur p-5 shadow-soft">
              <!-- Toolbar -->
              <div class="flex flex-wrap items-center gap-2 justify-between mb-4">
                <h2 class="text-lg font-bold flex items-center gap-2 text-slate-900">
                  <svg class="h-5 w-5 text-brand-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm7 9a1 1 0 01-1 1H6a1 1 0 01-1-1 7 7 0 0114 0z"/></svg>
                  Kelola Pengguna
                </h2>

                <div class="flex flex-wrap items-center gap-2 ml-auto mobile-stack">
                  <input type="text" placeholder="Cari nama…"
                         x-model.debounce.300ms="filterUser.q"
                         class="w-full md:w-56 rounded-xl border border-brand-300 px-3 py-2 text-sm bg-[#FFFDF5] focus:border-brand-500 focus:ring-brand-400 focus:ring-2"
                         title="Cari Nama">

                  <select x-model="filterUser.role"
                          class="w-full md:w-40 rounded-xl border border-brand-300 px-3 py-2 text-sm bg-[#FFFDF5] focus:border-brand-500 focus:ring-brand-400 focus:ring-2"
                          title="Filter Role">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="resepsionis">Resepsionis</option>
                    <option value="host">Host</option>
                  </select>

                  <button type="button"
                          class="w-full md:w-auto px-3 py-2 text-sm rounded-lg border border-brand-300 bg-[#FFFDF5] hover:bg-brand-100/80"
                          @click="filterUser={role:'', q:''}">
                    Reset
                  </button>

                  <button @click="openAddUser()"
                          class="ripple w-full md:w-auto rounded-xl bg-yellow-gradient px-4 py-2 text-white font-semibold shadow-yellow-glow hover:shadow-lift">
                    Tambah User
                  </button>
                </div>
              </div>

              <!-- Tabel pengguna -->
              <div class="overflow-x-auto scrollbar-thin">
                <table class="min-w-[820px] w-full text-sm">
                  <thead class="bg-brand-100">
                    <tr class="text-left text-slate-700">
                      <th class="py-3 px-4">Nama</th>
                      <th class="py-3 px-4">Email</th>
                      <th class="py-3 px-4">Role</th>
                      <th class="py-3 px-4">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-brand-200">
                    <template x-for="u in filteredUsers()" :key="u.id">
                      <tr class="bg-[#FFFDF5]">
                        <td class="py-3 px-4 font-medium truncate text-slate-900" x-text="u.full_name || '—'"></td>
                        <td class="py-3 px-4 truncate text-slate-800" x-text="u.email"></td>
                        <td class="py-3 px-4 uppercase text-slate-700" x-text="u.role"></td>
                        <td class="py-3 px-4">
                          <div class="flex flex-wrap gap-2">
                            <button @click="editUser(u)" class="rounded-lg bg-brand-100 px-3 py-1.5 text-xs text-slate-800 hover:bg-brand-200 hover:text-brand-800">Edit</button>
                            <button @click="confirmDelete('user', u)" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs text-rose-700 hover:bg-rose-200">Hapus</button>
                          </div>
                        </td>
                      </tr>
                    </template>
                    <tr x-show="filteredUsers().length===0">
                      <td colspan="4" class="py-8 px-4 text-center text-slate-600">Belum ada pengguna sesuai filter.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <!-- ========= KELOLA TAMU ========= -->
          <section x-cloak x-show="active==='tamu'" x-transition>
            <div class="rounded-2xl border border-brand-200/80 bg-brand-50/80 backdrop-blur p-5 shadow-soft">

              <!-- Toolbar -->
              <div class="flex flex-wrap items-center gap-2 justify-between mb-4">
                <h2 class="text-lg font-bold flex items-center gap-2 text-slate-900">
                  <svg class="h-5 w-5 text-brand-500" viewBox="0 0 24 24" fill="currentColor"><path d="M19 7H5a2 2 0 00-2 2v7h18V9a2 2 0 00-2-2zM3 18h18v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1z"/></svg>
                  Kelola Tamu
                </h2>

                <div class="flex flex-wrap items-center gap-2 ml-auto mobile-stack">
                  <span class="text-sm text-slate-700 whitespace-nowrap">
                    <span class="font-semibold text-brand-800" x-text="filteredTamu().length"></span> hasil
                  </span>

                  <select x-model="filter.status"
                          class="w-full md:w-36 rounded-xl border border-brand-300 px-3 py-2 text-sm bg-[#FFFDF5] focus:border-brand-500 focus:ring-brand-400 focus:ring-2"
                          title="Filter Status">
                    <option value="">Semua Status</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="diterima">Diterima</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="selesai">Selesai</option>
                  </select>

                  <input type="month" x-model="filter.month"
                         class="w-full md:w-40 rounded-xl border border-brand-300 px-3 py-2 text-sm bg-[#FFFDF5] focus:border-brand-500 focus:ring-brand-400 focus:ring-2"
                         title="Filter Bulan">

                  <input type="text" placeholder="Cari…"
                         x-model.debounce.300ms="filter.q"
                         class="w-full md:w-56 rounded-xl border border-brand-300 px-3 py-2 text-sm bg-[#FFFDF5] focus:border-brand-500 focus:ring-brand-400 focus:ring-2"
                         title="Cari Nama/Email/Instansi/HP/Bertemu">

                  <button type="button"
                          class="w-full md:w-auto px-3 py-2 text-sm rounded-lg border border-brand-300 bg-[#FFFDF5] hover:bg-brand-100/80"
                          @click="resetFilters()">
                    Reset
                  </button>

                  <button @click="openAddTamu()" 
                          class="ripple w-full md:w-auto rounded-xl bg-yellow-gradient px-4 py-2 text-white text-sm font-semibold whitespace-nowrap shadow-yellow-glow hover:shadow-lift">
                    Tambah Tamu
                  </button>
                </div>
              </div>

              <!-- Tabel + Pagination -->
              <div class="overflow-x-auto scrollbar-thin relative">
                <table class="min-w-[1700px] w-full text-sm">
                  <thead class="sticky top-0 z-20 bg-brand-100 shadow">
                    <tr class="text-left text-slate-700">
                      <th class="py-3 px-5">Nama</th>
                      <th class="py-3 px-5">Email</th>
                      <th class="py-3 px-5">Nomor HP</th>
                      <th class="py-3 px-5 text-center">Jumlah Peserta</th>
                      <th class="py-3 px-5">Kategori Instansi</th>
                      <th class="py-3 px-5">Nama Instansi</th>
                      <th class="py-3 px-5">Tanggal Berkunjung</th>
                      <th class="py-3 px-5">Waktu Berkunjung</th>
                      <th class="py-3 px-5 text-center">Status</th>
                      <th class="py-3 px-5">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="align-top divide-y divide-brand-200">
                    <!-- PAGINATED DATA -->
                    <template x-for="row in paginatedTamu()" :key="row.id">
                      <tr class="bg-[#FFFDF5]">
                        <td class="py-3 px-5 font-medium text-slate-900" x-text="row.nama || '—'"></td>
                        <td class="py-3 px-5 truncate text-slate-800" x-text="row.email || '—'"></td>
                        <td class="py-3 px-5 whitespace-nowrap text-slate-800" x-text="row.no_hp || '—'"></td>
                        <td class="py-3 px-5 text-center text-slate-900" x-text="Intl.NumberFormat('id').format(row.jumlah_peserta || 0)"></td>
                        <td class="py-3 px-5 uppercase whitespace-nowrap text-slate-700" x-text="row.instansi_kategori || '—'"></td>
                        <td class="py-3 px-5 text-slate-800" x-text="row.instansi_nama || '—'"></td>
                        <td class="py-3 px-5 whitespace-nowrap text-slate-800" x-text="formatDate(row.tanggal_kunjungan)"></td>
                        <td class="py-3 px-5 whitespace-nowrap text-slate-800" x-text="formatTime(row.waktu_kunjungan) + ' WITA'"></td>
                        <td class="py-3 px-5 text-center">
                          <span class="inline-flex rounded-full px-2 py-0.5 text-xs ring-1 whitespace-nowrap" :class="badgeClass(row.status_sekarang)"><span x-text="labelStatus(row.status_sekarang)"></span></span>
                        </td>
                        <td class="py-3 px-5">
                          <div class="flex flex-wrap gap-2">
                            <button @click="editTamu(row)" class="rounded-lg bg-brand-100 px-3 py-1.5 text-xs text-slate-800 hover:bg-brand-200 hover:text-brand-800">Edit</button>
                            <button @click="confirmDelete('tamu', row)" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs text-rose-700 hover:bg-rose-200">Hapus</button>
                          </div>
                        </td>
                      </tr>
                    </template>
                    <tr x-show="filteredTamu().length===0"><td colspan="12" class="py-8 px-5 text-center text-slate-600">Tidak ada data tamu sesuai filter.</td></tr>
                  </tbody>
                </table>
              </div>

              <!-- Pagination Controls -->
              <div class="flex flex-wrap items-center justify-between mt-4 text-sm text-slate-700" x-show="filteredTamu().length > 0">
                <div class="mb-2 sm:mb-0">
                  Menampilkan
                  <span class="font-semibold" x-text="pageInfoStart()"></span>
                  –
                  <span class="font-semibold" x-text="pageInfoEnd()"></span>
                  dari
                  <span class="font-semibold" x-text="filteredTamu().length"></span>
                  data
                </div>
                <div class="flex items-center gap-1">
                  <button
                    type="button"
                    class="px-3 py-1.5 rounded-lg border border-brand-300 bg-[#FFFDF5] hover:bg-brand-100/80 disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="prevPage()"
                    :disabled="currentPage === 1"
                  >
                    ‹
                  </button>
                  <template x-for="page in totalPages()" :key="page">
                    <button
                      type="button"
                      class="px-3 py-1.5 rounded-lg border text-xs"
                      :class="page === currentPage
                        ? 'bg-brand-500 border-brand-500 text-white shadow-yellow-glow'
                        : 'bg-[#FFFDF5] border-brand-300 text-slate-800 hover:bg-brand-100/80'"
                      @click="goToPage(page)"
                      x-text="page"
                    ></button>
                  </template>
                  <button
                    type="button"
                    class="px-3 py-1.5 rounded-lg border border-brand-300 bg-[#FFFDF5] hover:bg-brand-100/80 disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="nextPage()"
                    :disabled="currentPage === totalPages()"
                  >
                    ›
                  </button>
                </div>
              </div>
            </div>
          </section>

        </main>
      </div>
    </div>
  </div>

  <!-- ============== MODAL: Tambah User ============== -->
  <div x-cloak x-show="showAddUser" x-transition.opacity class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm grid place-items-center p-4">
    <div class="w-full max-w-lg bg-[#FFFDF5] rounded-2xl p-6 shadow-2xl animate-scaleIn ring-1 ring-brand-200">
      <div class="flex items-start justify-between">
        <h3 class="text-lg font-bold flex items-center gap-2 text-slate-900">
          <svg class="h-5 w-5 text-brand-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm7 9a1 1 0 01-1 1H6a1 1 0 01-1-1 7 7 0 0114 0z"/></svg>
          <span x-text="editingUser ? 'Edit User' : 'Tambah User'"></span>
        </h3>
        <button class="ripple rounded-lg px-2 py-1 text-slate-500 hover:bg-brand-100/80" @click="showAddUser=false">✕</button>
      </div>

      <form class="mt-4 space-y-4" @submit.prevent="saveUser">
        <div>
          <label class="block text-sm font-medium text-slate-800">Nama</label>
          <input type="text" x-model="formUser.full_name" placeholder="Masukkan nama" class="mt-1 w-full rounded-xl border border-brand-300 px-3 py-2 bg-white focus:border-brand-500 focus:ring-brand-400 focus:ring-2">
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-800">Email</label>
          <input type="email" x-model="formUser.email" placeholder="cth: user@dprdgorontalo.com" required class="mt-1 w-full rounded-xl border border-brand-300 px-3 py-2 bg-white focus:border-brand-500 focus:ring-brand-400 focus:ring-2">
        </div>
        <div x-show="!editingUser">
          <label class="block text-sm font-medium text-slate-800">Password</label>
          <input type="password"
                 x-model="formUser.password"
                 placeholder="Minimal 8 karakter"
                 :required="!editingUser"
                 minlength="8"
                 class="mt-1 w-full rounded-xl border border-brand-300 px-3 py-2 bg-white focus:border-brand-500 focus:ring-brand-400 focus:ring-2">
          <p class="mt-1 text-xs text-slate-600">Password minimal <span class="font-semibold">8</span> karakter.</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-800">Role</label>
          <select x-model="formUser.role" required class="mt-1 w-full rounded-xl border border-brand-300 px-3 py-2 bg-white focus:border-brand-500 focus:ring-brand-400 focus:ring-2">
            <option value="admin">Admin</option>
            <option value="resepsionis">Resepsionis</option>
            <option value="host">Host</option>
          </select>
        </div>

        <div class="flex justify-end gap-2 pt-2">
          <button type="button" @click="showAddUser=false" class="rounded-xl border border-brand-300 px-4 py-2 font-semibold text-slate-800 bg-[#FFFDF5] hover:bg-brand-100/80">Batal</button>
          <button type="submit" class="ripple rounded-xl bg-yellow-gradient px-4 py-2 text-white font-semibold shadow-yellow-glow hover:shadow-lift">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============== MODAL: Tambah/Edit Tamu (MULTI-STEP) ============== -->
  <div x-cloak x-show="showAddEditTamu"
       x-data
       @keydown.escape.window="showAddEditTamu=false"
       class="fixed inset-0 z-50 flex items-start justify-center p-4 sm:p-6 transition-all duration-200"
       :class="showAddEditTamu ? 'opacity-100 visible' : 'opacity-0 invisible'">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showAddEditTamu=false"></div>

    <div class="relative mt-6 w-full max-w-4xl overflow-hidden rounded-3xl bg-[#FFFDF5] shadow-2xl ring-1 ring-brand-200 transition-transform"
         :class="step===1 ? 'animate-scaleIn' : ''">
      <!-- Header sticky -->
      <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-brand-200 bg-[#FFFDF5]/95 px-6 py-5 backdrop-blur">
        <div>
          <h3 class="text-2xl font-extrabold flex items-center gap-2 text-slate-900">
            <svg class="h-6 w-6 text-brand-500" viewBox="0 0 24 24" fill="currentColor"><path d="M19 7H5a2 2 0 00-2 2v7h18V9a2 2 0 00-2-2zM3 18h18v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1z"/></svg>
            <span x-text="editingTamu ? 'Edit Kunjungan Tamu' : 'Pengajuan Kunjungan Tamu'"></span>
          </h3>
          <p class="mt-1 text-slate-700 text-sm">Silakan lengkapi formulir di bawah ini dengan data yang benar</p>
        </div>
        <button class="rounded-full p-2 text-slate-500 hover:bg-brand-100/80 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-300" aria-label="Tutup" @click="showAddEditTamu=false">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
      </div>

      <!-- Stepper -->
      <div class="px-6 pt-4">
        <ol class="flex items-centered gap-6">
          <li class="flex items-center gap-3">
            <span class="grid h-8 w-8 place-items-center rounded-full border-2 font-semibold"
                  :class="step===1 ? 'border-brand-500 bg-brand-500 text-white shadow-yellow-glow' : 'border-brand-200 bg-brand-50 text-brand-700'">1</span>
            <span class="text-sm font-medium text-slate-800">Data Keperluan</span>
          </li>
          <li class="flex items-center gap-3">
            <span class="grid h-8 w-8 place-items-center rounded-full border-2 font-semibold"
                  :class="step===2 ? 'border-brand-500 bg-brand-500 text-white shadow-yellow-glow' : 'border-brand-200 bg-brand-50 text-brand-700'">2</span>
            <span class="text-sm font-medium text-slate-800">Pihak Tujuan &amp; Dokumen</span>
          </li>
        </ol>
      </div>

      <!-- Form -->
      <form @submit.prevent="saveTamu" class="max-h-[75vh] overflow-y-auto px-6 pb-6">
        <!-- STEP 1 -->
        <section x-show="step===1" x-transition.opacity class="mt-6 space-y-6">
          <!-- Informasi Pemohon -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Informasi Pemohon</h4>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-800">Nama Lengkap <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.nama" type="text" class="mt-1 w-full rounded-xl border border-brand-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-400 focus:ring-2" placeholder="Nama lengkap">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Alamat Email <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.email" type="email" class="mt-1 w-full rounded-xl border border-brand-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-400 focus:ring-2" placeholder="nama@email.com">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Nomor Handphone (WhatsApp) <span class="text-red-500">*</span></label>
                <div class="mt-1 flex rounded-xl border border-brand-300 bg-white shadow-sm focus-within:border-brand-500 focus-within:ring-2 focus-within:ring-brand-400">
                  <span class="inline-flex items-center rounded-l-xl bg-brand-100 px-3 text-slate-700 select-none">+62</span>
                  <input required x-model="formTamu.no_hp" type="tel" class="w-full rounded-r-xl px-3 py-2 focus:outline-none" placeholder="81234567890">
                </div>
                <p class="mt-1 text-xs text-slate-600">Format: 812-3456-7890 (tanpa +62)</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Jumlah Peserta <span class="text-red-500">*</span></label>
                <input required x-model.number="formTamu.jumlah_peserta" type="number" min="1" max="50" class="mt-1 w-full rounded-xl border border-brand-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-400 focus:ring-2" placeholder="cth: 10">
                <p class="mt-1 text-xs text-slate-600">Maksimal 50 orang per kunjungan</p>
              </div>
            </div>
          </div>

          <!-- Informasi Instansi/Organisasi -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Informasi Instansi/Organisasi</h4>
            </div>

            <label class="block text-sm font-medium text-slate-800">Instansi/Daerah Asal <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-4">
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 cursor-pointer hover:ring-brand-400">
                <input type="radio" x-model="formTamu.instansi_kategori" value="opd" required><span class="text-sm text-slate-800">OPD</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 cursor-pointer hover:ring-brand-400">
                <input type="radio" x-model="formTamu.instansi_kategori" value="lembaga"><span class="text-sm text-slate-800">Lembaga</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 cursor-pointer hover:ring-brand-400">
                <input type="radio" x-model="formTamu.instansi_kategori" value="perseorangan"><span class="text-sm text-slate-800">Perseorangan</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 cursor-pointer hover:ring-brand-400">
                <input type="radio" x-model="formTamu.instansi_kategori" value="ormas"><span class="text-sm text-slate-800">ORMAS</span>
              </label>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-2">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-800">Nama Instansi/Organisasi <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.instansi_nama" type="text" class="mt-1 w-full rounded-xl border border-brand-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-400 focus:ring-2" placeholder="cth: Dinas Pendidikan">
              </div>
            </div>
          </div>

          <!-- Jadwal -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Jadwal Kunjungan</h4>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-800">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.tanggal_kunjungan" type="date" class="mt-1 w-full rounded-xl border border-brand-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-400 focus:ring-2">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Waktu Kunjungan <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.waktu_kunjungan" type="time" class="mt-1 w-full rounded-xl border border-brand-300 bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-400 focus:ring-2">
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3">
            <button type="button" @click="step=2" class="ripple rounded-xl bg-yellow-gradient px-5 py-2 font-semibold text-white shadow-yellow-glow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-transform focus:outline-none focus:ring-2 focus:ring-brand-300">Lanjut</button>
          </div>
        </section>

        <!-- STEP 2 -->
        <section x-show="step===2" x-transition.opacity class="mt-6 space-y-6">
          <!-- Pihak Tujuan -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13 7H7v6h6V7z" /><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm10 12H5V5h10v10z" clip-rule="evenodd"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Pihak yang Dituju</h4>
            </div>

            <label class="block text-sm font-medium text-slate-800">Kategori Pihak yang Dituju <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 cursor-pointer hover:ring-brand-400">
                <input type="radio" x-model="formTamu.bertemu_kategori" value="Pimpinan" required @change="formTamu.bertemu_sub=''; updateBertemuText()"><span class="text-sm text-slate-800">Pimpinan</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 cursor-pointer hover:ring-brand-400">
                <input type="radio" x-model="formTamu.bertemu_kategori" value="AKD" @change="formTamu.bertemu_sub=''; updateBertemuText()"><span class="text-sm text-slate-800">AKD</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 cursor-pointer hover:ring-brand-400">
                <input type="radio" x-model="formTamu.bertemu_kategori" value="Sekretariat" @change="formTamu.bertemu_sub=''; updateBertemuText()"><span class="text-sm text-slate-800">Sekretariat</span>
              </label>
            </div>

            <!-- List dinamis -->
            <div class="mt-4 space-y-3" x-show="formTamu.bertemu_kategori==='Pimpinan'">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Ketua DPRD" @change="updateBertemuText()" required><span class="text-slate-800">Ketua DPRD</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Wakil ketua 1" @change="updateBertemuText()"><span class="text-slate-800">Wakil ketua 1</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Wakil ketua 2" @change="updateBertemuText()"><span class="text-slate-800">Wakil ketua 2</span></label>
            </div>

            <div class="mt-4" x-show="formTamu.bertemu_kategori==='AKD'">
              <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Badan Kehormatan" @change="updateBertemuText()" required><span class="text-slate-800">Badan Kehormatan</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Badan Anggaran" @change="updateBertemuText()"><span class="text-slate-800">Badan Anggaran</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Badan Pembentukan Peraturan Daerah" @change="updateBertemuText()"><span class="text-slate-800">Badan Pembentukan Peraturan Daerah</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Badan Musyawarah" @change="updateBertemuText()"><span class="text-slate-800">Badan Musyawarah</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Komisi 1" @change="updateBertemuText()"><span class="text-slate-800">Komisi 1</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Komisi 2" @change="updateBertemuText()"><span class="text-slate-800">Komisi 2</span></label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Komisi 3" @change="updateBertemuText()"><span class="text-slate-800">Komisi 3</span></label>
              </div>
            </div>

            <div class="mt-4 space-y-3" x-show="formTamu.bertemu_kategori==='Sekretariat'">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Sekretaris" @change="updateBertemuText()" required><span class="text-slate-800">Sekretaris</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Bagian Umum dan Humas" @change="updateBertemuText()"><span class="text-slate-800">Bagian Umum dan Humas</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Bagian Keuangan" @change="updateBertemuText()"><span class="text-slate-800">Bagian Keuangan</span></label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-200 hover:ring-brand-400"><input type="radio" x-model="formTamu.bertemu_sub" value="Persidangan dan Perundang-undangan" @change="updateBertemuText()"><span class="text-slate-800">Persidangan dan Perundang-undangan</span></label>
            </div>

            <!-- Gabungan (readonly) -->
            <div class="mt-4">
              <label class="block text-sm font-medium text-slate-800">Bertemu Dengan (otomatis)</label>
              <input x-model="formTamu.bertemu_dengan" type="text" readonly class="mt-1 w-full rounded-xl border border-brand-300 px-3 py-2 bg-brand-50 text-slate-700">
              <p class="text-xs text-slate-600 mt-1">Otomatis dari pilihan kategori & sub-kategori.</p>
            </div>
          </div>

          <!-- Upload Dokumen -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 7a4 4 0 118 0v4a2 2 0 11-4 0V7a2 2 0 10-4 0v6a4 4 0 108 0V9h2v4a6 6 0 11-12 0V7z"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Upload Dokumen</h4>
            </div>
            <div class="grid grid-cols-1 gap-5">
              <div>
                <label class="block text-sm font-medium text-slate-800">Surat Pemberitahuan/Surat Tugas (opsional)</label>
                <input type="file" @change="onDokumenChange($event)" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 w-full rounded-xl border border-brand-300 bg-white px-3 py-2 file:mr-4 file:rounded-lg file:border-0 file:bg-yellow-gradient file:px-4 file:py-2 file:font-semibold file:text-white hover:file:shadow-yellow-glow">
                <p class="mt-1 text-xs text-slate-600" x-show="formTamu.dokumen">File terset: <span class="font-medium" x-text="formTamu.dokumen_name"></span></p>
              </div>
            </div>
          </div>

          <!-- Status -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5">
            <label class="block text-sm font-medium text-slate-800">Status</label>
            <select x-model="formTamu.status_sekarang" class="mt-1 w-full rounded-xl border border-brand-300 px-3 py-2 bg-white focus:border-brand-500 focus:ring-brand-400 focus:ring-2">
              <option value="menunggu">Menunggu</option>
              <option value="ditolak">Ditolak</option>
              <option value="diterima">Diterima</option>
              <option value="selesai">Selesai</option>
            </select>
          </div>

          <div class="flex items-center justify-between">
            <button type="button" @click="step=1" class="rounded-xl px-4 py-2 text-slate-800 hover:bg-brand-50 transition-colors">Kembali</button>
            <div class="flex items-center gap-3">
              <button type="button" @click="showAddEditTamu=false" class="rounded-xl px-4 py-2 text-slate-700 bg-[#FFFDF5] hover:bg-brand-50 transition-colors">Batal</button>
              <button type="submit" class="ripple rounded-xl bg-yellow-gradient px-5 py-2 font-semibold text-white shadow-yellow-glow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-transform focus:outline-none focus:ring-2 focus:ring-brand-300">Simpan</button>
            </div>
          </div>
        </section>
      </form>
    </div>
  </div>

  <!-- ============== MODAL: Logout Confirm ============== -->
  <div x-cloak x-show="showLogout" x-transition.opacity class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm grid place-items-center p-4">
    <div class="w-full max-w-md bg-[#FFFDF5] rounded-2xl p-6 shadow-2xl animate-scaleIn ring-1 ring-brand-200">
      <div class="flex items-start justify-between">
        <h3 class="text-lg font-bold flex items-center gap-2 text-slate-900">
          <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="currentColor"><path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8a2 2 0 00-2 2v4h2V5h8v14h-8v-4h-2v4a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2z"/></svg>
          Konfirmasi Logout
        </h3>
        <button class="ripple rounded-lg px-2 py-1 text-slate-500 hover:bg-brand-100/80" @click="showLogout=false">✕</button>
      </div>
      <p class="mt-3 text-slate-700">Yakin untuk logout?</p>
      <div class="mt-6 flex justify-end gap-2">
        <button type="button" class="rounded-xl border border-brand-300 px-4 py-2 font-semibold text-slate-800 bg-[#FFFDF5] hover:bg-brand-100/80" @click="showLogout=false">Tidak</button>
        <button type="button" class="ripple rounded-xl bg-rose-600 px-4 py-2 text-white font-semibold hover:bg-rose-700" @click="doLogout()">Ya, Logout</button>
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
        active:'dashboard',
        showAddUser:false,
        showAddEditTamu:false,
        showLogout:false,
        editingUser:false,
        editingTamu:false,

        step:1,

        users:[],
        tamu:[],

        dashboardRange: 7,
        metrics: { totalTamu:0, totalSelesai:0, totalUser:0, todayVisits: 0 },
        statusCounts: { menunggu: 0, diterima: 0, ditolak: 0, selesai: 0 },
        _chart: null,

        // Pagination state (Kelola Tamu)
        currentPage: 1,
        pageSize: 10, // 10 data per halaman

        filter: { status:'', month:'', q:'' },
        filterUser: { role:'', q:'' },

        formUser: { id:null, full_name:'', email:'', password:'', role:'admin' },
        formTamu: {
          id:null, nama:'', email:'', no_hp:'',
          jumlah_peserta:1, instansi_kategori:'', instansi_nama:'',
          tanggal_kunjungan:todayISO(), waktu_kunjungan:'08:00',
          bertemu_kategori:'', bertemu_sub:'', bertemu_dengan:'',
          dokumen:null, dokumen_name:'', status_sekarang:'menunggu'
        },

        async init(){
          try{
            await Promise.all([
              this.loadUsers().catch(e=>console.error('loadUsers error', e)),
              this.loadKunjungan().catch(e=>console.error('loadKunjungan error', e)),
            ]);
          }catch(e){
            console.error('Init load error', e);
          }

          this.computeMetrics();
          this.computeStatusCounts();

          if (this.$watch) {
            this.$watch('active', (val) => {
              if (val === 'dashboard') {
                this.$nextTick(() => this.refreshChart());
              }
            });
          }

          this.$nextTick(() => {
            this.refreshChart();
          });
        },

        async loadUsers(){ this.users = await api('/admin/api/users'); },
        async loadKunjungan(){ this.tamu = await api('/admin/api/kunjungan'); },

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

        dateAdd(d, days){
          const dt = new Date(d);
          dt.setDate(dt.getDate()+days);
          return dt;
        },
        fmtDateISO(d){
          const y=d.getFullYear(), m=String(d.getMonth()+1).padStart(2,'0'), dd=String(d.getDate()).padStart(2,'0');
          return `${y}-${m}-${dd}`;
        },
        fmtShort(d){
          return d.slice(8,10)+'/'+d.slice(5,7);
        },
        buildDateBuckets(days){
          const today = new Date();
          const start = this.dateAdd(today, -(days-1));
          const labels = [];
          const index = {};
          for(let i=0;i<days;i++){
            const dt = this.dateAdd(start, i);
            const key = this.fmtDateISO(dt);
            labels.push(key);
            index[key]=i;
          }
          return { labels, index };
        },

        // === DATA UNTUK GRAFIK (BAR CHART) - PERBAIKAN ===
        seriesByStatus(days){
          const statuses = ['menunggu','diterima','ditolak','selesai'];

          // Warna selaras tema kuning
          const colors = {
            menunggu: {
              bg:     'rgba(245, 158, 11, 0.8)',   // amber-500
              hover:  'rgba(245, 158, 11, 1)'
            },
            diterima: {
              bg:     'rgba(16, 185, 129, 0.8)',   // emerald-500
              hover:  'rgba(16, 185, 129, 1)'
            },
            ditolak: {
              bg:     'rgba(244, 63, 94, 0.8)',    // rose-500
              hover:  'rgba(244, 63, 94, 1)'
            },
            selesai: {
              bg:     'rgba(148, 163, 184, 0.8)',  // slate-400/500
              hover:  'rgba(148, 163, 184, 1)'
            }
          };

          const {labels,index} = this.buildDateBuckets(days);
          const map = {};
          statuses.forEach(s=> map[s] = new Array(labels.length).fill(0));

          // PERBAIKAN: Pastikan semua data diproses dengan benar
          (this.tamu||[]).forEach(row=>{
            const tgl = row.tanggal_kunjungan;
            if(!tgl) return;
            
            // PERBAIKAN: Normalisasi status untuk menghindari case sensitivity
            const st = (row.status_sekarang||'').toLowerCase().trim();
            
            // PERBAIKAN: Pastikan tanggal ada dalam rentang yang dipilih
            if(index.hasOwnProperty(tgl) && map[st]) {
              map[st][index[tgl]] += 1;
            }
          });

          const datasets = statuses.map(s=>{
            const data = map[s];
            const hasVal = data.some(v=>v>0);
            const palette = colors[s];

            return {
              label: this.capitalizeFirst(s),
              data,
              // PERBAIKAN: Jangan sembunyikan dataset meskipun tidak ada data
              // Ini memastikan semua status tetap tampil di legend
              hidden: false,
              backgroundColor: palette.bg,
              borderColor: palette.hover,
              borderWidth: 1,
              borderRadius: 4,
              borderSkipped: false,
              hoverBackgroundColor: palette.hover,
            };
          });

          return { labels, datasets };
        },

        // Helper untuk kapitalisasi pertama
        capitalizeFirst(string) {
          return string.charAt(0).toUpperCase() + string.slice(1);
        },

        refreshChart(){
          if (this.active !== 'dashboard') return;

          if (typeof Chart === 'undefined') {
            console.warn('Chart.js belum termuat saat refreshChart dipanggil.');
            return;
          }

          const canvas = (this.$refs && this.$refs.chartHarian)
            ? this.$refs.chartHarian
            : document.getElementById('chartHarian');

          if(!canvas){
            console.warn('Canvas chartHarian tidak ditemukan.');
            return;
          }

          const ctx = canvas.getContext ? canvas.getContext('2d') : canvas;

          const { labels, datasets } = this.seriesByStatus(this.dashboardRange);

          if(this._chart){ this._chart.destroy(); }

          const maxTicks = Math.min(8, labels.length);
          const that = this;

          this._chart = new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              // Animasi singkat (lebih ringan)
              animation: {
                duration: 300,
                easing: 'easeOutQuad'
              },
              interaction: {
                mode: 'index',
                intersect: false
              },
              plugins: {
                legend: {
                  position: 'bottom',
                  labels: {
                    usePointStyle: true,
                    pointStyle: 'circle',
                    padding: 16,
                    color: '#334155', // slate-700
                    boxWidth: 10,
                    // PERBAIKAN: Pastikan teks legend tidak terpotong
                    font: {
                      size: 12
                    }
                  }
                },
                tooltip: {
                  backgroundColor: 'rgba(15,23,42,0.9)',
                  titleColor: '#f9fafb',
                  bodyColor: '#e5e7eb',
                  borderWidth: 0,
                  padding: 10,
                  displayColors: true,
                  callbacks: {
                    title(items){
                      try{
                        const d = items[0].label;
                        const dt = new Date(d+'T00:00:00');
                        return new Intl.DateTimeFormat('id-ID',{
                          weekday:'long',
                          day:'2-digit',
                          month:'long',
                          year:'numeric'
                        }).format(dt);
                      }catch{
                        return items[0].label;
                      }
                    }
                  }
                }
              },
              scales: {
                x: {
                  stacked: true,
                  grid: {
                    display: false,
                    drawBorder: false
                  },
                  ticks: {
                    autoSkip: true,
                    maxTicksLimit: maxTicks,
                    color: '#4b5563', // slate-600
                    padding: 6,
                    callback: function(value){
                      const lab = this.getLabelForValue(value);
                      return that.fmtShort(lab);
                    }
                  }
                },
                y: {
                  stacked: true,
                  beginAtZero:true,
                  precision:0,
                  ticks: {
                    color: '#4b5563',
                    padding: 6,
                    stepSize: 1
                  },
                  grid: {
                    color: 'rgba(148,163,184,0.25)', // slate-400
                    drawBorder: false,
                    borderDash: [4,4]
                  }
                }
              }
            }
          });
        },

        openAddUser(){
          this.editingUser=false;
          this.formUser={id:null, full_name:'', email:'', password:'', role:'admin'};
          this.showAddUser=true;
        },
        editUser(u){
          this.editingUser=true;
          this.formUser={id:u.id, full_name:(u.full_name||''), email:u.email, password:'', role:u.role};
          this.showAddUser=true;
        },
        async saveUser(){
          try{
            if(!this.editingUser){
              const pwd = (this.formUser.password||'');
              if(pwd.length < 8){
                alert('Password minimal 8 karakter.');
                return;
              }
            }

            if(this.editingUser){
              await api(`/admin/api/users/${this.formUser.id}`, {
                method:'PUT',
                body: JSON.stringify({
                  full_name: this.formUser.full_name || null,
                  email: this.formUser.email,
                  role: this.formUser.role,
                  password: this.formUser.password || null,
                })
              });
            }else{
              await api('/admin/api/users', {
                method:'POST',
                body: JSON.stringify({
                  full_name: this.formUser.full_name || null,
                  email: this.formUser.email,
                  password: this.formUser.password,
                  role: this.formUser.role
                })
              });
            }
            this.showAddUser=false;
            await this.loadUsers();
            this.computeMetrics();
          }catch(e){ alert('Gagal menyimpan user:\n\n' + e.message); console.error(e); }
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
            dokumen:null, dokumen_name:'', status_sekarang:'menunggu'
          };
          this.showAddEditTamu=true;
        },
        editTamu(row){
          this.editingTamu=true;
          this.step=1;

          let kategori='', sub='';
          if (row.bertemu_kategori && row.bertemu_sub){
            kategori=row.bertemu_kategori; sub=row.bertemu_sub;
          } else if (row.bertemu_dengan && typeof row.bertemu_dengan==='string') {
            const parts = row.bertemu_dengan.split(' - ');
            if (parts.length>=2){ kategori=parts[0]; sub=parts.slice(1).join(' - '); }
          }

          this.formTamu = {
            id: row.id,
            nama: row.nama || '',
            email: row.email || '',
            no_hp: row.no_hp || '',
            jumlah_peserta: row.jumlah_peserta || 1,
            instansi_kategori: row.instansi_kategori || '',
            instansi_nama: row.instansi_nama || '',
            tanggal_kunjungan: row.tanggal_kunjungan,
            waktu_kunjungan: row.waktu_kunjungan,
            bertemu_kategori: kategori || '',
            bertemu_sub: sub || '',
            bertemu_dengan: row.bertemu_dengan || (kategori && sub ? `${kategori} - ${sub}` : ''),
            dokumen: null,
            dokumen_name: '',
            status_sekarang: row.status_sekarang || 'menunggu',
          };
          this.showAddEditTamu=true;
        },

        updateBertemuText(){
          const k = (this.formTamu.bertemu_kategori||'').trim();
          const s = (this.formTamu.bertemu_sub||'').trim();
          this.formTamu.bertemu_dengan = k && s ? `${k} - ${s}` : (k || '');
        },

        async saveTamu(){
          try{
            this.updateBertemuText();

            const fd = new FormData();
            fd.append('nama', this.formTamu.nama);
            fd.append('email', this.formTamu.email);
            fd.append('no_hp', this.formTamu.no_hp);
            if (this.formTamu.instansi_kategori) fd.append('instansi_kategori', this.formTamu.instansi_kategori);
            if (this.formTamu.instansi_nama)     fd.append('instansi_nama', this.formTamu.instansi_nama);
            if (this.formTamu.jumlah_peserta)    fd.append('jumlah_peserta', this.formTamu.jumlah_peserta);
            fd.append('tanggal_kunjungan', this.formTamu.tanggal_kunjungan);
            fd.append('waktu_kunjungan',   this.formTamu.waktu_kunjungan);
            if (this.formTamu.status_sekarang) fd.append('status_sekarang', this.formTamu.status_sekarang);

            if (this.formTamu.bertemu_dengan)    fd.append('bertemu_dengan', this.formTamu.bertemu_dengan);
            if (this.formTamu.bertemu_kategori)  fd.append('bertemu_kategori', this.formTamu.bertemu_kategori);
            if (this.formTamu.bertemu_sub)       fd.append('bertemu_sub', this.formTamu.bertemu_sub);

            if (this.formTamu.dokumen instanceof File) fd.append('dokumen', this.formTamu.dokumen);

            let url = '/admin/api/kunjungan';
            let method = 'POST';

            if (this.editingTamu && this.formTamu.id) {
              fd.append('_method', 'PUT');
              url = `/admin/api/kunjungan/${this.formTamu.id}`;
            }

            const res = await fetch(url, {
              method,
              headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
              body: fd,
              credentials: 'same-origin'
            });

            if(!res.ok){
              const msg = await res.text().catch(()=> '');
              throw new Error(`HTTP ${res.status} ${res.statusText}\n${msg}`);
            }

            this.showAddEditTamu=false;
            await this.loadKunjungan();
            this.computeMetrics();
            this.computeStatusCounts();
            this.refreshChart();
            this.currentPage = 1;
          }catch(e){
            console.error(e);
            alert('Gagal menyimpan data tamu/kunjungan.\n\nDetail:\n' + e.message);
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
            }else{
              await api(`/admin/api/kunjungan/${item.id}`, { method:'DELETE' });
              await this.loadKunjungan();
              this.computeMetrics();
              this.computeStatusCounts();
              this.refreshChart();
              // pastikan halaman tidak melewati total halaman baru
              const total = this.filteredTamu().length;
              const maxPage = total === 0 ? 1 : Math.ceil(total / this.pageSize);
              if(this.currentPage > maxPage) this.currentPage = maxPage;
            }
          }catch(e){ alert('Gagal menghapus:\n\n' + e.message); console.error(e); }
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

        // === FILTERED + PAGINATION (Kelola Tamu) ===
        filteredTamu(){
          const all = (this.tamu||[]).filter(row =>
            this.matchesStatus(row) && this.matchesMonth(row) && this.matchesSearch(row)
          );
          // jaga supaya currentPage tidak melewati total halaman
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
          try{ const dt=new Date(d+'T00:00:00'); return new Intl.DateTimeFormat('id-ID',{day:'2-digit',month:'short',year:'numeric'}).format(dt); }catch{ return d; }
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
        labelStatus(st){ const m={menunggu:'Menunggu',diterima:'Diterima',ditolak:'Ditolak',selesai:'Selesai'}; return m[st]||String(st).charAt(0).toUpperCase()+String(st).slice(1); },
      }
    }

    const TZ='Asia/Makassar';
    function todayISO(){ const d=new Date(); const y=d.getFullYear(), m=String(d.getMonth()+1).padStart(2,'0'), dd=String(d.getDate()).padStart(2,'0'); return `${y}-${m}-${dd}`; }

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
        if (window.scrollY > 6) topbar.classList.add('shadow','bg-brand-100/95');
        else topbar.classList.remove('shadow','bg-brand-100/95');
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