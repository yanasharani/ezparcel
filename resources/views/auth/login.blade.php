<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — EZParcel</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{--r:#C27080;--r2:#A85A68;--r3:#D4909A;--n:#F7EEE8;--n2:#EFE4DC;--w:#FFFFFF;--warm:#FBF8F5;--dk:#28140C;--dk2:#3C2018;--mu:#8C5848;--fa:#B89088;--bo:#EAD4CC;}
body{font-family:-apple-system,BlinkMacSystemFont,'SF Pro Display','Helvetica Neue',sans-serif;background:var(--dk);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;-webkit-font-smoothing:antialiased;}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.6}}
.bg{position:fixed;inset:0;background:url('https://images.unsplash.com/photo-1600518464441-9154a4dea21b?w=1400&q=60') center/cover;filter:brightness(0.06) saturate(0.2);}
.ov{position:fixed;inset:0;background:rgba(40,20,12,0.95);}
.noise{position:fixed;inset:0;background-image:radial-gradient(rgba(194,112,128,0.05) 1px,transparent 1px);background-size:28px 28px;}
.card{position:relative;z-index:2;background:var(--w);padding:48px 46px;width:100%;max-width:400px;box-shadow:0 32px 80px rgba(40,20,12,0.45),0 8px 24px rgba(40,20,12,0.2);animation:fadeUp 0.7s 0.3s both;}
.logo{font-family:'Playfair Display',serif;font-size:20px;font-weight:600;color:var(--dk);margin-bottom:30px;display:block;text-decoration:none;}
.logo span{color:var(--r);font-style:italic;}
h2{font-family:'Playfair Display',serif;font-size:36px;font-weight:600;color:var(--dk);line-height:1.08;margin-bottom:6px;letter-spacing:-0.5px;}
.sub{font-size:12px;color:var(--fa);margin-bottom:30px;font-weight:300;}
.fg{margin-bottom:16px;}
.fg label{font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);display:block;margin-bottom:6px;}
.fg input{width:100%;padding:12px 14px;border:0.5px solid var(--bo);font-size:13px;font-family:-apple-system,sans-serif;background:var(--n);outline:none;color:var(--dk);transition:all 0.2s;}
.fg input:focus{border-color:var(--r);background:var(--w);box-shadow:0 0 0 3px rgba(194,112,128,0.1);}
.fgt{font-size:11px;color:var(--fa);text-align:right;margin-bottom:24px;cursor:pointer;transition:color 0.2s;}
.fgt:hover{color:var(--r);}
.btn{display:block;width:100%;background:var(--r);color:#fff;padding:14px;font-size:13px;font-weight:700;border:none;cursor:pointer;border-radius:30px;margin-bottom:16px;font-family:-apple-system,sans-serif;transition:all 0.25s;letter-spacing:0.3px;}
.btn:hover{background:var(--r2);transform:translateY(-2px);box-shadow:0 8px 24px rgba(194,112,128,0.4);}
.lnk{font-size:12px;color:var(--mu);text-align:center;}
.lnk a{color:var(--r);font-weight:700;text-decoration:none;transition:color 0.2s;}
.lnk a:hover{color:var(--r2);}
.alert-error{background:rgba(194,112,128,0.08);border:0.5px solid rgba(194,112,128,0.2);color:var(--r2);padding:12px 14px;font-size:12px;margin-bottom:20px;line-height:1.5;}
</style>
</head>
<body>
<div class="bg"></div>
<div class="ov"></div>
<div class="noise"></div>

<div class="card">
  <a href="{{ route('landing') }}" class="logo">EZ<span>Parcel</span></a>
  <h2>Welcome<br>back,</h2>
  <p class="sub">Sign in to your EZParcel account</p>

  @if($errors->any())
  <div class="alert-error">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
  </div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="fg">
      <label>Matric Number</label>
      <input type="text" name="matric_number" value="{{ old('matric_number') }}" placeholder="e.g. D012345678" required autofocus>
    </div>
    <div class="fg">
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required>
    </div>
    @if(Route::has('password.request'))
    <div class="fgt"><a href="{{ route('password.request') }}" style="color:var(--fa);text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--r)'" onmouseout="this.style.color='var(--fa)'">Forgot password?</a></div>
    @endif
    <button type="submit" class="btn">Sign In</button>
  </form>
  <p class="lnk">New here? <a href="{{ route('register') }}">Create an account</a></p>
</div>
</body>
</html>