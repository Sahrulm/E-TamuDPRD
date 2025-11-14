{{-- resources/views/host.blade.php --}}
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
            },
            night: {
              900:'#0B1220',
              800:'#0F1930',
            },
          },
          boxShadow: {
            soft: '0 10px 30px -12px rgba(0,0,0,.12)',
            lift: '0 18px 40px -20px rgba(0,0,0,.25)',
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
      background-color:#FFC233;
      color:#0B1220;
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
  <header id="topbar" class="fixed inset-x-0 top-0 z-40 bg-brand-500/90 text-slate-900 backdrop-blur border-b border-brand-400/40 transition-all">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-3">
        <button
          type="button"
          class="group flex items-center gap-3"
          onclick="scrollTo({top:0,behavior:'smooth'})"
          aria-label="Ke atas"
        >
          <div class="relative">
            <div class="absolute inset-0 rounded-full bg-black/10 blur-sm opacity-40 group-hover:opacity-70 transition"></div>
            <img
              src="/img/logoDprd.png"
              alt="Logo DPRD"
              class="relative h-10 w-10 md:h-12 md:w-12 object-contain transition-transform duration-500 motion-safe:group-hover:scale-110 motion-safe:group-hover:rotate-3"
            />
          </div>
          <div class="leading-tight text-left">
            <div class="font-extrabold tracking-tight text-slate-900">E-Tamu DPRD</div>
            <div class="text-xs text-amber-100/90">Kota Gorontalo · Panel Host</div>
          </div>
        </button>

        <!-- Desktop nav -->
        <nav class="hidden md:flex items-center gap-2 text-sm text-amber-50">
          <button type="button" data-tab-target="dashboard" class="tab-btn with-underline is-active">
            <span class="inline-flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M3 3h6v6H3V3zM11 3h6v6h-6V3zM3 11h6v6H3v-6zM11 11h6v6h-6v-6z"/>
              </svg>
              Dashboard
            </span>
          </button>
          <button type="button" data-tab-target="data" class="tab-btn with-underline">
            <span class="inline-flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M8 5.25a3.25 3.25 0 100 6.5 3.25 3.25 0 000-6.5zM1.75 16a6.25 6.25 0 0112.5 0v1.25h-12.5V16z"/>
                <path d="M15.5 4.75a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM18 17.25V16a3.5 3.5 0 00-5.67-2.82 a7.47 7.47 0 012.92 4.07H18z"/>
              </svg>
              Data Pengajuan
            </span>
          </button>
        </nav>

        <!-- Kanan -->
        <div class="hidden sm:flex items-center gap-4">
          <div class="text-right leading-tight text-amber-50">
            <div id="clock" class="font-semibold text-sm md:text-base">--:--:-- WITA</div>
            <div id="date" class="text-[11px] md:text-xs text-amber-100/90">—</div>
          </div>

          <!-- USER DROPDOWN -->
          <div class="relative" x-data="{open:false}" @keydown.escape.window="open=false">
            <button class="flex items-center gap-2 px-2 py-1 rounded-lg bg-brand-400/40 hover:bg-brand-400/70 text-amber-50 transition"
                    @click="open=!open" :aria-expanded="open">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-brand-700 ring-2 ring-amber-200/80 shadow-soft">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.761 0 5-2.462 5-5.5S14.761 1 12 1 7 3.462 7 6.5 9.239 12 12 12zm0 2c-3.866 0-7 2.582-7 5.769V22h14v-2.231C19 16.582 15.866 14 12 14z"/>
                </svg>
              </span>
              <span class="text-sm hidden sm:inline">Host</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-100/90" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>

            <div x-cloak x-show="open" x-transition.origin.top.right @click.outside="open=false"
                 class="absolute right-0 mt-2 w-52 rounded-xl border border-amber-100 bg-white shadow-lift overflow-hidden">
              <div class="py-2">
                <div class="px-4 pb-2 text-xs text-slate-500 border-b border-brand-50">
                  Masuk sebagai <span class="font-semibold text-slate-700">Host</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="button"
                          class="w-full text-left px-4 py-2.5 text-sm hover:bg-brand-50/80 flex items-center gap-2 text-rose-700"
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
        <div class="sm:hidden">
          <div class="inline-flex items-center gap-2 rounded-full bg-brand-400/40 px-3 py-1 text-[11px] text-amber-50">
            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-amber-100 text-brand-700 text-xs font-semibold">H</span>
            <span>Host</span>
          </div>
        </div>
      </div>
    </div>
    <div class="hairline"></div>
  </header>

  <!-- HERO -->
  <section class="pt-24 sm:pt-28 pb-4 sm:pb-6 bg-gradient-to-r from-brand-50 via-brand-100 to-amber-100 relative overflow-hidden">
    <div class="pointer-events-none absolute -left-24 -top-32 h-72 w-72 rounded-full border border-brand-200/60 bg-gradient-to-br from-amber-100/70 to-transparent"></div>
    <div class="pointer-events-none absolute -right-20 -bottom-32 h-80 w-80 rounded-full border-4 border-brand-100/70 bg-gradient-to-tr from-brand-50/70 to-transparent"></div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-end justify-between gap-4">
      <div class="relative z-10 space-y-2 sm:space-y-3">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-brand-700 shadow-soft">
          <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-brand-500 text-[10px] text-white">◎</span>
          Mode Host · Real-time
        </span>
        <h1 id="heroTitle" class="text-xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
          Dashboard
        </h1>
        <p id="heroSub" class="text-slate-700 mt-1 sm:mt-2 text-sm sm:text-base max-w-xl">
          Ringkasan dan daftar pengajuan tamu yang berstatus menunggu.
        </p>
      </div>

      <!-- Mobile tab mini (atas) -->
      <div class="md:hidden relative z-10">
        <div class="inline-flex rounded-full bg-white/90 p-1 shadow-soft ring-1 ring-amber-100">
          <button type="button" data-tab-target="dashboard"
                  class="tab-btn is-active text-xs px-3 py-1.5">
            Dashboard
          </button>
          <button type="button" data-tab-target="data"
                  class="tab-btn with-underline text-xs px-3 py-1.5">
            Data
          </button>
        </div>
      </div>
    </div>
  </section>

  {{-- =============================== KONTEN =============================== --}}
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6 sm:space-y-8">

    {{-- DASHBOARD (metrik + kartu) --}}
    <section id="panel-dashboard" class="animate-fadeUp">
      @php
        $totalHariIni = $totalHariIni ?? 0;
        $diterimaMingguIni = $diterimaMingguIni ?? 0;
        $ditolakMingguIni = $ditolakMingguIni ?? 0;
      @endphp

      <!-- Ringkasan status -->
      <div class="mb-4 sm:mb-6 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-600">
          <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulseSoft"></span>
          <span>Sistem aktif · Data diperbarui secara real-time</span>
        </div>
        <div class="inline-flex flex-wrap gap-2 text-[11px] sm:text-xs">
          <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-emerald-700">
            <span class="h-1.5 w-3 rounded-full bg-emerald-500"></span>
            Diterima
          </span>
          <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-rose-700">
            <span class="h-1.5 w-3 rounded-full bg-rose-500"></span>
            Ditolak
          </span>
          <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-amber-700">
            <span class="h-1.5 w-3 rounded-full bg-amber-400"></span>
            Menunggu
          </span>
        </div>
      </div>

      <!-- Metrik (stack di mobile) -->
      <div class="grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-3 mb-4 sm:mb-6">
        <div class="rounded-2xl border border-amber-100 bg-white/90 p-4 sm:p-5 shadow-soft relative overflow-hidden">
          <div class="absolute -right-6 -top-6 h-16 w-16 rounded-full bg-gradient-to-br from-brand-300/60 to-amber-300/30"></div>
          <div class="relative">
            <div class="flex items-center justify-between gap-2">
              <div class="text-slate-500 text-xs sm:text-sm">Total Pengajuan Hari Ini</div>
              <div class="inline-flex items-center justify-center h-7 w-7 rounded-xl bg-amber-100 text-brand-700 text-xs font-semibold">
                H
              </div>
            </div>
            <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-slate-900">
              {{ $totalHariIni }}
            </div>
          </div>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4 sm:p-5 shadow-soft relative overflow-hidden">
          <div class="absolute -right-6 -top-6 h-16 w-16 rounded-full bg-gradient-to-br from-emerald-400/40 to-emerald-300/10"></div>
          <div class="relative">
            <div class="flex items-center justify-between gap-2">
              <div class="text-emerald-900/80 text-xs sm:text-sm">Diterima Minggu Ini</div>
              <div class="inline-flex items-center justify-center h-7 w-7 rounded-xl bg-white/80 text-emerald-600 text-xs font-semibold">
                ✓
              </div>
            </div>
            <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-emerald-700">
              {{ $diterimaMingguIni }}
            </div>
          </div>
        </div>
        <div class="rounded-2xl border border-rose-100 bg-rose-50/70 p-4 sm:p-5 shadow-soft relative overflow-hidden">
          <div class="absolute -right-6 -top-6 h-16 w-16 rounded-full bg-gradient-to-br from-rose-400/40 to-rose-300/10"></div>
          <div class="relative">
            <div class="flex items-center justify-between gap-2">
              <div class="text-rose-900/80 text-xs sm:text-sm">Ditolak Minggu Ini</div>
              <div class="inline-flex items-center justify-center h-7 w-7 rounded-xl bg-white/80 text-rose-600 text-xs font-semibold">
                !
              </div>
            </div>
            <div class="mt-1 text-2xl sm:text-3xl font-extrabold text-rose-600">
              {{ $ditolakMingguIni }}
            </div>
          </div>
        </div>
      </div>

      <!-- Kartu pengajuan ringkas -->
      <div class="grid gap-3 sm:gap-4 [grid-template-columns:repeat(1,minmax(0,1fr))] sm:[grid-template-columns:repeat(2,minmax(0,1fr))] xl:[grid-template-columns:repeat(3,minmax(0,1fr))]">
        @php $pengajuan = $pengajuan ?? []; @endphp
        @forelse($pengajuan as $row)
          @php
            $id = $row->id ?? uniqid();
            $nama = $row->nama ?? '—';
            $hp = $row->no_hp ?? '—';
            $instansi = $row->instansi_nama ?? '—';
            $bertemu = $row->bertemu_dengan ?? '—';
          @endphp
          <article class="rounded-2xl border border-amber-100 bg-white p-4 sm:p-5 shadow-soft transition hover:shadow-lift hover:border-brand-200 hover:-translate-y-0.5"
                   data-item-id="{{ $id }}">
            <div class="flex items-start justify-between gap-3">
              <div class="mt-1 grid grid-cols-2 gap-x-4 gap-y-1 sm:grid-cols-2">
                <h3 class="col-span-2 text-base sm:text-lg font-bold text-slate-900">
                  {{ $nama }}
                </h3>
                <div class="text-slate-500 text-xs sm:text-sm">No. HP</div>
                <div class="font-medium text-right sm:text-left text-sm">{{ $hp }}</div>
                <div class="text-slate-500 text-xs sm:text-sm">Instansi</div>
                <div class="font-medium text-right sm:text-left text-sm">{{ $instansi }}</div>
                <div class="text-slate-500 text-xs sm:text-sm">Bertemu</div>
                <div class="font-medium text-right sm:text-left text-sm">{{ $bertemu }}</div>
              </div>
              <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-[11px] font-medium text-amber-700 border border-amber-100">
                Menunggu
              </span>
            </div>

            <div class="mt-4 flex flex-col sm:flex-row sm:items-center gap-2">
              <form method="POST" action="{{ route('host.kunjungan.terima', $id) }}" class="inline w-full sm:w-auto">
                @csrf
                <button type="submit" class="ripple w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 font-semibold text-white active:scale-[.99] focus:outline-none focus:ring-2 focus:ring-brand-400/70">
                  Terima
                </button>
              </form>
              <button type="button" 
                      class="btn-tolak ripple w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-200 px-4 py-2 font-semibold text-slate-700 active:scale-[.99] focus:outline-none focus:ring-2 focus:ring-amber-300/80"
                      data-id="{{ $id }}"
                      data-nama="{{ $nama }}">
                Tolak
              </button>
            </div>
          </article>
        @empty
          <div class="col-span-full rounded-2xl border border-amber-100 bg-white/90 p-6 sm:p-8 text-center text-slate-500">
            Belum ada pengajuan menunggu.
          </div>
        @endforelse
      </div>
    </section>

    {{-- DATA PENGAJUAN (desktop: tabel; mobile: kartu list) --}}
    <section id="panel-data" class="hidden animate-fadeUp">
      @php
        $tamuMenunggu = $tamuMenunggu ?? $pengajuan ?? [];
      @endphp

      <div class="rounded-2xl border border-amber-100 bg-white/95 p-4 sm:p-5 shadow-soft space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
          <div>
            <h2 class="text-base sm:text-lg font-semibold text-slate-900 flex items-center gap-2">
              <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-brand-500/90 text-white text-xs font-bold shadow-soft">
                DP
              </span>
              Data Pengajuan Menunggu
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
              Seluruh tamu yang melakukan pengajuan dan masih berstatus menunggu.
            </p>
          </div>
          <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] text-amber-700 border border-amber-100">
              <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
              Scroll secara horizontal untuk melihat semua kolom
            </span>
          </div>
        </div>

        <!-- MOBILE LIST (≤ md) -->
        <div class="md:hidden space-y-3">
          @forelse($tamuMenunggu as $row)
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

            <article class="rounded-xl border border-amber-100 bg-amber-50/60 p-4 shadow-soft">
              <div class="flex items-start justify-between gap-3">
                <h3 class="font-bold text-base leading-tight text-slate-900">
                  {{ $namaTamu }}
                </h3>
                <div class="text-right text-[11px] text-slate-600">
                  <div>{{ $dt->translatedFormat('d M Y') }}</div>
                  <div class="font-semibold text-slate-800">{{ $dt->format('H:i') }} WITA</div>
                </div>
              </div>

              <dl class="mt-3 grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                <dt class="text-slate-500">HP</dt>
                <dd class="font-medium text-right sm:text-left">{{ $hpTamu }}</dd>
                <dt class="text-slate-500">Email</dt>
                <dd class="font-medium text-right sm:text-left truncate">{{ $emailTamu }}</dd>
                <dt class="text-slate-500">Instansi</dt>
                <dd class="font-medium text-right sm:text-left">{{ $namaInstansi }}</dd>
                <dt class="text-slate-500">Bertemu</dt>
                <dd class="font-medium text-right sm:text-left">{{ $bertemu }}</dd>
                <dt class="text-slate-500">Peserta</dt>
                <dd class="font-medium text-right sm:text-left">
                  {{ is_numeric($jumlah) ? number_format($jumlah) : ($jumlah ?? '—') }}
                </dd>
              </dl>

              <div class="mt-2 text-sm">
                <div class="text-slate-500">Keperluan</div>
                <div class="font-medium line-clamp-3 text-slate-800">
                  {{ $keperluan }}
                </div>
              </div>

              <div class="mt-3 flex items-center gap-2">
                @if($dokUrl)
                  <a href="{{ $dokUrl }}" target="_blank"
                     class="inline-flex items-center gap-1 rounded-lg bg-white px-3 py-1.5 text-xs hover:bg-amber-100 text-slate-800 border border-amber-100 shadow-soft">
                    Lihat Dokumen
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M12.293 2.293a1 1 0 011.414 0L18 6.586V8a2 2 0 01-2 2h-1V8a1 1 0 00-1-1h-2V6a2 2 0 012-2h1V3.414l-3.293-3.293z"/>
                      <path d="M3 6a2 2 0 012-2h6v2H5v10h10v-6h2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V6z"/>
                    </svg>
                  </a>
                @endif
              </div>

              <div class="mt-4 grid grid-cols-2 gap-2">
                @if($id)
                  <form method="POST" action="{{ route('host.kunjungan.terima', $id) }}">
                    @csrf
                    <button type="submit" class="ripple w-full inline-flex items-center justify-center rounded-lg bg-brand-500 hover:bg-brand-600 px-3 py-2 text-white font-semibold active:scale-[.99] focus:outline-none focus:ring-2 focus:ring-brand-400/70">
                      Terima
                    </button>
                  </form>
                  <button type="button" 
                          class="btn-tolak ripple w-full inline-flex items-center justify-center rounded-lg bg-white hover:bg-amber-100 border border-amber-200 px-3 py-2 text-slate-700 font-semibold active:scale-[.99] focus:outline-none focus:ring-2 focus:ring-amber-300/80"
                          data-id="{{ $id }}"
                          data-nama="{{ $namaTamu }}">
                    Tolak
                  </button>
                @else
                  <div class="col-span-2 text-center text-slate-400 text-sm">—</div>
                @endif
              </div>
            </article>
          @empty
            <div class="rounded-xl border border-amber-100 bg-amber-50/60 p-6 text-center text-slate-500">
              Tidak ada tamu menunggu verifikasi.
            </div>
          @endforelse
        </div>

        <!-- DESKTOP TABLE (≥ md) -->
        <div class="hidden md:block">
          <div class="overflow-x-auto rounded-xl border border-amber-100 bg-amber-50/40">
            <table class="min-w-[1400px] w-full text-sm">
              <thead class="bg-amber-100/80">
                <tr class="text-left text-slate-700">
                  <th class="py-3 pl-3 pr-4 sticky-col text-xs font-semibold tracking-wide">Aksi</th>
                  <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Nama</th>
                  <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Email</th>
                  <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Nomor HP</th>
                  <th class="py-3 pr-4 text-center text-xs font-semibold tracking-wide">Nama Instansi</th>
                  <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Keperluan</th>
                  <th class="py-3 pr-4 text-center text-xs font-semibold tracking-wide">Jumlah Peserta</th>
                  <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Tanggal Kunjungan</th>
                  <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Waktu Kunjungan</th>
                  <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Bertemu Dengan</th>
                  <th class="py-3 pr-4 text-xs font-semibold tracking-wide">Dokumen</th>
                </tr>
              </thead>
              <tbody class="align-top bg-white/90">
                @forelse($tamuMenunggu as $row)
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

                  <tr class="border-t border-amber-100 hover:bg-amber-50/40">
                    <td class="py-3 pl-3 pr-4 sticky-col">
                      <div class="flex items-center gap-2">
                        @if($id)
                          <form method="POST" action="{{ route('host.kunjungan.terima', $id) }}" class="inline">
                            @csrf
                            <button type="submit" class="ripple inline-flex items-center justify-center rounded-lg bg-brand-500 hover:bg-brand-600 px-3 py-1.5 text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-400/70 text-xs">
                              Terima
                            </button>
                          </form>
                          <button type="button" 
                                  class="btn-tolak ripple inline-flex items-center justify-center rounded-lg bg-white hover:bg-amber-100 border border-amber-200 px-3 py-1.5 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-amber-300/80 text-xs"
                                  data-id="{{ $id }}"
                                  data-nama="{{ $namaTamu }}">
                            Tolak
                          </button>
                        @else
                          <span class="text-slate-400 text-xs">—</span>
                        @endif
                      </div>
                    </td>

                    <td class="py-3 pr-4 font-medium text-slate-900">{{ $namaTamu }}</td>
                    <td class="py-3 pr-4 text-slate-700">{{ $emailTamu }}</td>
                    <td class="py-3 pr-4 whitespace-nowrap text-slate-700">{{ $hpTamu }}</td>
                    <td class="py-3 pr-4 text-center text-slate-800">{{ $namaInstansi }}</td>
                    <td class="py-3 pr-4 max-w-[320px] text-slate-700">
                      <span class="block truncate" title="{{ $keperluan }}">{{ $keperluan }}</span>
                    </td>
                    <td class="py-3 pr-4 text-center text-slate-800">
                      {{ is_numeric($jumlah) ? number_format($jumlah) : ($jumlah ?? '—') }}
                    </td>
                    <td class="py-3 pr-4 text-slate-700">{{ $dt->translatedFormat('d M Y') }}</td>
                    <td class="py-3 pr-4 text-slate-800">{{ $dt->format('H:i') }} WITA</td>
                    <td class="py-3 pr-4 text-slate-700">{{ $bertemu }}</td>
                    <td class="py-3 pr-4">
                      @if($dokUrl)
                        <a href="{{ $dokUrl }}" target="_blank"
                           class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-3 py-1.5 text-[11px] hover:bg-amber-100 text-slate-800 border border-amber-200">
                          Lihat
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M12.293 2.293a1 1 0 011.414 0L18 6.586V8a2 2 0 01-2 2h-1V8a1 1 0 00-1-1h-2V6a2 2 0 012-2h1V3.414l-3.293-3.293z"/>
                            <path d="M3 6a2 2 0 012-2h6v2H5v10h10v-6h2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V6z"/>
                          </svg>
                        </a>
                      @else
                        <span class="text-slate-400 text-xs">—</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr class="border-t border-amber-100">
                    <td colspan="11" class="py-8 text-center text-slate-500">
                      Tidak ada tamu menunggu verifikasi.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </section>
  </main>

  <!-- MODAL TOLAK dengan input alasan -->
  <div id="modal-tolak" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden place-items-center p-4" role="dialog" aria-hidden="true">
    <div class="w-full max-w-md bg-white rounded-2xl p-6 shadow-2xl animate-scaleIn border border-amber-100">
      <div class="flex items-start justify-between">
        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
          <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-rose-500 text-white text-xs font-bold">!</span>
          Tolak Pengajuan
        </h3>
        <button type="button" id="modalTolakClose" class="ripple rounded-lg px-2 py-1 text-slate-500 hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-amber-200">
          ✕
        </button>
      </div>

      <form id="formTolak" method="POST" action="">
        @csrf
        <div class="mt-4">
          <label for="alasan" class="block text-sm font-medium text-slate-700 mb-1">Alasan Penolakan</label>
          <textarea id="alasan" name="alasan" rows="3" class="w-full px-3 py-2 border border-amber-200 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500" placeholder="Masukkan alasan penolakan..." required></textarea>
        </div>

        <div class="mt-6 flex flex-wrap justify-end gap-2">
          <button type="button" id="modalTolakClose2" class="ripple rounded-xl border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-amber-200">
            Batal
          </button>
          <button type="submit" class="ripple rounded-xl bg-rose-600 px-4 py-2 font-semibold text-white focus:outline-none focus:ring-2 focus:ring-rose-500">
            Tolak Pengajuan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL DETAIL (opsional) -->
  <div id="detail" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden place-items-center p-4" role="dialog" aria-hidden="true">
    <div class="w-full max-w-2xl bg-white rounded-2xl p-6 shadow-2xl animate-scaleIn border border-amber-100">
      <div class="flex items-start justify-between">
        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
          <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-brand-500 text-white text-xs font-bold">DP</span>
          Detail Pengajuan
        </h3>
        <button type="button" id="detailClose" class="ripple rounded-lg px-2 py-1 text-slate-500 hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-amber-200">
          ✕
        </button>
      </div>

      <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-y-3 gap-x-4 text-sm">
        <div class="text-slate-500">Nama</div><div class="sm:col-span-2 font-semibold text-slate-900" data-detail="nama">—</div>
        <div class="text-slate-500">Nomor HP</div><div class="sm:col-span-2 font-semibold text-slate-900" data-detail="hp">—</div>
        <div class="text-slate-500">Instansi</div><div class="sm:col-span-2 font-semibold text-slate-900" data-detail="instansi">—</div>
        <div class="text-slate-500">Bertemu Dengan</div><div class="sm:col-span-2 font-semibold text-slate-900" data-detail="bertemu">—</div>
      </div>

      <div class="mt-6 flex flex-wrap justify-end gap-2">
        <button type="button" id="detailClose2" class="ripple rounded-xl border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-amber-200">
          Tutup
        </button>
        <button type="button" class="ripple rounded-xl bg-emerald-600 px-4 py-2 font-semibold text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
          Terima
        </button>
        <button type="button" class="ripple rounded-xl bg-rose-600 px-4 py-2 font-semibold text-white focus:outline-none focus:ring-2 focus:ring-rose-500">
          Tolak
        </button>
      </div>
    </div>
  </div>

  <div id="toast" class="hidden pointer-events-none fixed bottom-5 right-5 z-60 rounded-xl bg-emerald-600 px-4 py-3 text-white shadow-lg">
    Perubahan disimpan.
  </div>

  <footer class="mt-4 pb-6 text-center text-[11px] text-slate-500">
    E-Tamu DPRD Kota Gorontalo · Panel Host
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
        topbar.classList.add('shadow','bg-brand-500/100');
      } else {
        topbar.classList.remove('shadow','bg-brand-500/100');
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

    // Tabs (desktop + mobile top-chip)
    const panelDashboard = document.getElementById('panel-dashboard');
    const panelData      = document.getElementById('panel-data');
    const tabButtons     = document.querySelectorAll('[data-tab-target]');
    const heroTitle      = document.getElementById('heroTitle');
    const heroSub        = document.getElementById('heroSub');

    function setActiveTab(tab){
      const isDashboard = tab !== 'data';
      if (panelDashboard && panelData) {
        panelDashboard.classList.toggle('hidden', !isDashboard);
        panelData.classList.toggle('hidden', isDashboard);
      }
      if (heroTitle) {
        heroTitle.textContent = isDashboard ? 'Dashboard' : 'Data Pengajuan';
      }
      if (heroSub) {
        heroSub.textContent   = isDashboard
          ? 'Ringkasan dan daftar pengajuan tamu yang berstatus menunggu.'
          : 'Seluruh tamu yang melakukan pengajuan berstatus menunggu.';
      }

      tabButtons.forEach(btn => {
        const match = btn.dataset.tabTarget === (isDashboard ? 'dashboard' : 'data');
        btn.classList.toggle('is-active', match);
        btn.classList.toggle('with-underline', !match);
      });

      history.replaceState(null, '', '#' + (isDashboard ? 'dashboard' : 'data'));
      if (window.innerWidth < 768) window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function initTab(){
      const fromHash = (location.hash || '').replace('#','');
      if(fromHash === 'data'){ setActiveTab('data'); }
      else { setActiveTab('dashboard'); }
    }

    tabButtons.forEach(btn => btn.addEventListener('click', () => setActiveTab(btn.dataset.tabTarget)));
    window.addEventListener('hashchange', () => initTab());
    initTab();

    // Modal tolak dengan alasan
    const modalTolak = document.getElementById('modal-tolak');
    const formTolak = document.getElementById('formTolak');
    const alasanInput = document.getElementById('alasan');
    const modalTolakClose = document.getElementById('modalTolakClose');
    const modalTolakClose2 = document.getElementById('modalTolakClose2');

    function openModalTolak(id, nama) {
      if (!modalTolak || !formTolak) return;
      
      // Set action form dengan ID yang benar
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

    // Modal detail (opsional)
    const modal  = document.getElementById('detail');
    const close1 = document.getElementById('detailClose');
    const close2 = document.getElementById('detailClose2');
    const fields = {
      nama:     document.querySelector('[data-detail="nama"]'),
      hp:       document.querySelector('[data-detail="hp"]'),
      instansi: document.querySelector('[data-detail="instansi"]'),
      bertemu:  document.querySelector('[data-detail="bertemu"]'),
    };
    function openDetail(data){
      if (!modal) return;
      if (fields.nama)     fields.nama.textContent     = data.nama || '—';
      if (fields.hp)       fields.hp.textContent       = data.hp || '—';
      if (fields.instansi) fields.instansi.textContent = data.instansi || '—';
      if (fields.bertemu)  fields.bertemu.textContent  = data.bertemu || '—';
      modal.classList.remove('hidden');
      modal.setAttribute('aria-hidden','false');
    }
    function closeDetail(){
      if (!modal) return;
      modal.classList.add('hidden');
      modal.setAttribute('aria-hidden','true');
    }
    if (close1) close1.addEventListener('click', closeDetail);
    if (close2) close2.addEventListener('click', closeDetail);
    if (modal) {
      modal.addEventListener('click', (e)=>{ if(e.target === modal) closeDetail(); });
      document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && !modal.classList.contains('hidden')) closeDetail(); });
    }
  </script>
</body>
</html>