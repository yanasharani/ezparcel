@extends('layouts.app')
@section('title', 'My Profile')

@section('content')

<div class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-tag">EZParcel · UPSI</div>
    <h1>My <em>Profile</em></h1>
  </div>
</div>

<div class="page-wrap">

  @if(session('success'))
  <div class="alert-success" style="margin-bottom:20px;">✓ {{ session('success') }}</div>
  @endif
  @if(session('error'))
  <div class="alert-error" style="margin-bottom:20px;">{{ session('error') }}</div>
  @endif

  {{-- PROFILE BANNER --}}
  <div style="background:var(--w);border:0.5px solid var(--bo);padding:26px 28px;display:flex;align-items:center;gap:20px;margin-bottom:18px;transition:box-shadow 0.25s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(44,24,16,0.08)'" onmouseout="this.style.boxShadow='none'">
    <div style="width:58px;height:58px;background:rgba(194,112,128,0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:600;color:var(--r);font-family:'Playfair Display',serif;border:2px solid rgba(194,112,128,0.25);flex-shrink:0;">
      {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>
    <div>
      <div style="font-family:'Playfair Display',serif;font-size:20px;font-weight:600;color:var(--dk);margin-bottom:4px;">{{ auth()->user()->name }}</div>
      <div style="font-size:12px;color:var(--fa);">{{ auth()->user()->email }} · {{ auth()->user()->matric_number ?? '' }}</div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">

    {{-- PERSONAL INFO --}}
    <div style="background:var(--w);border:0.5px solid var(--bo);padding:24px;">
      <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--dk);margin-bottom:18px;padding-bottom:12px;border-bottom:0.5px solid var(--bo);">Personal Information</div>
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PUT')
        <input type="hidden" name="section" value="info">
        <div class="form-group">
          <label class="lbl">Full Name</label>
          <input class="inp" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
          @error('name')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="lbl">Email Address</label>
          <input class="inp" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
          @error('email')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="lbl">Phone Number</label>
          <input class="inp" type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
            oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
          @error('phone')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
        </div>
        <button type="submit" style="width:100%;background:var(--r);color:#fff;padding:12px;font-size:12px;font-weight:700;border:none;cursor:pointer;transition:all 0.25s;margin-top:6px;">
          Save Changes
        </button>
      </form>
    </div>

    {{-- CHANGE PASSWORD --}}
    <div style="background:var(--w);border:0.5px solid var(--bo);padding:24px;">
      <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--dk);margin-bottom:18px;padding-bottom:12px;border-bottom:0.5px solid var(--bo);">Change Password</div>
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PUT')
        <input type="hidden" name="section" value="password">
        <div class="form-group">
          <label class="lbl">Current Password</label>
          <input class="inp" type="password" name="current_password" placeholder="••••••••">
          @error('current_password')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="lbl">New Password</label>
          <input class="inp" type="password" name="password" id="new-pw"
            placeholder="Min 8 — uppercase, number & symbol"
            oninput="checkPw(this.value)">
          <div style="display:flex;gap:4px;margin-top:8px;">
            <div id="pw-b1" style="height:3px;flex:1;border-radius:2px;background:var(--bo);transition:background 0.3s"></div>
            <div id="pw-b2" style="height:3px;flex:1;border-radius:2px;background:var(--bo);transition:background 0.3s"></div>
            <div id="pw-b3" style="height:3px;flex:1;border-radius:2px;background:var(--bo);transition:background 0.3s"></div>
            <div id="pw-b4" style="height:3px;flex:1;border-radius:2px;background:var(--bo);transition:background 0.3s"></div>
          </div>
          <div id="pw-hint" style="font-size:10px;font-weight:600;color:var(--r);margin-top:5px;"></div>
          @error('password')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="lbl">Confirm Password</label>
          <input class="inp" type="password" name="password_confirmation" placeholder="••••••••">
        </div>
        <button type="submit" style="width:100%;background:var(--dk);color:#FBF8F5;padding:12px;font-size:12px;font-weight:700;border:none;cursor:pointer;transition:all 0.25s;margin-top:6px;">
          Update Password
        </button>
      </form>
    </div>

  </div>

</div>


@endsection

@push('scripts')
<script>
function checkPw(val) {
  const bars  = [1,2,3,4].map(i => document.getElementById('pw-b' + i));
  const hint  = document.getElementById('pw-hint');
  const rules = [
    val.length >= 8,
    /[A-Z]/.test(val),
    /[0-9]/.test(val),
    /[^A-Za-z0-9]/.test(val),
  ];
  const score  = rules.filter(Boolean).length;
  const colors = ['var(--bo)','var(--r2)','var(--r)','var(--r3)','#5A9A3A'];
  const hints  = ['','Weak','Fair','Good','Strong'];
  bars.forEach((b,i) => { b.style.background = i < score ? colors[score] : 'var(--bo)'; });
  hint.textContent = hints[score] || '';
  hint.style.color = colors[score];
}
</script>
@endpush