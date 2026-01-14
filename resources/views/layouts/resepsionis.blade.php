<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'E-Tamu DPRD – Resepsionis')</title>
  <link rel="icon" type="image/png" href="/img/logoDprd.png">

  <!-- Tailwind CDN -->
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
              900: '#0B1220',
              800: '#0F1930'
            }
          },
          boxShadow: {
            soft: '0 10px 30px -12px rgba(0,0,0,.12)',
            lift: '0 18px 40px -20px rgba(0,0,0,.25)',
            'yellow-glow': '0 4px 14px -2px rgba(255, 179, 0, 0.3)'
          },
          keyframes: {
            fadeUp: { '0%': { opacity: 0, transform: 'translateY(12px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
            scaleIn: { '0%': { opacity: 0, transform: 'scale(.96)' }, '100%': { opacity: 1, transform: 'scale(1)' } },
            pulseSoft: { '0%,100%': { transform: 'scale(1)' }, '50%': { transform: 'scale(1.03)' } }
          },
          animation: {
            fadeUp: 'fadeUp .45s ease-out both',
            scaleIn: 'scaleIn .28s ease-out both',
            pulseSoft: 'pulseSoft 1.8s ease-in-out infinite'
          }
        }
      }
    }
  </script>

  <style>
    .pattern-dots{background-image:radial-gradient(#FFE199 1px,transparent 1px);background-size:14px 14px;background-position:0 0}
    .hairline{height:1px;background:repeating-linear-gradient(90deg,rgba(0,0,0,.08)0 8px,transparent 8px 16px)}
    .tab-btn{padding:.5rem 1rem;border-radius:999px;transition:background-color .2s ease, color .2s ease, box-shadow .2s ease, transform .2s ease}
    .tab-btn:not(.is-active){background-color:transparent}
    .tab-btn.is-active{background-color:#805A00;color:#fff;box-shadow:0 10px 30px -12px rgba(0,0,0,.12)}
    .tab-btn:hover{transform:translateY(-2px)}
    .tab-btn:not(.is-active).with-underline{position:relative}
    .tab-btn:not(.is-active).with-underline::after{content:'';position:absolute;left:12px;right:12px;bottom:6px;height:2px;background:#805A00;transform:scaleX(0);transform-origin:left;transition:transform .25s ease}
    .tab-btn:not(.is-active).with-underline:hover::after{transform:scaleX(1)}
    .ripple{position:relative;overflow:hidden}
    .ripple .rp{position:absolute;border-radius:999px;transform:translate(-50%,-50%);pointer-events:none;opacity:.25;background:#000;animation:ripple .6s ease-out forwards}
    @keyframes ripple{from{width:0;height:0;opacity:.25}to{width:360px;height:360px;opacity:0}}
    .dot-corner::before{content:'';position:absolute;right:14px;top:14px;width:6px;height:6px;border-radius:999px;background:#FFE199;box-shadow:0 0 0 6px rgba(255,227,153,.35),0 0 0 14px rgba(255,227,153,.12)}
    .views-wrap{overflow:hidden;transition:height .28s ease}
    .views{display:flex;width:300%;transition:transform .35s cubic-bezier(.2,.8,.2,1)}
    .view{width:100%}
    
    /* Mobile improvements */
    @media (max-width: 640px) {
      .mobile-padding { padding-left: 1rem; padding-right: 1rem; }
      .mobile-stack { flex-direction: column; }
      .mobile-text-center { text-align: center; }
      .mobile-card { border-radius: 1rem; padding: 1rem; }
    }

    /* Main content spacing */
    .main-content {
      margin-top: 80px;
      min-height: calc(100vh - 80px);
    }

    /* Animation delays for staggered effects */
    .anim-on { opacity: 0; }
  </style>
</head>
<body class="antialiased text-slate-800 bg-white selection:bg-brand-200/60">
    
    <!-- NAVBAR DENGAN DOMINASI KUNING KONTRAS -->
    <header id="topbar" class="fixed inset-x-0 top-0 z-40 bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-lg border-b border-brand-400 transition-all">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-4">
          <!-- Brand -->
          <a href="{{ route('resepsionis.index') }}" class="group flex items-center gap-3">
            <div class="relative">
              <div class="absolute inset-0 rounded-full bg-brand-800/30 blur-sm opacity-60 group-hover:opacity-80 transition"></div>
              <img
                src="/img/logoDprd.png"
                alt="Logo DPRD"
                class="relative h-10 w-10 md:h-12 md:w-12 object-contain
                       transition-transform duration-500
                       motion-safe:group-hover:scale-110 motion-safe:group-hover:rotate-3"
              />
            </div>
            <div class="leading-tight text-left">
              <div class="font-extrabold tracking-tight text-white text-sm sm:text-base">E-Tamu DPRD</div>
              <div class="text-[10px] sm:text-[11px] text-brand-100">Kota Gorontalo · Panel Resepsionis</div>
            </div>
          </a>

          <!-- Nav -->
          <nav class="hidden md:flex items-center gap-1 text-sm">
            <!-- Dashboard -->
            <a href="{{ route('resepsionis.index') }}"
               class="group tab-btn {{ request()->routeIs('resepsionis.index') ? 'is-active' : 'with-underline' }} text-white hover:text-brand-100">
              <span class="inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4 text-brand-100 transition-transform duration-300 motion-safe:group-hover:-translate-y-0.5"
                     viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"/>
                  <path d="M12 6a1 1 0 0 0-1 1v5a1 1 0 0 0 2 0V7a1 1 0 0 0-1-1z"/>
                  <path d="M12 16a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                </svg>
                Dashboard
              </span>
            </a>

            <!-- Data Tamu -->
            <a href="{{ route('resepsionis.datatamu') }}"
               class="group tab-btn {{ request()->routeIs('resepsionis.datatamu') ? 'is-active' : 'with-underline' }} text-white hover:text-brand-100">
              <span class="inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4 text-brand-100 transition-transform duration-300 motion-safe:group-hover:scale-110"
                     viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  <path d="M0 0h24v24H0z" fill="none"/>
                </svg>
                Data Tamu
              </span>
            </a>
          </nav>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center gap-2">
            <!-- Mobile navigation menu -->
            <div x-data="{ open: false }" class="relative">
              <button @click="open = !open" class="p-2 rounded-lg text-white hover:bg-brand-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                </svg>
              </button>
              
              <div x-show="open" @click.away="open = false" 
                   class="absolute right-0 mt-2 w-48 rounded-xl bg-brand-700 shadow-lg ring-1 ring-brand-400 py-2 z-50">
                <a href="{{ route('resepsionis.index') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-sm text-white hover:bg-brand-600 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-100" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 1.293a1 1 0 00-1.414 0l-8 8a1 1 0 001.414 1.414L3 10.414V17a1 1 0 001 1h3a1 1 0 001-1v-2h4v2a1 1 0 001 1h3a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-8-8z"/>
                  </svg>
                  Dashboard
                </a>
                <a href="{{ route('resepsionis.datatamu') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-sm text-white hover:bg-brand-600 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-100" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                  </svg>
                  Data Tamu
                </a>
                <div class="my-1 h-px bg-brand-400"></div>
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-brand-100 hover:bg-brand-600 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-100" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 4.5A1.5 1.5 0 014.5 3h5A1.5 1.5 0 0111 4.5v1a.5.5 0 01-1 0v-1a.5.5 0 00-.5-.5h-5a.5.5 0 00-.5.5v11a.5.5 0 00.5.5h5a2 2 0 002-2v-1a.5.5 0 011 0v1A1.5 1.5 0 019.5 17h-5A1.5 1.5 0 013 15.5v-11z" clip-rule="evenodd"/>
                    <path d="M13.854 10.354a.5.5 0 010-.708l2-2a.5.5 0 11.707.708L15.707 9.5H8.5a.5.5 0 010-1h7.207l.854-.854a.5.5 0 11.707.707l-2 2a.5.5 0 01-.707 0z"/>
                  </svg>
                  Logout
                </a>
              </div>
            </div>

            <!-- Mobile kecil: badge role -->
            <div class="inline-flex items-center gap-2 rounded-full bg-brand-700 px-3 py-1 text-[11px] text-white">
              <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-brand-100 text-brand-700 text-xs font-semibold">R</span>
              <span class="text-sm hidden sm:inline">
                  @if(auth()->check())
                      {{ auth()->user()->full_name ?? auth()->user()->username ?? auth()->user()->email ?? 'Host' }}
                  @else
                      Host
                  @endif
              </span>
            </div>
          </div>

          <!-- Desktop: clock + role -->
          <div class="hidden md:flex items-center gap-4 relative">
            <div class="text-right leading-tight text-white">
              <div id="clock" class="font-semibold text-sm md:text-base">--:--:-- WITA</div>
              <div id="date" class="text-[11px] md:text-xs text-brand-100">—</div>
            </div>

            <!-- User button (avatar + label) -->
            <button
              id="userMenuButton"
              type="button"
              class="flex items-center gap-2 rounded-full bg-brand-700 hover:bg-brand-800 text-white px-2 py-1 focus:outline-none focus:ring-2 focus:ring-brand-300 transition-colors"
              aria-haspopup="true"
              aria-expanded="false"
              aria-controls="userMenu"
            >
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

            <!-- Dropdown -->
            <div
              id="userMenu"
              role="menu"
              aria-labelledby="userMenuButton"
              class="absolute right-0 top-12 w-52 origin-top-right rounded-xl border border-brand-400 bg-brand-700 shadow-lg ring-1 ring-black/5
                     invisible opacity-0 scale-95 pointer-events-none transition ease-out duration-150"
            >
              <div class="py-2">
                <div class="px-4 pb-2 text-[11px] text-brand-100 border-b border-brand-400">
                  Masuk sebagai <span class="font-semibold text-white">Resepsionis</span>
                </div>
                <a
                  href="{{ route('logout') }}"
                  class="flex items-center gap-2 px-3 py-2 text-sm text-brand-100 hover:bg-brand-600 transition-colors"
                  role="menuitem"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-100" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 4.5A1.5 1.5 0 014.5 3h5A1.5 1.5 0 0111 4.5v1a.5.5 0 01-1 0v-1a.5.5 0 00-.5-.5h-5a.5.5 0 00-.5.5v11a.5.5 0 00.5.5h5a2 2 0 002-2v-1a.5.5 0 011 0v1A1.5 1.5 0 019.5 17h-5A1.5 1.5 0 013 15.5v-11z" clip-rule="evenodd"/>
                    <path d="M13.854 10.354a.5.5 0 010-.708l2-2a.5.5 0 11.707.708L15.707 9.5H8.5a.5.5 0 010-1h7.207l.854-.854a.5.5 0 11.707.707l-2 2a.5.5 0 01-.707 0z"/>
                  </svg>
                  Logout
                </a>
              </div>
            </div>

            <!-- Laravel logout form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </div>
      <div class="hairline"></div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <!-- TOAST -->
    <div id="toast" class="hidden pointer-events-none fixed bottom-5 right-5 z-60 rounded-xl bg-emerald-600 px-4 py-3 text-white shadow-lg">Berhasil.</div>

    <!-- SCRIPTS -->
    <script>
      (function () {
        const btn = document.getElementById('userMenuButton');
        const menu = document.getElementById('userMenu');
        if (!btn || !menu) return;

        function openMenu() {
          menu.classList.remove('invisible', 'opacity-0', 'scale-95', 'pointer-events-none');
          menu.classList.add('opacity-100', 'scale-100');
          btn.setAttribute('aria-expanded', 'true');
        }
        function closeMenu() {
          menu.classList.add('invisible', 'opacity-0', 'scale-95', 'pointer-events-none');
          menu.classList.remove('opacity-100', 'scale-100');
          btn.setAttribute('aria-expanded', 'false');
        }
        function isOpen() {
          return !menu.classList.contains('invisible');
        }

        btn.addEventListener('click', (e) => {
          e.stopPropagation();
          isOpen() ? closeMenu() : openMenu();
        });

        // click outside
        document.addEventListener('click', (e) => {
          if (isOpen() && !menu.contains(e.target) && e.target !== btn && !btn.contains(e.target)) {
            closeMenu();
          }
        });

        // ESC to close
        document.addEventListener('keydown', (e) => {
          if (e.key === 'Escape' && isOpen()) closeMenu();
        });
      })();

      // Clock Gorontalo (WITA -> Asia/Makassar)
      function updateClock(){
        const tz = 'Asia/Makassar';
        const now = new Date();
        const time = new Intl.DateTimeFormat('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false,timeZone:tz}).format(now);
        const date = new Intl.DateTimeFormat('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric',timeZone:tz}).format(now);
        const clockEl = document.getElementById('clock');
        const dateEl  = document.getElementById('date');
        if (clockEl) clockEl.textContent = time + ' WITA';
        if (dateEl)  dateEl.textContent  = date.charAt(0).toUpperCase()+date.slice(1);
      }
      updateClock(); setInterval(updateClock, 1000);

      // Header shadow saat scroll
      const topbar = document.getElementById('topbar');
      function setShadow(){
        if (!topbar) return;
        if (window.scrollY > 6) topbar.classList.add('shadow');
        else topbar.classList.remove('shadow','bg-white/90');
      }
      setShadow(); window.addEventListener('scroll', setShadow, { passive:true });

      // Ripple efek
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

      // Counter
      function runCounters(scope){
        scope.querySelectorAll('.counter').forEach(el=>{
          const target = +el.dataset.target;
          const duration = 900, start = performance.now();
          const step = (now) => {
            const p = Math.min((now - start) / duration, 1);
            const value = Math.floor(p * target);
            el.textContent = value.toLocaleString('id-ID');
            if (p < 1) requestAnimationFrame(step);
          };
          requestAnimationFrame(step);
        });
      }

      // Tabs / Views
      const viewsWrap = document.getElementById('viewsWrap');
      const views = document.getElementById('views');
      const sections = views ? [...views.children] : [];
      const tabBtns = document.querySelectorAll('.tab-btn');

      function setWrapHeight(index){
        if (!viewsWrap || !sections[index]) return;
        viewsWrap.style.height = sections[index].offsetHeight + 'px';
      }
      function setActiveBtn(index){
        tabBtns.forEach((b,i)=>{
          if(i===index){ b.classList.add('is-active'); b.classList.remove('with-underline'); b.setAttribute('aria-current','page'); }
          else { b.classList.remove('is-active'); b.classList.add('with-underline'); b.removeAttribute('aria-current'); }
        });
      }

      let active = 0;
      function activate(index){
        if(!views || index===active) return;
        active = index;
        views.style.transform = `translateX(-${index * 100}%)`;
        setWrapHeight(index);
        setActiveBtn(index);
        const scope = sections[index];
        scope.querySelectorAll('.anim-on').forEach((el,k)=>{ el.style.animationDelay = (k*40)+'ms'; el.classList.add('animate-fadeUp'); });
        runCounters(scope);
      }

      window.addEventListener('load', ()=>{
        if (!viewsWrap || !views || sections.length === 0) return;
        setWrapHeight(0); setActiveBtn(0); runCounters(sections[0]);
      });
      tabBtns.forEach(btn=>{ btn.addEventListener('click', ()=> activate(+btn.dataset.tab)); });
      document.querySelectorAll('[data-tab-jump]').forEach(btn=>{ btn.addEventListener('click', ()=> activate(+btn.getAttribute('data-tab-jump'))); });
      window.addEventListener('resize', ()=> setWrapHeight(active));

      // ===== Helpers lock scroll (dipakai semua modal) =====
      function lockBodyScroll() {
        document.body.style.overflow = 'hidden';
      }
      function unlockBodyScroll() {
        document.body.style.overflow = '';
      }

      // ===== Modal multi-step (Tambah Tamu) =====
      const modal     = document.getElementById('applyModal');
      const modalCard = document.getElementById('modalCard');
      const openBtn   = document.getElementById('btnTambah');
      const closeBtn  = document.getElementById('closeModalBtn');
      const cancelBtn = document.getElementById('cancelBtn');
      const visitForm = document.getElementById('visitForm');

      // Stepper refs
      const step1      = document.getElementById('step1');
      const step2      = document.getElementById('step2');
      const toStep2    = document.getElementById('toStep2');
      const theBackBtn = document.getElementById('backTo1');
      const step1State = document.getElementById('step1State');
      const step2State = document.getElementById('step2State');

      function openModal(){
        if (!modal || !modalCard) return;

        modal.classList.remove('invisible','opacity-0');
        modal.setAttribute('aria-hidden','false');
        lockBodyScroll(); // ⬅️ kunci scroll background

        // Reset ke Step 1 saat dibuka
        if (step1 && step2 && step1State && step2State) {
          step2.classList.add('hidden'); step1.classList.remove('hidden');
          step1State.classList.add('bg-brand-500','border-brand-500','text-white');
          step2State.classList.remove('bg-brand-500','text-white');
          step2State.classList.add('border-slate-300','text-slate-500');
        }

        modalCard.style.transform='translateY(16px) scale(.98)';
        modalCard.style.opacity='0';
        requestAnimationFrame(()=>{
          modalCard.style.transition='transform .22s ease-out, opacity .22s ease-out';
          modalCard.style.transform='translateY(0) scale(1)';
          modalCard.style.opacity='1';
        });
      }
      function closeModal(){
        if (!modal || !modalCard) return;

        modalCard.style.transform='translateY(12px) scale(.98)';
        modalCard.style.opacity='0';
        setTimeout(()=>{
          modal.classList.add('opacity-0');
          modal.setAttribute('aria-hidden','true');
          setTimeout(()=>{
            modal.classList.add('invisible');
            unlockBodyScroll(); // ⬅️ kembalikan scroll background
          },180);
        },150);
      }

      if (openBtn)  openBtn.addEventListener('click', openModal);
      if (closeBtn) closeBtn.addEventListener('click', closeModal);
      if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
      if (modal) {
        modal.addEventListener('click', e=>{ if(e.target===modal) closeModal(); });
      }
      document.addEventListener('keydown', e=>{ if(e.key==='Escape' && modal && modal.getAttribute('aria-hidden')!=='true') closeModal(); });

      // Stepper actions
      if (toStep2 && step1 && step2 && step1State && step2State) {
        toStep2.addEventListener('click', ()=>{
          step1.classList.add('hidden'); step2.classList.remove('hidden');
          step1State.classList.remove('bg-brand-500','text-white');
          step1State.classList.add('border-brand-500','text-brand-700');
          step2State.classList.remove('border-slate-300','text-slate-500');
          step2State.classList.add('bg-brand-500','border-brand-500','text-white');
        });
      }
      if (theBackBtn && step1 && step2 && step1State && step2State) {
        theBackBtn.addEventListener('click', ()=>{
          step2.classList.add('hidden'); step1.classList.remove('hidden');
          step2State.classList.remove('bg-brand-500','text-white');
          step2State.classList.add('border-slate-300','text-slate-500');
          step1State.classList.add('bg-brand-500','border-brand-500','text-white');
        });
      }

      // Toggle list pihak yang dituju
      const topRadios = document.querySelectorAll('input[name="kategori_pihak_top"]');
      const lists = {
        pimpinan: document.getElementById('pimpinanList'),
        akd: document.getElementById('akdList'),
        sekretariat: document.getElementById('sekretariatList'),
      };
      topRadios.forEach(r => r.addEventListener('change', (e) => {
        Object.values(lists).forEach(el => el && el.classList.add('hidden'));
        const target = lists[e.target.value];
        if (target) target.classList.remove('hidden');
      }));

      // Toast contoh saat submit (submit tetap jalan ke server)
      const toast = document.getElementById('toast');
      if (visitForm && toast) {
        visitForm.addEventListener('submit', ()=>{
          toast.classList.remove('hidden');
          toast.classList.add('animate-fadeUp');
          setTimeout(()=>toast.classList.add('hidden'), 2200);
        });
      }

      // ===== Modal Detail Tamu (untuk tombol "Detail") =====
      const dModal   = document.getElementById('detailModal');
      const dCard    = document.getElementById('detailCard');
      const dClose   = document.getElementById('detailCloseBtn');
      const dClose2  = document.getElementById('detailCloseBtn2');

      // Field di dalam modal (pakai ID yang ada pada markup)
      const f = {
        nama:     document.getElementById('nama'),
        email:    document.getElementById('email'),
        nohp:     document.getElementById('no_hp'),
        jumlah:   document.getElementById('jumlah'),
        instansi: document.getElementById('instansi_nama'),
        tanggal:  document.getElementById('tanggal_kunjungan'),
        waktu:    document.getElementById('waktu_kunjungan'),
        bertemu:  document.getElementById('subnama'),
      };

      function openDetail(data){
        if (!dModal || !dCard) return;

        if (f.nama)     f.nama.textContent     = data.nama ?? '—';
        if (f.email)    f.email.textContent    = data.email ?? '—';
        if (f.nohp)     f.nohp.textContent     = data.nohp ?? '—';
        if (f.jumlah)   f.jumlah.textContent   = data.jumlahPeserta ?? data.jumlah ?? '—';
        if (f.instansi) f.instansi.textContent = data.instansi ?? '—';
        if (f.tanggal)  f.tanggal.textContent  = data.tanggal ?? '—';
        if (f.waktu)    f.waktu.textContent    = data.waktu ?? '—';
        if (f.bertemu)  f.bertemu.textContent  = data.bertemuDengan ?? '—';

        dModal.classList.remove('invisible','opacity-0');
        dModal.setAttribute('aria-hidden','false');
        lockBodyScroll(); // ⬅️ kunci scroll saat modal detail juga

        dCard.style.transform='translateY(14px) scale(.98)';
        dCard.style.opacity='0';
        requestAnimationFrame(()=>{
          dCard.style.transition='transform .18s ease-out, opacity .18s ease-out';
          dCard.style.transform='translateY(0) scale(1)';
          dCard.style.opacity='1';
        });
      }

      function closeDetail(){
        if (!dModal || !dCard) return;

        dCard.style.transform='translateY(10px) scale(.98)';
        dCard.style.opacity='0';
        setTimeout(()=>{
          dModal.classList.add('opacity-0');
          dModal.setAttribute('aria-hidden','true');
          setTimeout(()=>{
            dModal.classList.add('invisible');
            unlockBodyScroll(); // ⬅️ balikin scroll
          },160);
        },120);
      }

      // Delegasi klik untuk semua tombol .btnDetail (termasuk yang di-render Blade)
      document.addEventListener('click', (e)=>{
        const btn = e.target.closest('.btnDetail');
        if(!btn) return;
        const d = btn.dataset;
        openDetail({
          nama: d.nama,
          email: d.email,
          nohp: d.nohp,
          jumlahPeserta: d.jumlahPeserta,
          instansi: d.instansi,
          tanggal: d.tanggal,
          waktu: d.waktu,
          bertemuDengan: d.bertemuDengan
        });
      });

      // Tutup modal detail
      [dClose, dClose2].forEach(el => el && el.addEventListener('click', closeDetail));
      if (dModal) {
        dModal.addEventListener('click', (e)=>{ if(e.target === dModal) closeDetail(); });
      }
      document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && dModal && dModal.getAttribute('aria-hidden')!=='true') closeDetail(); });

      // Animasi untuk elemen dengan class anim-on
      document.addEventListener('DOMContentLoaded', function() {
        const animElements = document.querySelectorAll('.anim-on');
        animElements.forEach((el, index) => {
          el.style.animationDelay = (index * 40) + 'ms';
          el.classList.add('animate-fadeUp');
        });
      });
    </script>
    
    @yield('scripts')
</body>
</html>