<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'EZParcel') — EZParcel</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{
  --r:#C27080;--r2:#A85A68;--r3:#D4909A;--r4:#E8B8C0;
  --n:#F7EEE8;--n2:#EFE4DC;--n3:#E4D4C8;--n4:#D4BEB4;
  --w:#FFFFFF;--warm:#FBF8F5;
  --dk:#28140C;--dk2:#3C2018;--dk3:#5A3020;
  --mu:#8C5848;--fa:#B89088;--bo:#EAD4CC;--b2:#F4EAE4;
}
html{scroll-behavior:smooth}
body{font-family:-apple-system,BlinkMacSystemFont,'SF Pro Display','SF Pro Text','Helvetica Neue',sans-serif;background:var(--warm);color:var(--dk);-webkit-font-smoothing:antialiased;min-height:100vh}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.6}}
.nav{background:rgba(255,255,255,0.96);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:0.5px solid var(--bo);padding:0 52px;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;animation:fadeIn 0.4s ease}
.n-logo{font-family:'Playfair Display',serif;font-size:22px;font-weight:600;color:var(--dk);letter-spacing:-0.3px;text-decoration:none;display:flex;align-items:center;gap:8px;}
.n-logo img{height:26px;width:auto;object-fit:contain;}
.n-logo span{color:var(--r);font-style:italic}
.n-links{display:flex;gap:0}
.n-links a{font-size:12px;font-weight:500;color:var(--mu);text-decoration:none;padding:0 16px;height:64px;display:flex;align-items:center;border-bottom:2px solid transparent;transition:color 0.2s,border-color 0.2s;letter-spacing:0.1px}
.n-links a:hover{color:var(--dk)}
.n-links a.act{color:var(--dk);font-weight:600;border-bottom:2px solid var(--r)}
.n-right{display:flex;align-items:center;gap:10px;}
.n-av{width:34px;height:34px;background:var(--r);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;cursor:pointer;transition:transform 0.2s,box-shadow 0.2s;text-decoration:none;flex-shrink:0;}
.n-av:hover{transform:scale(1.05);box-shadow:0 4px 12px rgba(194,112,128,0.4)}
.n-signout-btn{background:none;border:0.5px solid var(--bo);color:var(--mu);padding:7px 16px;font-size:11px;font-weight:600;cursor:pointer;font-family:inherit;letter-spacing:0.3px;transition:all 0.2s;}
.n-signout-btn:hover{border-color:var(--r);color:var(--r);}
.hamburger{display:none;flex-direction:column;gap:4px;cursor:pointer;background:none;border:none;padding:4px}
.hamburger span{display:block;width:22px;height:1.5px;background:var(--dk);border-radius:2px}
.drawer{position:fixed;top:64px;left:0;right:0;bottom:0;background:var(--w);z-index:99;padding:24px;border-top:0.5px solid var(--bo);transform:translateX(100%);transition:transform .3s cubic-bezier(.4,0,.2,1);overflow-y:auto}
.drawer.open{transform:translateX(0)}
.drawer a{display:block;padding:14px 16px;font-size:13px;font-weight:500;color:var(--mu);text-decoration:none;border-bottom:0.5px solid var(--b2);}
.drawer a:hover,.drawer a.act{color:var(--dk);font-weight:600}
.drawer-sep{height:0.5px;background:var(--bo);margin:16px 0}
@media(max-width:768px){
  .nav{padding:0 24px}
  .n-links,.n-right{display:none}
  .hamburger{display:flex}
}
.footer{background:var(--dk);padding:32px 52px;border-top:1px solid rgba(255,255,255,0.05);display:flex;flex-direction:column;align-items:center;gap:6px;text-align:center;}
.f-logo{font-family:'Playfair Display',serif;font-size:18px;color:#FBF8F5;font-weight:600;text-decoration:none;}
.f-logo span{color:var(--r3);font-style:italic}
@media(max-width:768px){.footer{padding:24px}}
.container{max-width:1180px;margin:0 auto;padding:0 52px}
.badge{display:inline-flex;align-items:center;gap:5px;font-size:10px;font-weight:600;padding:4px 10px;border-radius:20px}
.b-r{background:rgba(194,112,128,0.1);color:var(--r2)}
.b-g{background:rgba(21,128,61,0.1);color:#166534}
.b-b{background:rgba(29,78,216,0.1);color:#1e40af}
.b-rd{background:rgba(185,28,28,0.1);color:#991b1b}
.b-gr{background:var(--n2);color:var(--mu)}
.b-p{background:rgba(109,40,217,0.1);color:#5b21b6}
.bdot{width:5px;height:5px;border-radius:50%;background:currentColor;animation:pulse 2s infinite}
.page-wrap{padding:48px 52px;background:var(--warm)}
.page-hd{margin-bottom:28px;display:flex;justify-content:space-between;align-items:flex-end;padding-bottom:20px;border-bottom:1px solid var(--bo)}
.page-hd h2{font-family:'Playfair Display',serif;font-size:28px;font-weight:600;color:var(--dk);letter-spacing:-0.3px}
.page-hd p{font-size:12px;color:var(--fa);margin-top:4px}
.lbl-sec{font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r);display:flex;align-items:center;gap:8px;margin-bottom:12px}
.lbl-sec::before{content:'';width:20px;height:1px;background:var(--r)}
.inp{width:100%;padding:12px 16px;border:0.5px solid var(--bo);font-size:13px;font-family:-apple-system,sans-serif;outline:none;color:var(--dk);background:var(--n);transition:all 0.2s}
.inp:focus{border-color:var(--r);background:var(--w);box-shadow:0 0 0 3px rgba(194,112,128,0.12)}
.inp::placeholder{color:var(--fa)}
select.inp{cursor:pointer}
textarea.inp{resize:none;line-height:1.6}
.lbl{font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);display:block;margin-bottom:8px}
.form-group{margin-bottom:16px}
.btn-dk{background:var(--dk);color:#FBF8F5;padding:12px 22px;font-size:12px;font-weight:600;border:none;cursor:pointer;transition:all 0.2s;white-space:nowrap;letter-spacing:0.3px;text-decoration:none;display:inline-block;}
.btn-dk:hover{background:var(--dk2);transform:translateY(-1px)}
.btn-r{background:var(--r);color:#fff;padding:10px 22px;font-size:12px;font-weight:700;border:none;cursor:pointer;transition:all 0.2s;text-decoration:none;display:inline-block;}
.btn-r:hover{background:var(--r2);transform:translateY(-1px)}
.btn-sm{background:var(--dk);color:#FBF8F5;padding:8px 18px;font-size:11px;font-weight:700;border:none;cursor:pointer;transition:all 0.2s}
.btn-sm:hover{background:var(--r);transform:translateY(-1px)}
.btn-outline{background:none;border:0.5px solid var(--bo);color:var(--mu);padding:10px 22px;font-size:12px;font-weight:500;cursor:pointer;transition:all 0.2s}
.btn-outline:hover{border-color:var(--r);color:var(--r)}
.btn-full{width:100%;display:block;text-align:center}
.divider{height:0.5px;background:var(--bo);margin:20px 0}
.card{background:var(--w);border:0.5px solid var(--bo);transition:box-shadow 0.25s}
.card:hover{box-shadow:0 4px 20px rgba(44,24,16,0.08)}
.tbl{background:var(--w);border:0.5px solid var(--bo);overflow:hidden}
.tbl-top{padding:16px 20px;background:var(--n2);border-bottom:0.5px solid var(--bo);display:flex;justify-content:space-between;align-items:center}
.tbl-top span{font-size:13px;font-weight:700;color:var(--dk)}
table.t{width:100%;border-collapse:collapse}
table.t th{font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);padding:11px 20px;text-align:left;border-bottom:0.5px solid var(--bo);background:var(--w)}
table.t td{font-size:13px;color:var(--dk);padding:13px 20px;border-bottom:0.5px solid var(--b2);transition:background 0.15s}
table.t tr:last-child td{border-bottom:none}
table.t tr:hover td{background:var(--n)}
.alert-success{background:rgba(21,128,61,0.08);border:0.5px solid rgba(21,128,61,0.2);color:#166534;padding:13px 17px;font-size:13px;margin-bottom:18px;}
.alert-error{background:rgba(194,112,128,0.08);border:0.5px solid rgba(194,112,128,0.2);color:var(--r2);padding:13px 17px;font-size:13px;margin-bottom:18px;}
.page-hero{position:relative;background:var(--dk);padding:48px 52px;overflow:hidden;}
.page-hero::before{content:'';position:absolute;inset:0;background:rgba(40,20,12,0.95);}
.page-hero::after{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(194,112,128,0.04) 1px,transparent 1px);background-size:30px 30px;}
.page-hero-inner{position:relative;z-index:2;}
.page-hero-tag{font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r3);display:flex;align-items:center;gap:8px;margin-bottom:10px;}
.page-hero-tag::before{content:'';display:inline-block;width:20px;height:1px;background:var(--r3);}
.page-hero h1{font-family:'Playfair Display',serif;font-size:clamp(28px,4vw,42px);font-weight:600;color:#FBF8F5;letter-spacing:-0.5px;line-height:1.1;}
.page-hero h1 em{color:var(--r3);font-style:italic;}
@media(max-width:768px){
  .container{padding:0 24px}
  .page-wrap{padding:32px 24px}
  .page-hero{padding:36px 24px}
}
@yield('styles')
</style>
@stack('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="nav">
  @php $logoFile = \DB::table('settings')->where('key','logo_filename')->value('value'); @endphp
  <a class="n-logo" href="{{ route('landing') }}">
    @if($logoFile && file_exists(public_path('images/'.$logoFile)))
      <img src="{{ asset('images/'.$logoFile) }}" alt="EZParcel">
    @else
      EZ<span>Parcel</span>
    @endif
  </a>

  @auth
  <div class="n-links">
    <a href="{{ route('landing') }}"       class="{{ request()->routeIs('landing')    ? 'act' : '' }}">Home</a>
    <a href="{{ route('about') }}"         class="{{ request()->routeIs('about')      ? 'act' : '' }}">About Us</a>
    <a href="{{ route('home') }}"          class="{{ request()->routeIs('home')       ? 'act' : '' }}">Search Parcel</a>
    <a href="{{ route('cart.index') }}"    class="{{ request()->routeIs('cart.*')     ? 'act' : '' }}">Cart</a>
    <a href="{{ route('booking.index') }}" class="{{ request()->routeIs('booking.*')  ? 'act' : '' }}">My Bookings</a>
    <a href="{{ route('contact') }}"       class="{{ request()->routeIs('contact')    ? 'act' : '' }}">Contact Us</a>
  </div>

  {{-- RIGHT: Avatar + Sign Out --}}
  <div class="n-right">
    <a class="n-av" href="{{ route('profile.show') }}" title="My Profile">
      {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </a>
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button type="submit" class="n-signout-btn">Sign Out</button>
    </form>
  </div>
  @endauth

  <button class="hamburger" onclick="document.getElementById('ez-drawer').classList.toggle('open')">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- MOBILE DRAWER -->
<div id="ez-drawer" class="drawer">
  @auth
    <a href="{{ route('landing') }}"       class="{{ request()->routeIs('landing')    ? 'act' : '' }}">Home</a>
    <a href="{{ route('about') }}"         class="{{ request()->routeIs('about')      ? 'act' : '' }}">About Us</a>
    <a href="{{ route('home') }}"          class="{{ request()->routeIs('home')       ? 'act' : '' }}">Search Parcel</a>
    <a href="{{ route('cart.index') }}"    class="{{ request()->routeIs('cart.*')     ? 'act' : '' }}">Cart</a>
    <a href="{{ route('booking.index') }}" class="{{ request()->routeIs('booking.*')  ? 'act' : '' }}">My Bookings</a>
    <a href="{{ route('contact') }}"       class="{{ request()->routeIs('contact')    ? 'act' : '' }}">Contact Us</a>
    <a href="{{ route('profile.show') }}"  class="{{ request()->routeIs('profile.*')  ? 'act' : '' }}">Profile</a>
    <div class="drawer-sep"></div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" style="width:100%;background:var(--dk);color:#FBF8F5;padding:14px;font-size:12px;font-weight:600;border:none;cursor:pointer;margin-top:8px;font-family:inherit;">
        Sign Out
      </button>
    </form>
  @else
    <div class="drawer-sep"></div>
    <a href="{{ route('login') }}" style="background:var(--r);color:#fff;text-align:center;font-weight:700;">Get Started</a>
  @endauth
</div>

@if(session('success'))
<div class="container"><div class="alert-success" style="margin-top:16px">{{ session('success') }}</div></div>
@endif
@if(session('error'))
<div class="container"><div class="alert-error" style="margin-top:16px">{{ session('error') }}</div></div>
@endif

@yield('content')

<!-- FOOTER -->
<footer class="footer">
  <a class="f-logo" href="{{ route('landing') }}">EZ<span>Parcel</span></a>
  <div style="font-size:11px;color:rgba(251,248,245,0.35);line-height:1.8;">
    Final Year Project · <strong style="color:rgba(251,248,245,0.5)">EZParcel</strong> by NurLyana
  </div>
  <div style="font-size:10px;color:rgba(251,248,245,0.2);">© {{ date('Y') }} EZParcel. All rights reserved.</div>
</footer>

<script>
document.addEventListener('click', function(e) {
  const drawer = document.getElementById('ez-drawer');
  if (drawer && drawer.classList.contains('open') && !drawer.contains(e.target) && !e.target.closest('.hamburger')) {
    drawer.classList.remove('open');
  }
});
</script>
@stack('scripts')
</body>
</html>