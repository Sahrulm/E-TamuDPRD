{{-- resources/views/view/auth/login.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login — E-Tamu DPRD Kota Gorontalo</title>
  <link rel="icon" type="image/png" href="/img/logoDprd.png">

  <meta name="color-scheme" content="light">
  <style>
    /* === THEME === */
    :root{
      --brand-50: #FFF9EC;
      --brand-100:#FFF3D6;
      --brand-200:#FFE6AD;
      --brand-300:#FFD973;
      --brand-400:#FFCA3A; /* kuning utama */
      --brand-500:#FFB300; /* kuning aksen */
      --brand-600:#E6A100;
      --ink-900:#0B1220;
      --ink-800:#1f2937;
      --ink-700:#374151;
      --ink-600:#475569;
      --ink-500:#64748b;
      --card:#ffffff;
      --error-bg:#fef2f2;
      --error-border:#fecaca;
      --error-text:#991b1b;
      --ring: rgba(255, 179, 0, .35);
      --shadow: 0 14px 40px -16px rgba(0,0,0,.22);
      --shadow-lg: 0 30px 70px -28px rgba(0,0,0,.30);
    }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", sans-serif;
      color:var(--ink-800);
      background:
        radial-gradient(1200px 600px at -10% -20%, rgba(255, 227, 140, .55), transparent 60%),
        radial-gradient(900px 500px at 120% 120%, rgba(255, 214, 102, .5), transparent 60%),
        linear-gradient(180deg, var(--brand-50), #fff 40%, var(--brand-50));
      overflow-x:hidden;
    }

    .orb, .ring{
      position:absolute; pointer-events:none;
      filter: blur(0.2px);
      animation: floaty 10s ease-in-out infinite;
      will-change: transform;
    }
    .orb{
      width:260px; height:260px; border-radius:999px;
      background: radial-gradient(circle at 30% 30%, var(--brand-200), transparent 60%);
      opacity:.8;
    }
    .ring{
      width:360px; height:360px; border-radius:999px;
      border: 7px solid rgba(255, 243, 214, .9);
      animation-duration: 12s;
      opacity:.9;
    }
    .orb.one{ left:-80px; top:-80px; }
    .ring.one{ right:-140px; bottom:-160px; }

    @keyframes floaty{
      0%,100%{ transform: translateY(0) }
      50%{ transform: translateY(-10px) }
    }

    .wrap{
      min-height:100dvh;
      display:grid;
      place-items:center;
      padding:16px;
      position:relative;
      isolation:isolate;
    }

    .card{
      width:min(100%, 900px);
      background:var(--card);
      border-radius:24px;
      box-shadow: var(--shadow);
      overflow:hidden;
      display:grid;
      grid-template-columns: 1fr;
      position:relative;
      animation: scaleIn .32s ease-out both;
    }
    @media (min-width: 880px){
      .card{ grid-template-columns: 1.05fr .95fr; }
    }
    @keyframes scaleIn{
      0%{ opacity:0; transform: translateY(10px) scale(.98) }
      100%{ opacity:1; transform: translateY(0) scale(1) }
    }

    .top-sheen{
      position:absolute; inset-inline:0; top:0; height:3px;
      background:
        linear-gradient(90deg,
          transparent 0%,
          rgba(255, 214, 102, 0.1) 15%,
          var(--brand-400) 50%,
          rgba(255, 214, 102, 0.1) 85%,
          transparent 100%);
      opacity:.9;
    }

    /* ========= LEFT ========= */
    .left{
      position:relative;
      background:
        linear-gradient(135deg, var(--brand-50), #fff 60%, var(--brand-100));
      padding:22px 18px 18px;
      border-right:1px solid var(--brand-100);
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      gap:18px;
    }
    @media (min-width: 880px){
      .left{ padding:26px 22px 20px; }
    }

    .brand{
      display:flex; align-items:center; gap:12px;
      flex-wrap:wrap;
    }

    /* LOGO + glow kuning */
    .brand-logo{
      width:56px; height:56px;
      border-radius:18px;
      position:relative;
      display:grid; place-items:center;
      overflow:visible;
      isolation:isolate;
      flex:0 0 56px;
    }
    .brand-logo::before{
      content:"";
      position:absolute;
      inset:-16px;
      border-radius:22px;
      background:
        radial-gradient(70% 70% at 30% 30%,
          rgba(255,255,255,1) 0%,
          rgba(255, 232, 170, 1) 40%,
          rgba(255, 202, 58, .9) 70%,
          rgba(255, 202, 58, 0) 100%);
      filter: blur(18px);
      z-index:0;
      pointer-events:none;
    }
    .brand-logo img{
      max-width:100%;
      max-height:100%;
      display:block;
      object-fit:contain;
      position:relative;
      z-index:1;
    }
    .brand-text{
      display:flex; flex-direction:column;
      line-height:1.1;
    }
    .brand-text .title-strong{
      font-size:18px; font-weight:900; color:var(--ink-900); letter-spacing:.2px;
    }
    .brand-text .subtitle{
      font-size:12px; color:var(--ink-600); font-weight:600;
    }
    @media (min-width:420px){
      .brand-logo{ width:60px; height:60px; flex-basis:60px; }
      .brand-text .title-strong{ font-size:19px; }
    }

    .left-hero{
      margin-top:18px;
      padding:14px 14px 12px;
      border-radius:18px;
      background:
        linear-gradient(90deg,
          rgba(255, 227, 140, .2),
          rgba(255, 214, 102, .8),
          rgba(255, 227, 140, .3));
      background-size:200% 100%;
      animation: shimmer 3s linear infinite;
      color:#5c4b00; font-weight:700;
      display:flex; align-items:flex-start; gap:10px;
      font-size:14px;
    }
    @keyframes shimmer{
      0%{ background-position:-200% 0 }
      100%{ background-position:200% 0 }
    }
    .left-hero svg{
      flex:0 0 auto;
      margin-top:2px;
    }

    .left-illus{
      margin-top:18px;
      padding:4px 2px 8px;
      display:flex;
      flex-direction:column;
      gap:14px;
    }

    .badge{
      display:inline-flex; align-items:center; gap:8px;
      padding:8px 12px; border-radius:999px;
      background:var(--brand-50);
      border:1px solid var(--brand-100);
      color:#8a6a00; font-weight:700; font-size:12px;
    }

    .checklist{
      display:flex; flex-direction:column; gap:8px;
      font-size:13px; color:var(--ink-700);
    }
    .check-item{
      display:flex; align-items:flex-start; gap:8px;
    }
    .check-icon{
      flex:0 0 auto;
      width:18px; height:18px; border-radius:999px;
      background:var(--brand-400);
      display:grid; place-items:center;
      color:#1a1300;
      box-shadow:0 0 0 3px rgba(255, 214, 102, .5);
      margin-top:1px;
    }
    .check-label strong{ font-weight:800; }

    .left-footer{
      margin-top:10px;
      display:flex;
      flex-wrap:wrap;
      gap:8px;
      font-size:11px;
      color:var(--ink-500);
      align-items:center;
      justify-content:space-between;
    }
    .tag-role-wrap{
      display:flex;
      flex-wrap:wrap;
      gap:6px;
    }
    .tag-role{
      padding:4px 8px;
      border-radius:999px;
      background:rgba(255, 214, 102, .18);
      border:1px dashed rgba(230, 161, 0, .6);
      font-weight:700;
      color:#7a5800;
    }

    /* ========= RIGHT ========= */
    .right{
      padding:20px 16px 18px;
      position:relative;
      z-index:1;
      background:#ffffff;
    }
    @media (min-width:560px){
      .right{ padding:26px 26px 22px; }
    }

    .heading-wrap{
      display:flex;
      flex-direction:column;
      gap:4px;
      margin-bottom:16px;
    }
    .title-page{
      margin:0;
      font-size:22px;
      color:var(--ink-900);
      letter-spacing:.1px;
    }
    .subtitle-page{
      margin:0;
      color:#6b7280;
      font-size:14px;
    }
    @media (min-width:480px){
      .title-page{ font-size:24px; }
    }

    .meta-chip{
      display:inline-flex; align-items:center; gap:6px;
      margin-top:6px;
      padding:4px 8px;
      border-radius:999px;
      background:var(--brand-50);
      border:1px solid var(--brand-100);
      font-size:11px; color:#7a5800;
      font-weight:700;
      align-self:flex-start;
    }
    .meta-dot{
      width:7px; height:7px; border-radius:999px;
      background:var(--brand-500);
    }

    label{
      display:block;
      font-weight:800;
      margin:14px 0 6px;
      font-size:13px;
      color:#1f2937;
    }
    .input{
      width:100%; padding:12px 12px;
      border:1px solid #e5e7eb; border-radius:12px; font-size:14px; outline:none;
      transition: box-shadow .15s ease, border-color .15s ease, transform .06s ease, background-color .15s ease;
      background:#fff;
    }
    .input::placeholder{ color:#9ca3af; }
    .input:focus{
      border-color: var(--brand-400);
      box-shadow: 0 0 0 4px var(--ring);
      background:#fffbef;
    }
    .input:hover{ transform: translateY(-1px) }

    .input-wrap{ position:relative; }

    .toggle-pass{
      position:absolute; right:10px; top:50%; transform:translateY(-50%);
      background:#fff;
      border:1px solid #e5e7eb; color:#6b7280;
      padding:6px 8px; border-radius:10px; cursor:pointer; font-size:12px; font-weight:800;
      display:inline-flex; align-items:center; gap:6px;
      transition: border-color .15s ease, box-shadow .15s ease, transform .06s ease, background-color .15s ease;
    }
    .toggle-pass:hover{
      border-color: var(--brand-300);
      background:#fffaf0;
      box-shadow: 0 0 0 4px var(--ring);
      transform: translateY(-50%) translateY(-1px);
    }
    .toggle-pass svg{ width:16px; height:16px; }

    .row{
      display:flex; align-items:center; justify-content:space-between;
      margin:12px 0 6px; gap:8px;
    }
    .row a{
      font-weight:700; color:#b07400; text-decoration:none; font-size:13px;
    }
    .row a:hover{ text-decoration:underline; }

    .checkbox{
      appearance:none; width:18px; height:18px; border-radius:6px; border:1.5px solid #d1d5db; display:grid; place-items:center;
      transition: all .16s ease; cursor:pointer; position:relative; background:#fff;
    }
    .checkbox:checked{
      background: var(--brand-500); border-color: var(--brand-500);
      box-shadow: 0 0 0 4px var(--ring);
    }
    .checkbox:before{
      content:""; width:10px; height:10px; background:#fff; border-radius:3px; transform:scale(0); transition: transform .12s ease;
    }
    .checkbox:checked:before{ transform:scale(1) }
    .inline{
      display:flex; align-items:center; gap:10px;
      font-weight:700; color:#374151; cursor:pointer; user-select:none; font-size:13px;
    }

    .btn{
      width:100%; margin-top:16px; padding:12px 14px;
      border:0; border-radius:12px;
      background:
        radial-gradient(circle at 10% 0, #fff6d7 0, transparent 55%),
        linear-gradient(180deg, var(--brand-500), var(--brand-400));
      color:#1a1300; font-weight:900; letter-spacing:.2px; cursor:pointer; font-size:15px;
      box-shadow: 0 14px 30px -14px rgba(255,179,0,.75);
      transition: transform .08s ease, filter .12s ease, box-shadow .12s ease;
    }
    .btn:hover{
      filter:brightness(1.03);
      box-shadow: var(--shadow-lg);
      transform: translateY(-1px);
    }
    .btn:active{ transform: translateY(0); }

    .muted{
      text-align:center; margin-top:14px;
      color:var(--ink-500); font-size:13px;
    }
    .muted a{ color:#b07400; font-weight:800; text-decoration:none; }
    .muted a:hover{ text-decoration:underline; }

    .error{
      margin:10px 0 4px; background:var(--error-bg); color:var(--error-text);
      border:1px solid var(--error-border); padding:10px 12px; border-radius:12px; font-size:13px;
      display:flex; gap:8px; align-items:flex-start;
      animation: fadeUp .25s ease-out both;
    }
    .error-icon{
      flex:0 0 auto;
      margin-top:2px;
    }
    @keyframes fadeUp{
      from{ opacity:0; transform: translateY(8px) }
      to{ opacity:1; transform: translateY(0) }
    }

    .sr-only{
      position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0;
    }

    .grid2{
      display:grid; grid-template-columns:1fr; gap:14px;
    }
    @media (min-width:560px){
      .grid2{ grid-template-columns:1fr; }
    }

    /* UTIL gambar */
    img{ max-width:100%; height:auto; }

    /* Preferensi motion */
    @media (prefers-reduced-motion: reduce){
      *{ animation: none !important; transition: none !important; }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="orb one" aria-hidden="true"></div>
    <div class="ring one" aria-hidden="true"></div>

    <main class="card" role="main" aria-labelledby="title">
      <div class="top-sheen" aria-hidden="true"></div>

      {{-- ================= LEFT (Branding) ================= --}}
      <section class="left" aria-label="Branding E-Tamu DPRD">
        <div>
          <div class="brand">
            <a href="#" class="brand-logo" onclick="scrollTo({top:0,behavior:'smooth'})" aria-label="Kembali ke atas">
              <img
                src="/img/logoDprd.png"
                alt="Logo DPRD Kota Gorontalo"
                width="120" height="120"
                decoding="async"
                loading="eager"
              />
            </a>
            <div class="brand-text">
              <span class="title-strong">E-Tamu DPRD</span>
              <span class="subtitle">Kota Gorontalo</span>
            </div>
          </div>

          <div class="left-hero" role="status" aria-live="polite">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M12 2l1.8 4.9L19 9l-5.2 2.1L12 16l-1.8-4.9L5 9l5.2-2.1L12 2z"/>
            </svg>
            <div>
              Selamat datang kembali —
              <span style="font-weight:800;">silakan masuk untuk melanjutkan layanan tamu.</span>
            </div>
          </div>

          <div class="left-illus" aria-hidden="true">
            <span class="badge">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm7 9a1 1 0 01-1 1H6a1 1 0 01-1-1 7 7 0 0114 0z"/></svg>
              Akses sesuai peran
            </span>

            <div class="checklist">
              <div class="check-item">
                <div class="check-icon">
                  <svg viewBox="0 0 20 20" width="11" height="11" fill="currentColor">
                    <path d="M8.143 13.314L4.4 9.571l1.414-1.414 2.329 2.329 5.143-5.143 1.414 1.414-6.557 6.557z"/>
                  </svg>
                </div>
                <div class="check-label">
                  <strong>Keamanan terjaga</strong><br>
                  Hanya petugas dengan akun yang dapat mengelola data kunjungan.
                </div>
              </div>
              <div class="check-item">
                <div class="check-icon">
                  <svg viewBox="0 0 20 20" width="11" height="11" fill="currentColor">
                    <path d="M8.143 13.314L4.4 9.571l1.414-1.414 2.329 2.329 5.143-5.143 1.414 1.414-6.557 6.557z"/>
                  </svg>
                </div>
                <div class="check-label">
                  <strong>Pelayanan cepat</strong><br>
                  Pantau dan proses pengajuan tamu dalam satu panel.
                </div>
              </div>
              <div class="check-item">
                <div class="check-icon">
                  <svg viewBox="0 0 20 20" width="11" height="11" fill="currentColor">
                    <path d="M8.143 13.314L4.4 9.571l1.414-1.414 2.329 2.329 5.143-5.143 1.414 1.414-6.557 6.557z"/>
                  </svg>
                </div>
                <div class="check-label">
                  <strong>Jejak digital rapi</strong><br>
                  Riwayat kunjungan tersimpan otomatis untuk kebutuhan laporan.
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="left-footer">
          <div class="tag-role-wrap">
            <span class="tag-role">Admin</span>
            <span class="tag-role">Resepsionis</span>
            <span class="tag-role">Host</span>
          </div>
          <span>© {{ date('Y') }} DPRD Kota Gorontalo</span>
        </div>
      </section>

      {{-- ================= RIGHT (Form Login) ================= --}}
      <section class="right">
        <div class="heading-wrap">
          <h1 id="title" class="title-page">Masuk ke Akun Anda</h1>
          <p class="subtitle-page">Gunakan kredensial yang telah diberikan untuk mengakses dashboard.</p>
          <div class="meta-chip">
            <span class="meta-dot"></span>
            Akses internal E-Tamu DPRD
          </div>
        </div>

        {{-- Error global dari validasi/throttling (Laravel melempar di field "email") --}}
        @error('email')
          <div class="error">
            <span class="error-icon" aria-hidden="true">
              <svg viewBox="0 0 20 20" width="16" height="16" fill="currentColor">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 6h2v5H9V6zm0 7h2v2H9v-2z"/>
              </svg>
            </span>
            <span>{{ $message }}</span>
          </div>
        @enderror

        {{-- Error field password (jika ada) --}}
        @error('password')
          <div class="error">
            <span class="error-icon" aria-hidden="true">
              <svg viewBox="0 0 20 20" width="16" height="16" fill="currentColor">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 6h2v5H9V6zm0 7h2v2H9v-2z"/>
              </svg>
            </span>
            <span>{{ $message }}</span>
          </div>
        @enderror

        <form method="POST" action="{{ route('login.attempt') }}" novalidate autocomplete="on">
          @csrf

          <div class="grid2">
            <div style="grid-column:1 / -1;">
              <label for="email">Email</label>
              <input
                id="email"
                class="input"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
                placeholder="nama@contoh.com"
                aria-describedby="emailHelp"
                inputmode="email"
              >
              <span id="emailHelp" class="sr-only">Masukkan email yang terdaftar</span>
            </div>

            <div style="grid-column:1 / -1;">
              <label for="password">Kata sandi</label>

              <div class="input-wrap">
                <input
                  id="password"
                  class="input"
                  type="password"
                  name="password"
                  required
                  autocomplete="current-password"
                  placeholder="••••••••"
                  aria-describedby="passwordHelp"
                >
                <button
                  type="button"
                  class="toggle-pass"
                  id="togglePassword"
                  aria-label="Tampilkan sandi"
                  aria-pressed="false"
                >
                  <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12a5 5 0 110-10 5 5 0 010 10z"/>
                  </svg>
                  <span id="toggleText">Tampilkan</span>
                </button>
              </div>
              <span id="passwordHelp" class="sr-only">Anda dapat menampilkan atau menyembunyikan sandi</span>
            </div>
          </div>

          {{-- Jika suatu saat ingin aktifkan kembali remember & lupa sandi, tinggal uncomment --}}
          {{-- 
          <div class="row" style="margin-top:10px">
            <label class="inline" for="remember">
              <input id="remember" class="checkbox" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
              Ingat saya
            </label>

            <a href="{{ route('password.request') }}">Lupa sandi?</a>
          </div>
          --}}

          <button class="btn" type="submit" aria-label="Login">
            Masuk
          </button>
        </form>

        <p class="muted">
          Kembali ke <a href="{{ route('welcome') }}">halaman utama</a>
        </p>
      </section>
    </main>
  </div>

  <!-- Interaksi halus + toggle password -->
  <script>
    (function(){
      const pwd = document.getElementById('password');
      const toggle = document.getElementById('togglePassword');
      const tText = document.getElementById('toggleText');

      if (toggle && pwd) {
        toggle.addEventListener('click', function(){
          const show = pwd.type === 'password';
          pwd.type = show ? 'text' : 'password';
          this.setAttribute('aria-pressed', show ? 'true' : 'false');
          this.setAttribute('aria-label', show ? 'Sembunyikan sandi' : 'Tampilkan sandi');
          if (tText) tText.textContent = show ? 'Sembunyikan' : 'Tampilkan';
          pwd.focus({ preventScroll:true });
          try { pwd.setSelectionRange(0, pwd.value.length); } catch(e){}
        });
      }

      if (pwd) {
        pwd.addEventListener('keydown', e=>{
          if(e.key === 'Enter'){
            const form = pwd.closest('form');
            if(form) form.requestSubmit();
          }
        });
      }
    })();
  </script>
</body>
</html>
