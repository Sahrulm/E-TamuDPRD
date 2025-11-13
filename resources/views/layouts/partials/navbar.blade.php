<!-- NAVBAR -->
<header id="topbar" class="fixed inset-x-0 top-0 z-40 bg-brand-500/90 text-slate-900 backdrop-blur border-b border-brand-400/40 transition-all">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between py-3">
      <!-- Brand -->
      <a href="{{ route('resepsionis.index') }}" class="group flex items-center gap-3">
        <div class="relative">
          <div class="absolute inset-0 rounded-full bg-black/10 blur-sm opacity-40 group-hover:opacity-70 transition"></div>
          <img
            src="/img/logoDprd.png"
            alt="Logo DPRD"
            class="relative h-10 w-10 md:h-12 md:w-12 object-contain
                   transition-transform duration-500
                   motion-safe:group-hover:scale-110 motion-safe:group-hover:rotate-3"
          />
        </div>
        <div class="leading-tight text-left">
          <div class="font-extrabold tracking-tight text-slate-900 text-sm sm:text-base">E-Tamu DPRD</div>
          <div class="text-[10px] sm:text-[11px] text-amber-100/90">Kota Gorontalo · Panel Resepsionis</div>
        </div>
      </a>

      <!-- Nav -->
      <nav class="hidden md:flex items-center gap-2 text-sm text-amber-50">
        <!-- Dashboard -->
        <a href="{{ route('resepsionis.index') }}"
           class="group tab-btn {{ request()->routeIs('resepsionis.index') ? 'is-active' : 'with-underline' }}">
          <span class="inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4 text-amber-50 transition-transform duration-300 motion-safe:group-hover:-translate-y-0.5"
                 viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M12 4a9 9 0 1 0 9 9h-2a7 7 0 1 1-7-7V4z"/>
              <path d="M12 8a1 1 0 0 0-.894.553l-3 6a1 1 0 1 0 1.788.894L12 10.618l1.106 2.212A3.5 3.5 0 0 0 17 16a1 1 0 1 0 0-2 1.5 1.5 0 0 1-1.342-.829l-2.764-5.528A1 1 0 0 0 12 8z"/>
            </svg>
            Dashboard
          </span>
        </a>

        <!-- Data Tamu -->
        <a href="{{ route('resepsionis.datatamu') }}"
           class="group tab-btn {{ request()->routeIs('resepsionis.datatamu') ? 'is-active' : 'with-underline' }}">
          <span class="inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4 text-amber-50 transition-transform duration-300 motion-safe:group-hover:scale-110"
                 viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
              <path d="M2.25 20.25a7.75 7.75 0 0115.5 0v.75H2.25v-.75zM18 9.75a3 3 0 110-6 3 3 0 010 6z"/>
              <path d="M21.75 21h-3v-.75a6.25 6.25 0 00-3.42-5.53 7.73 7.73 0 013.92-1.22 6.5 6.5 0 012.5.48V21z"/>
            </svg>
            Data Tamu
          </span>
        </a>
      </nav>

      <!-- Mobile menu button -->
      <div class="md:hidden flex items-center gap-2">
        <!-- Mobile navigation menu -->
        <div x-data="{ open: false }" class="relative">
          <button @click="open = !open" class="p-2 rounded-lg text-amber-50 hover:bg-brand-400/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <div x-show="open" @click.away="open = false" 
               class="absolute right-0 mt-2 w-48 rounded-xl bg-white/95 backdrop-blur shadow-lg ring-1 ring-black/5 py-2 z-50">
            <a href="{{ route('resepsionis.index') }}" 
               class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-brand-50/80">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.707 1.293a1 1 0 00-1.414 0l-8 8a1 1 0 001.414 1.414L3 10.414V17a1 1 0 001 1h3a1 1 0 001-1v-2h4v2a1 1 0 001 1h3a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-8-8z"/>
              </svg>
              Dashboard
            </a>
            <a href="{{ route('resepsionis.datatamu') }}" 
               class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-brand-50/80">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
              </svg>
              Data Tamu
            </a>
            <div class="my-1 h-px bg-slate-200"></div>
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-600" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 4.5A1.5 1.5 0 014.5 3h5A1.5 1.5 0 0111 4.5v1a.5.5 0 01-1 0v-1a.5.5 0 00-.5-.5h-5a.5.5 0 00-.5.5v11a.5.5 0 00.5.5h5a2 2 0 002-2v-1a.5.5 0 011 0v1A1.5 1.5 0 019.5 17h-5A1.5 1.5 0 013 15.5v-11z" clip-rule="evenodd"/>
                <path d="M13.854 10.354a.5.5 0 010-.708l2-2a.5.5 0 11.707.708L15.707 9.5H8.5a.5.5 0 010-1h7.207l.854-.854a.5.5 0 11.707.707l-2 2a.5.5 0 01-.707 0z"/>
              </svg>
              Logout
            </a>
          </div>
        </div>

        <!-- Mobile kecil: badge role -->
        <div class="inline-flex items-center gap-2 rounded-full bg-brand-400/40 px-3 py-1 text-[11px] text-amber-50">
          <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-amber-100 text-brand-700 text-xs font-semibold">R</span>
          <span class="hidden xs:inline">Resepsionis</span>
        </div>
      </div>

      <!-- Desktop: clock + role -->
      <div class="hidden md:flex items-center gap-4 relative">
        <div class="text-right leading-tight text-amber-50">
          <div id="clock" class="font-semibold text-sm md:text-base">--:--:-- WITA</div>
          <div id="date" class="text-[11px] md:text-xs text-amber-100/90">—</div>
        </div>

        <!-- User button (avatar + label) -->
        <button
          id="userMenuButton"
          type="button"
          class="flex items-center gap-2 rounded-full bg-brand-400/40 hover:bg-brand-400/70 text-amber-50 px-2 py-1 focus:outline-none focus:ring-2 focus:ring-brand-500/40"
          aria-haspopup="true"
          aria-expanded="false"
          aria-controls="userMenu"
        >
          <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-brand-700 ring-2 ring-amber-200/80 shadow-soft">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.761 0 5-2.462 5-5.5S14.761 1 12 1 7 3.462 7 6.5 9.239 12 12 12zm0 2c-3.866 0-7 2.582-7 5.769V22h14v-2.231C19 16.582 15.866 14 12 14z"/>
            </svg>
          </span>
          <span class="text-sm">Resepsionis</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-100/90" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
          </svg>
        </button>

        <!-- Dropdown -->
        <div
          id="userMenu"
          role="menu"
          aria-labelledby="userMenuButton"
          class="absolute right-0 top-12 w-52 origin-top-right rounded-xl border border-amber-100 bg-white/95 backdrop-blur shadow-lg ring-1 ring-black/5
                 invisible opacity-0 scale-95 pointer-events-none transition ease-out duration-150"
        >
          <div class="py-2">
            <div class="px-4 pb-2 text-[11px] text-slate-500 border-b border-amber-50">
              Masuk sebagai <span class="font-semibold text-slate-700">Resepsionis</span>
            </div>
            <a href="{{ route('resepsionis.index') }}"
               class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-brand-50/80"
               role="menuitem">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.707 1.293a1 1 0 00-1.414 0l-8 8a1 1 0 001.414 1.414L3 10.414V17a1 1 0 001 1h3a1 1 0 001-1v-2h4v2a1 1 0 001 1h3a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-8-8z"/>
              </svg>
              Dashboard
            </a>
            <div class="my-1 h-px bg-amber-50"></div>
            <a
              href="{{ route('logout') }}"
              class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50"
              role="menuitem"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-600" viewBox="0 0 20 20" fill="currentColor">
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