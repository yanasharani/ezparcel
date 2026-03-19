<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — EZParcel</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{--r:#C27080;--r2:#A85A68;--r3:#D4909A;--n:#F7EEE8;--n2:#EFE4DC;--w:#FFFFFF;--warm:#FBF8F5;--dk:#28140C;--dk2:#3C2018;--mu:#8C5848;--fa:#B89088;--bo:#EAD4CC;}
body{font-family:-apple-system,BlinkMacSystemFont,'SF Pro Display','Helvetica Neue',sans-serif;background:var(--dk);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;-webkit-font-smoothing:antialiased;}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
.bg{position:fixed;inset:0;background:url('https://images.unsplash.com/photo-1600518464441-9154a4dea21b?w=1400&q=60') center/cover;filter:brightness(0.06) saturate(0.2);}
.ov{position:fixed;inset:0;background:rgba(40,20,12,0.95);}
.noise{position:fixed;inset:0;background-image:radial-gradient(rgba(194,112,128,0.05) 1px,transparent 1px);background-size:28px 28px;}
.card{position:relative;z-index:2;background:var(--w);padding:48px 46px;width:100%;max-width:500px;box-shadow:0 32px 80px rgba(40,20,12,0.45),0 8px 24px rgba(40,20,12,0.2);animation:fadeUp 0.7s 0.3s both;}
.logo{font-family:'Playfair Display',serif;font-size:20px;font-weight:600;color:var(--dk);margin-bottom:30px;display:block;text-decoration:none;}
.logo span{color:var(--r);font-style:italic;}
h2{font-family:'Playfair Display',serif;font-size:36px;font-weight:600;color:var(--dk);line-height:1.08;margin-bottom:6px;letter-spacing:-0.5px;}
.sub{font-size:12px;color:var(--fa);margin-bottom:30px;font-weight:300;}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:0 16px;}
.fg{margin-bottom:16px;}
.fg label{font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);display:block;margin-bottom:6px;}
.fg input{width:100%;padding:12px 14px;border:0.5px solid var(--bo);font-size:13px;font-family:-apple-system,sans-serif;background:var(--n);outline:none;color:var(--dk);transition:all 0.2s;}
.fg input:focus{border-color:var(--r);background:var(--w);box-shadow:0 0 0 3px rgba(194,112,128,0.1);}
.btn{display:block;width:100%;background:var(--r);color:#fff;padding:14px;font-size:13px;font-weight:700;border:none;cursor:pointer;border-radius:30px;margin-top:18px;margin-bottom:16px;font-family:-apple-system,sans-serif;transition:all 0.25s;letter-spacing:0.3px;}
.btn:hover{background:var(--r2);transform:translateY(-2px);box-shadow:0 8px 24px rgba(194,112,128,0.4);}
.lnk{font-size:12px;color:var(--mu);text-align:center;}
.lnk a{color:var(--r);font-weight:700;text-decoration:none;}
.err{font-size:11px;color:var(--r2);margin-top:4px;}
.alert-error{background:rgba(194,112,128,0.08);border:0.5px solid rgba(194,112,128,0.2);color:var(--r2);padding:12px 14px;font-size:12px;margin-bottom:20px;line-height:1.5;}
.pw-bars{display:flex;gap:4px;margin-top:8px;}
.pw-b{height:3px;flex:1;border-radius:2px;background:var(--bo);transition:background 0.3s;}
.req{display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;}
.req-item{font-size:10px;padding:3px 8px;border-radius:100px;background:var(--n2);color:var(--fa);transition:all 0.25s;}
.req-item.pass{background:rgba(21,128,61,0.1);color:#166534;}
</style>
</head>
<body>
<div class="bg"></div>
<div class="ov"></div>
<div class="noise"></div>

<div class="card">
  <a href="{{ route('landing') }}" class="logo">EZ<span>Parcel</span></a>
  <h2>Create<br>account.</h2>
  <p class="sub">Register with your UPSI matric number</p>

  @if($errors->any())
  <div class="alert-error">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
  </div>
  @endif

  <form method="POST" action="{{ route('register') }}" id="reg-form" novalidate>
    @csrf
    <div class="g2">
      <div class="fg">
        <label>Full Name</label>
        <input type="text" name="name" id="r-name" value="{{ old('name') }}" placeholder="Nur Lyana" required oninput="checkForm()">
        @error('name')<div class="err">{{ $message }}</div>@enderror
      </div>
      <div class="fg">
        <label>Matric Number</label>
        <input type="text" name="matric_number" id="r-matric" value="{{ old('matric_number') }}" placeholder="D012345678" required oninput="checkForm()">
        @error('matric_number')<div class="err">{{ $message }}</div>@enderror
      </div>
      <div class="fg">
        <label>Email</label>
        <input type="email" name="email" id="r-email" value="{{ old('email') }}" placeholder="lyana@siswa.upsi.edu.my" required oninput="checkForm()">
        @error('email')<div class="err">{{ $message }}</div>@enderror
      </div>
      <div class="fg">
        <label>Phone</label>
        <input type="text" name="phone" id="r-phone" value="{{ old('phone') }}" placeholder="01X-XXXXXXX" required
          oninput="this.value=this.value.replace(/[^0-9]/g,'');checkForm()">
        @error('phone')<div class="err">{{ $message }}</div>@enderror
      </div>
    </div>
    <div class="fg">
      <label>Password</label>
      <input type="password" name="password" id="r-pw" placeholder="Min 8 — uppercase, number & symbol" required oninput="checkPw(this.value);checkForm()">
      <div class="pw-bars">
        <div class="pw-b" id="r-bar1"></div>
        <div class="pw-b" id="r-bar2"></div>
        <div class="pw-b" id="r-bar3"></div>
        <div class="pw-b" id="r-bar4"></div>
      </div>
      <div class="req">
        <span class="req-item" id="r-len">8+ chars</span>
        <span class="req-item" id="r-upper">Uppercase</span>
        <span class="req-item" id="r-lower">Lowercase</span>
        <span class="req-item" id="r-num">Number</span>
        <span class="req-item" id="r-sym">Symbol</span>
      </div>
    </div>
    <div class="fg">
      <label>Confirm Password</label>
      <input type="password" name="password_confirmation" id="r-pwc" placeholder="••••••••" required oninput="checkForm()">
      <div id="r-match" style="font-size:10px;margin-top:5px;color:var(--fa);"></div>
    </div>
    <button type="submit" class="btn" id="r-submit" disabled>Create Account</button>
  </form>
  <p class="lnk">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
</div>

<script>
let pwStrong = false;
let pwMatch  = false;

function checkPw(val) {
  const rules = {
    'r-len':   val.length >= 8,
    'r-upper': /[A-Z]/.test(val),
    'r-lower': /[a-z]/.test(val),
    'r-num':   /[0-9]/.test(val),
    'r-sym':   /[^A-Za-z0-9]/.test(val),
  };
  Object.entries(rules).forEach(([id, ok]) => {
    document.getElementById(id).classList.toggle('pass', ok);
  });
  const score = Object.values(rules).filter(Boolean).length;
  const colors = ['var(--bo)','var(--r2)','var(--r)','var(--r3)','#5A9A3A'];
  [1,2,3,4].forEach(i => {
    document.getElementById('r-bar'+i).style.background = i <= score ? colors[score] : 'var(--bo)';
  });
  pwStrong = score === 5;
}

function checkForm() {
  const pw  = document.getElementById('r-pw').value;
  const pwc = document.getElementById('r-pwc').value;
  const matchEl = document.getElementById('r-match');

  if (pwc) {
    pwMatch = pw === pwc;
    matchEl.textContent = pwMatch ? '✓ Passwords match' : '✗ Passwords do not match';
    matchEl.style.color = pwMatch ? '#166534' : 'var(--r2)';
  }

  const allFilled = ['r-name','r-matric','r-email','r-phone','r-pw','r-pwc'].every(id => {
    return document.getElementById(id)?.value.trim() !== '';
  });

  const btn = document.getElementById('r-submit');
  const ok  = allFilled && pwStrong && pwMatch;
  btn.disabled = !ok;
  btn.style.opacity = ok ? '1' : '0.5';
}
</script>
</body>
</html>