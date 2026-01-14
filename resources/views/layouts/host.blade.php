<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>E-Tamu DPRD – Host</title>
  <link rel="icon" type="image/png" href="/img/logoDprd.png">

  <!-- Tailwind + Alpine -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>

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
              500: '#FFB300',
              600: '#E6A100',
              700: '#B37D00',
              800: '#805A00',
              900: '#4D3600'
            },
            night: {
              900:'#0B1220',
              800:'#0F1930',
            },
          },
          boxShadow: {
            soft: '0 10px 30px -12px rgba(0,0,0,.12)',
            lift: '0 18px 40px -20px rgba(0,0,0,.25)',
            'yellow-glow': '0 4px 14px -2px rgba(255, 179, 0, 0.3)'
          },
          keyframes: {
            fadeUp: {
              '0%':   { opacity:0, transform:'translateY(12px)' },
              '100%': { opacity:1, transform:'translateY(0)' },
            },
            scaleIn: {
              '0%':   { opacity:0, transform:'scale(.96)' },
              '100%': { transform:'scale(1)' },
            },
            pulseSoft: {
              '0%,100%': { transform:'scale(1)' },
              '50%':     { transform:'scale(1.03)' },
            },
          },
          animation: {
            fadeUp: 'fadeUp .45s ease-out both',
            scaleIn:'scaleIn .28s ease-out both',
            pulseSoft:'pulseSoft 1.8s ease-in-out infinite',
          }
        }
      }
    }
  </script>

  <style>
    .hairline{
      height:1px;
      background:repeating-linear-gradient(
        90deg,
        rgba(255,179,0,.35) 0 8px,
        transparent 8px 16px
      )
    }

    .tab-btn{
      padding:.5rem 1rem;
      border-radius:999px;
      transition:
        background-color .2s ease,
        color .2s ease,
        box-shadow .2s ease,
        transform .2s ease
    }
    .tab-btn:not(.is-active){
      background-color:transparent;
      color:rgba(255,251,235,.9);
    }
    .tab-btn.is-active{
      background-color:#805A00;
      color:#fff;
      box-shadow:0 10px 30px -12px rgba(0,0,0,.22)
    }
    .tab-btn:hover{
      transform:translateY(-2px)
    }
    .tab-btn:not(.is-active).with-underline{
      position:relative
    }
    .tab-btn:not(.is-active).with-underline::after{
      content:'';
      position:absolute;
      left:12px;
      right:12px;
      bottom:6px;
      height:2px;
      background:#FFECB3;
      transform:scaleX(0);
      transform-origin:left;
      transition:transform .25s ease
    }
    .tab-btn:not(.is-active).with-underline:hover::after{
      transform:scaleX(1)
    }

    .ripple{position:relative;overflow:hidden}
    .ripple .rp{
      position:absolute;
      border-radius:999px;
      transform:translate(-50%,-50%);
      pointer-events:none;
      opacity:.25;
      background:#000;
      animation:ripple .6s ease-out forwards
    }
    @keyframes ripple{
      from{width:0;height:0;opacity:.25}
      to{width:360px;height:360px;opacity:0}
    }

    /* Kolom aksi sticky kiri pada tabel (desktop) */
    .sticky-col {
      position: sticky;
      left: 0;
      z-index: 10;
      background: #FFF8E6; /* brand-50 agar menyatu dengan tema kuning */
    }
    thead .sticky-col { z-index: 11; }

    [x-cloak]{display:none!important;}
  </style>
</head>
<body class="min-h-screen antialiased text-slate-800 bg-gradient-to-br from-brand-50 via-yellow-50 to-white selection:bg-brand-200/60">

  <!-- NAVBAR -->
  <header id="topbar" class="fixed inset-x-0 top-0 z-40 bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-lg border-b border-brand-400 transition-all">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-3">
        <button
          type="button"
          class="group flex items-center gap-3"
          onclick="scrollTo({top:0,behavior:'smooth'})"
          aria-label="Ke atas"
        >
          <div class="relative">
            <div class="absolute inset-0 rounded-full bg-brand-800/30 blur-sm opacity-60 group-hover:opacity-80 transition"></div>
            <img
              src="/img/logoDprd.png"
              alt="Logo DPRD"
              class="relative h-10 w-10 md:h-12 md:w-12 object-contain transition-transform duration-500 motion-safe:group-hover:scale-110 motion-safe:group-hover:rotate-3"
            />
          </div>
          <div class="leading-tight text-left">
            <div class="font-extrabold tracking-tight text-white text-sm sm:text-base">E-Tamu DPRD</div>
            <div class="text-[10px] sm:text-[11px] text-brand-100">Kota Gorontalo · Panel Host</div>
          </div>
        </button>

        <!-- Desktop nav -->
        <nav class="hidden md:flex items-center gap-1 text-sm">
          <a href="{{ route('host.index') }}" 
             class="tab-btn {{ request()->routeIs('host.index') ? 'is-active' : 'with-underline' }} text-white hover:text-brand-100">
            <span class="inline-flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-100 transition-transform duration-300 motion-safe:group-hover:-translate-y-0.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"/>
                <path d="M12 6a1 1 0 0 0-1 1v5a1 1 0 0 0 2 0V7a1 1 0 0 0-1-1z"/>
                <path d="M12 16a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
              </svg>
              Dashboard
            </span>
          </a>
          <a href="{{ route('host.datapengajuan') }}" 
             class="tab-btn {{ request()->routeIs('host.datapengajuan') ? 'is-active' : 'with-underline' }} text-white hover:text-brand-100">
            <span class="inline-flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-100 transition-transform duration-300 motion-safe:group-hover:scale-110" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
              </svg>
              Data Pengajuan
            </span>
          </a>
          <a href="{{ route('host.riwayat') }}" 
             class="tab-btn {{ request()->routeIs('host.riwayat') ? 'is-active' : 'with-underline' }} text-white hover:text-brand-100">
            <span class="inline-flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-100 transition-transform duration-300 motion-safe:group-hover:rotate-12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/>
                <path d="M12 15l5-5-5-5v10z"/>
              </svg>
              Riwayat
            </span>
          </a>
        </nav>

        <!-- Kanan -->
        <div class="hidden sm:flex items-center gap-4">
          <div class="text-right leading-tight text-white">
            <div id="clock" class="font-semibold text-sm md:text-base">--:--:-- WITA</div>
            <div id="date" class="text-[11px] md:text-xs text-brand-100">—</div>
          </div>

          <!-- USER DROPDOWN -->
          <div class="relative" x-data="{open:false}" @keydown.escape.window="open=false">
            <button class="flex items-center gap-2 rounded-full bg-brand-700 hover:bg-brand-800 text-white px-2 py-1 focus:outline-none focus:ring-2 focus:ring-brand-300 transition-colors"
                    @click="open=!open" :aria-expanded="open">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-brand-700 ring-2 ring-brand-300 shadow-soft">
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
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-100" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>

            <div x-cloak x-show="open" x-transition.origin.top.right @click.outside="open=false"
                 class="absolute right-0 mt-2 w-52 rounded-xl border border-brand-400 bg-brand-700 shadow-lg overflow-hidden">
              <div class="py-2">
                <div class="px-4 pb-2 text-[11px] text-brand-100 border-b border-brand-400">
                  Masuk sebagai <span class="font-semibold text-white">Host</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="button"
                          class="w-full text-left px-4 py-2.5 text-sm hover:bg-brand-600 flex items-center gap-2 text-brand-100 transition-colors"
                          @click="open=false; $nextTick(()=>document.getElementById('logout-form').submit())">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M3 4.5A1.5 1.5 0 014.5 3h5A1.5 1.5 0 0111 4.5v1a.5.5 0 01-1 0v-1a.5.5 0 00-.5-.5h-5a.5.5 0 00-.5.5v11a.5.5 0 00.5.5h5a2 2 0 002-2v-1a.5.5 0 011 0v1A1.5 1.5 0 019.5 17h-5A1.5 1.5 0 013 15.5v-11z" clip-rule="evenodd"/>
                      <path d="M13.854 10.354a.5.5 0 010-.708l2-2a.5.5 0 11.707.708L15.707 9.5H8.5a.5.5 0 010-1h7.207l.854-.854a.5.5 0 11.707.707l-2 2a.5.5 0 01-.707 0z"/>
                    </svg>
                    Logout
                  </button>
                </form>
              </div>
            </div>
          </div>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
          </form>
        </div>

        <!-- Mobile right side (ikon user kecil) -->
        <div class="sm:hidden flex items-center gap-2">
          <div class="inline-flex items-center gap-2 rounded-full bg-brand-700 px-3 py-1 text-[11px] text-white">
            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-brand-100 text-brand-700 text-xs font-semibold">H</span>
            <span>Host</span>
          </div>
        </div>
      </div>
    </div>
    <div class="hairline"></div>
  </header>

  <!-- HERO -->
  <section class="pt-24 sm:pt-28 pb-4 sm:pb-6 bg-gradient-to-r from-brand-50 via-brand-100 to-amber-100 relative overflow-hidden">
    <div class="pointer-events-none absolute -left-24 -top-32 h-72 w-72 rounded-full border-2 border-brand-300/60 bg-gradient-to-br from-amber-100/70 to-transparent"></div>
    <div class="pointer-events-none absolute -right-20 -bottom-32 h-80 w-80 rounded-full border-4 border-brand-200/70 bg-gradient-to-tr from-brand-50/70 to-transparent"></div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-end justify-between gap-4">
      <div class="relative z-10 space-y-2 sm:space-y-3">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/90 px-4 py-2 text-sm font-bold text-brand-700 shadow-lg border-2 border-brand-200">
          <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-brand-500 text-xs text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
              <path d="M16 7h-1l-1-1h-4L9 7H8c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm-4 7c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
            </svg>
          </span>
          Mode Host · Real-time
        </span>
        <h1 id="heroTitle" class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
          @if(request()->routeIs('host.index'))
            Dashboard
          @elseif(request()->routeIs('host.datapengajuan'))
            Data Pengajuan
          @elseif(request()->routeIs('host.riwayat'))
            Riwayat Kunjungan
          @else
            Dashboard
          @endif
        </h1>
        <p id="heroSub" class="text-slate-700 mt-1 sm:mt-2 text-base sm:text-lg max-w-xl font-medium">
          @if(request()->routeIs('host.index'))
            Ringkasan dan daftar pengajuan tamu yang berstatus menunggu.
          @elseif(request()->routeIs('host.datapengajuan'))
            Seluruh tamu yang melakukan pengajuan berstatus menunggu.
          @elseif(request()->routeIs('host.riwayat'))
            Daftar seluruh riwayat kunjungan tamu yang telah diproses.
          @else
            Ringkasan dan daftar pengajuan tamu yang berstatus menunggu.
          @endif
        </p>
      </div>

      <!-- Mobile tab mini (atas) -->
      <div class="md:hidden relative z-10">
        <div class="inline-flex rounded-full bg-white/90 p-1 shadow-lg ring-1 ring-brand-200">
          <a href="{{ route('host.index') }}" 
             class="tab-btn {{ request()->routeIs('host.index') ? 'is-active' : 'with-underline' }} text-xs px-3 py-1.5">
            Dashboard
          </a>
          <a href="{{ route('host.datapengajuan') }}" 
             class="tab-btn {{ request()->routeIs('host.datapengajuan') ? 'is-active' : 'with-underline' }} text-xs px-3 py-1.5">
            Data
          </a>
          <a href="{{ route('host.riwayat') }}" 
             class="tab-btn {{ request()->routeIs('host.riwayat') ? 'is-active' : 'with-underline' }} text-xs px-3 py-1.5">
            Riwayat
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- =============================== KONTEN UTAMA =============================== --}}
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6 sm:space-y-8">
    @yield('content')
  </main>

  <!-- MODAL TOLAK dengan input alasan -->
  <div id="modal-tolak" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden place-items-center p-4" role="dialog" aria-hidden="true">
    <div class="w-full max-w-md bg-white rounded-2xl p-6 shadow-2xl animate-scaleIn border-2 border-rose-200">
      <div class="flex items-start justify-between">
        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
          <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-rose-500 text-white text-xs font-bold">!</span>
          Tolak Pengajuan
        </h3>
        <button type="button" id="modalTolakClose" class="ripple rounded-lg px-2 py-1 text-slate-500 hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-200 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
          </svg>
        </button>
      </div>

      <form id="formTolak" method="POST" action="">
        @csrf
        <div class="mt-4">
          <label for="alasan" class="block text-sm font-bold text-slate-700 mb-2">Alasan Penolakan</label>
          <textarea id="alasan" name="alasan" rows="3" class="w-full px-4 py-3 border-2 border-rose-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-sm" placeholder="Masukkan alasan penolakan..." required></textarea>
        </div>

        <div class="mt-6 flex flex-wrap justify-end gap-3">
          <button type="button" id="modalTolakClose2" class="ripple rounded-xl border-2 border-slate-300 px-5 py-2.5 font-bold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 transition-colors">
            Batal
          </button>
          <button type="submit" class="ripple rounded-xl bg-rose-600 px-5 py-2.5 font-bold text-white focus:outline-none focus:ring-2 focus:ring-rose-500 hover:bg-rose-700 transition-colors">
            Tolak Pengajuan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- TOAST NOTIFICATION -->
  @if(session('success'))
    <div id="toast" class="pointer-events-none fixed bottom-5 right-5 z-60 rounded-xl bg-emerald-600 px-4 py-3 text-white shadow-lg animate-scaleIn">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div id="toast" class="pointer-events-none fixed bottom-5 right-5 z-60 rounded-xl bg-rose-600 px-4 py-3 text-white shadow-lg animate-scaleIn">
      {{ session('error') }}
    </div>
  @endif

  <footer class="mt-8 pb-6 text-center text-sm text-slate-600 font-medium">
    E-Tamu DPRD Kota Gorontalo · {{ date('Y') }} &copy; All Rights Reserved.
  </footer>

  <script>
    // Jam WITA
    function updateClock(){
      const tz = 'Asia/Makassar';
      const now = new Date();
      const time = new Intl.DateTimeFormat('id-ID',{
        hour:'2-digit',
        minute:'2-digit',
        second:'2-digit',
        hour12:false,
        timeZone:tz
      }).format(now);
      const date = new Intl.DateTimeFormat('id-ID',{
        weekday:'long',
        day:'numeric',
        month:'long',
        year:'numeric',
        timeZone:tz
      }).format(now);
      const clockEl = document.getElementById('clock');
      const dateEl  = document.getElementById('date');
      if (clockEl) clockEl.textContent = time + ' WITA';
      if (dateEl)  dateEl.textContent  = date.charAt(0).toUpperCase()+date.slice(1);
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Topbar shadow
    const topbar = document.getElementById('topbar');
    function setShadow(){
      if (!topbar) return;
      if (window.scrollY > 6) {
        topbar.classList.add('shadow');
      } else {
        topbar.classList.remove('shadow',);
      }
    }
    setShadow();
    window.addEventListener('scroll', setShadow, { passive:true });

    // Ripple
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

    // Modal tolak dengan alasan
    const modalTolak = document.getElementById('modal-tolak');
    const formTolak = document.getElementById('formTolak');
    const alasanInput = document.getElementById('alasan');
    const modalTolakClose = document.getElementById('modalTolakClose');
    const modalTolakClose2 = document.getElementById('modalTolakClose2');

    function openModalTolak(id, nama) {
      if (!modalTolak || !formTolak) return;
      
      // Set action form dengan route yang benar
      formTolak.action = `/host/kunjungan/${id}/tolak`;
      
      // Reset dan fokus ke textarea
      if (alasanInput) {
        alasanInput.value = '';
        alasanInput.focus();
      }
      
      // Tampilkan modal
      modalTolak.classList.remove('hidden');
      modalTolak.setAttribute('aria-hidden', 'false');
    }

    function closeModalTolak() {
      if (!modalTolak) return;
      modalTolak.classList.add('hidden');
      modalTolak.setAttribute('aria-hidden', 'true');
    }

    // Event listener untuk tombol tolak
    document.addEventListener('click', function(e) {
      const btnTolak = e.target.closest('.btn-tolak');
      if (btnTolak) {
        const id = btnTolak.dataset.id;
        const nama = btnTolak.dataset.nama;
        if (id) {
          openModalTolak(id, nama);
        }
      }
    });

    // Event listener untuk menutup modal tolak
    if (modalTolakClose) modalTolakClose.addEventListener('click', closeModalTolak);
    if (modalTolakClose2) modalTolakClose2.addEventListener('click', closeModalTolak);
    if (modalTolak) {
      modalTolak.addEventListener('click', function(e) {
        if (e.target === modalTolak) closeModalTolak();
      });
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modalTolak.classList.contains('hidden')) {
          closeModalTolak();
        }
      });
    }

    // Auto-hide toast setelah 5 detik
    const toast = document.getElementById('toast');
    if (toast) {
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s ease';
        setTimeout(() => toast.remove(), 500);
      }, 5000);
    }
  </script>
</body>
</html>