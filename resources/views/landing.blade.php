@extends('layouts.app')
@section('title', 'EZParcel — UPSI')

@section('styles')
<style>
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes countUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.6}}
@keyframes kenburns{0%{transform:scale(1.05)}50%{transform:scale(1.1) translate(-1%,-1%)}100%{transform:scale(1.05)}}
.slide{
  position:absolute;inset:0;
  background-size:cover;
  background-position:center;
  opacity:0;
  transition:opacity 1.5s ease-in-out;
  animation:kenburns 14s ease-in-out infinite;
}
.slide.active{opacity:1;}
.dot{width:7px;height:7px;border-radius:50%;background:rgba(255,255,255,0.4);cursor:pointer;transition:all 0.3s;border:none;padding:0;}
.dot.active{background:#fff;transform:scale(1.3);}
</style>
@endsection

@section('content')

<div style="position:relative;min-height:calc(100vh - 64px);display:flex;align-items:center;overflow:hidden;background:var(--dk)">

  {{-- SLIDESHOW — no filter, gambar penuh --}}
  <div class="slide active" style="background-image:url('{{ asset('images/background(1).jpg') }}');"></div>
  <div class="slide" style="background-image:url('{{ asset('images/background(2).jpg') }}');"></div>
  <div class="slide" style="background-image:url('{{ asset('images/background(3).png') }}');"></div>
  <div class="slide" style="background-image:url('{{ asset('images/background(4).png') }}');"></div>

  {{-- OVERLAY NIPIS — supaya teks boleh baca --}}
  <div style="position:absolute;inset:0;background:linear-gradient(115deg,rgba(40,20,12,0.55) 40%,rgba(40,20,12,0.15));z-index:1;"></div>

  {{-- CONTENT --}}
  <div style="position:relative;z-index:2;padding:80px 52px 64px;max-width:720px;animation:fadeUp 0.8s 0.3s both">

    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(194,112,128,0.15);border:0.5px solid rgba(194,112,128,0.4);padding:6px 16px;margin-bottom:28px;">
      <div style="width:6px;height:6px;border-radius:50%;background:var(--r3);animation:pulse 2s infinite"></div>
      <span style="font-size:9px;font-weight:700;letter-spacing:2.5px;color:var(--r3);text-transform:uppercase">UPSI Tanjung Malim &nbsp;·&nbsp; Kiosk Batu KZ</span>
    </div>

    <h1 style="font-family:'Playfair Display',serif;font-size:clamp(44px,6vw,64px);font-weight:600;color:#FBF8F5;line-height:1.06;letter-spacing:-1.5px;margin-bottom:20px;animation:fadeUp 0.8s 0.5s both;text-shadow:0 2px 20px rgba(0,0,0,0.4);">
      Your UPSI Parcels,<br><em style="color:var(--r3)">Effortlessly</em><br>Yours.
    </h1>

    <p style="font-size:15px;color:rgba(251,248,245,0.85);line-height:1.85;max-width:460px;margin-bottom:38px;font-weight:300;animation:fadeUp 0.8s 0.7s both;text-shadow:0 1px 8px rgba(0,0,0,0.5);">
      Search, book, and pay — fast &amp; simple, built exclusively for UPSI students at Kiosk Batu KZ.
    </p>

    @auth
      <a href="{{ route('home') }}" style="display:inline-block;background:var(--r);color:#fff;padding:15px 40px;font-size:13px;font-weight:600;cursor:pointer;letter-spacing:0.3px;transition:all 0.25s;text-decoration:none;animation:fadeUp 0.8s 0.9s both">
        Search My Parcel
      </a>
    @else
      <a href="{{ route('login') }}" style="display:inline-block;background:var(--r);color:#fff;padding:15px 40px;font-size:13px;font-weight:600;cursor:pointer;letter-spacing:0.3px;transition:all 0.25s;text-decoration:none;animation:fadeUp 0.8s 0.9s both">
        Get Started
      </a>
    @endauth

    {{-- DOTS --}}
    <div style="display:flex;gap:8px;margin-top:48px;" id="slide-dots">
      <button class="dot active" onclick="goSlide(0)"></button>
      <button class="dot" onclick="goSlide(1)"></button>
      <button class="dot" onclick="goSlide(2)"></button>
      <button class="dot" onclick="goSlide(3)"></button>
    </div>

  </div>

  {{-- ARROWS --}}
  <button onclick="goSlide((current+3)%4)"
    style="position:absolute;left:20px;top:50%;transform:translateY(-50%);z-index:3;background:rgba(255,255,255,0.15);border:0.5px solid rgba(255,255,255,0.3);color:#fff;width:44px;height:44px;cursor:pointer;font-size:20px;transition:all 0.25s;backdrop-filter:blur(4px);"
    onmouseover="this.style.background='rgba(194,112,128,0.4)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">‹</button>
  <button onclick="goSlide((current+1)%4)"
    style="position:absolute;right:20px;top:50%;transform:translateY(-50%);z-index:3;background:rgba(255,255,255,0.15);border:0.5px solid rgba(255,255,255,0.3);color:#fff;width:44px;height:44px;cursor:pointer;font-size:20px;transition:all 0.25s;backdrop-filter:blur(4px);"
    onmouseover="this.style.background='rgba(194,112,128,0.4)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">›</button>

</div>

{{-- STATS BAR --}}
<div style="background:var(--r);display:flex;overflow:hidden">
  <div style="flex:1;padding:32px 24px;text-align:center;border-right:1px solid rgba(255,255,255,0.15);cursor:default;animation:countUp 0.6s 1.1s both">
    <div style="font-family:'Playfair Display',serif;font-size:44px;font-weight:600;color:#fff;line-height:1;letter-spacing:-1px">{{ $totalParcels }}+</div>
    <div style="font-size:9px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:rgba(255,255,255,0.55);margin-top:8px">Parcels Collected</div>
  </div>
  <div style="flex:1;padding:32px 24px;text-align:center;border-right:1px solid rgba(255,255,255,0.15);cursor:default;animation:countUp 0.6s 1.3s both">
    <div style="font-family:'Playfair Display',serif;font-size:44px;font-weight:600;color:#fff;line-height:1;letter-spacing:-1px">{{ $totalStudents }}+</div>
    <div style="font-size:9px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:rgba(255,255,255,0.55);margin-top:8px">UPSI Students</div>
  </div>
  <div style="flex:1;padding:32px 24px;text-align:center;cursor:default;animation:countUp 0.6s 1.5s both">
    <div style="font-family:'Playfair Display',serif;font-size:44px;font-weight:600;color:#fff;line-height:1;letter-spacing:-1px">{{ number_format($avgRating,1) }}★</div>
    <div style="font-size:9px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:rgba(255,255,255,0.55);margin-top:8px">Student Rating</div>
  </div>
</div>

@endsection

@push('scripts')
<script>
let current = 0;
const slides = document.querySelectorAll('.slide');
const dots   = document.querySelectorAll('.dot');
let timer;

function goSlide(n) {
  slides[current].classList.remove('active');
  dots[current].classList.remove('active');
  current = (n + slides.length) % slides.length;
  slides[current].classList.add('active');
  dots[current].classList.add('active');
  resetTimer();
}

function nextSlide() {
  goSlide((current + 1) % slides.length);
}

function resetTimer() {
  clearInterval(timer);
  timer = setInterval(nextSlide, 5000);
}

timer = setInterval(nextSlide, 5000);
</script>
@endpush