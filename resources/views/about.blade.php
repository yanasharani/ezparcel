@extends('layouts.app')
@section('title', 'About Us')

@section('styles')
<style>
@keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes slideIn{from{opacity:0;transform:translateX(-20px)}to{opacity:1;transform:translateX(0)}}
@keyframes countUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

.reveal{opacity:0;transform:translateY(30px);transition:opacity 0.7s ease,transform 0.7s ease;}
.reveal.visible{opacity:1;transform:translateY(0);}
.reveal-delay-1{transition-delay:0.1s}
.reveal-delay-2{transition-delay:0.2s}
.reveal-delay-3{transition-delay:0.3s}
.reveal-delay-4{transition-delay:0.4s}

.wg{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--bo)}
.wc{background:var(--w);padding:32px 26px;transition:all 0.3s;cursor:default;position:relative;overflow:hidden;}
.wc::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;background:var(--r);transform:scaleX(0);transition:transform 0.3s ease;}
.wc:hover{background:var(--n);transform:translateY(-4px);box-shadow:0 8px 24px rgba(194,112,128,0.12);}
.wc:hover::after{transform:scaleX(1);}
.wn{font-family:'Playfair Display',serif;font-size:40px;font-weight:600;color:var(--r);opacity:0.18;line-height:1;margin-bottom:14px;transition:opacity 0.25s}
.wc:hover .wn{opacity:0.5}
.wc h4{font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--dk);margin-bottom:8px}
.wc p{font-size:12px;color:var(--mu);line-height:1.7}
.wc-ico{width:36px;height:36px;background:rgba(194,112,128,0.1);display:flex;align-items:center;justify-content:center;margin-bottom:16px;transition:all 0.3s}
.wc:hover .wc-ico{background:var(--r);transform:rotate(5deg);}
.wc:hover .wc-ico svg{stroke:#fff;}

.sg{display:grid;grid-template-columns:repeat(4,1fr)}
.step{
  padding:40px 28px;
  border-right:0.5px solid rgba(251,248,245,0.08);
  transition:all 0.3s;
  position:relative;
  overflow:hidden;
  cursor:default;
}
.step:last-child{border-right:none}
.step::before{
  content:'';
  position:absolute;
  top:0;left:0;right:0;
  height:2px;
  background:linear-gradient(90deg,var(--r),var(--r3));
  transform:scaleX(0);
  transition:transform 0.4s ease;
  transform-origin:left;
}
.step:hover{background:rgba(194,112,128,0.08);}
.step:hover::before{transform:scaleX(1);}
.step-n{
  font-family:'Playfair Display',serif;
  font-size:56px;
  font-weight:600;
  color:var(--r3);
  opacity:0.3;
  line-height:1;
  margin-bottom:18px;
  transition:all 0.3s;
}
.step:hover .step-n{opacity:0.8;transform:scale(1.05);}
.step h4{font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#FBF8F5;margin-bottom:8px;transition:color 0.3s;}
.step:hover h4{color:var(--r3);}
.step p{font-size:12px;color:rgba(251,248,245,0.5);line-height:1.75;transition:color 0.3s;}
.step:hover p{color:rgba(251,248,245,0.75);}

.step-connector{
  display:flex;
  align-items:center;
  justify-content:center;
  position:absolute;
  right:-1px;
  top:50%;
  transform:translateY(-50%);
  z-index:2;
}

@media(max-width:768px){
  .wg{grid-template-columns:1fr 1fr}
  .sg{grid-template-columns:1fr 1fr}
}
</style>
@endsection

@section('content')

{{-- HERO --}}
<div style="position:relative;background:var(--dk);padding:80px 52px;overflow:hidden">
  <div style="position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1600518464441-9154a4dea21b?w=1400&q=60') center/cover;filter:brightness(0.06) saturate(0.2)"></div>
  <div style="position:absolute;inset:0;background:rgba(40,20,12,0.95)"></div>
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(194,112,128,0.04) 1px,transparent 1px);background-size:36px 36px"></div>
  <div style="position:relative;z-index:2;animation:fadeUp 0.8s 0.3s both">
    <div style="font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r3);display:flex;align-items:center;gap:8px;margin-bottom:12px">
      <span style="display:inline-block;width:20px;height:1px;background:var(--r3)"></span>About EZParcel
    </div>
    <h1 style="font-family:'Playfair Display',serif;font-size:clamp(36px,5vw,52px);font-weight:600;color:#FBF8F5;line-height:1.08;margin-bottom:12px;letter-spacing:-1px">
      Built for UPSI.<br><em style="color:var(--r3)">Built with purpose.</em>
    </h1>
    <p style="font-size:13px;color:rgba(251,248,245,0.38);max-width:520px;line-height:1.8;font-weight:300">
      A final year project designed to eliminate queues and make parcel collection effortless for every UPSI student at Kiosk Batu KZ.
    </p>
  </div>
</div>

{{-- MISSION --}}
<div style="padding:72px 52px;background:var(--w)">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;">
    <div class="reveal">
      <div style="font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r);display:flex;align-items:center;gap:8px;margin-bottom:12px">
        <span style="display:inline-block;width:20px;height:1px;background:var(--r)"></span>Our Mission
      </div>
      <h2 style="font-family:'Playfair Display',serif;font-size:36px;font-weight:600;color:var(--dk);line-height:1.12;margin-bottom:18px;letter-spacing:-0.5px">
        Making campus life<br>a little <em style="color:var(--r)">easier.</em>
      </h2>
      <p style="font-size:13px;color:var(--mu);line-height:1.85;margin-bottom:14px">
        EZParcel was born from a real frustration — students spending too long queuing at Kiosk Batu KZ. Late fines, missed windows, wasted trips.
      </p>
      <p style="font-size:13px;color:var(--mu);line-height:1.85">
        Our platform lets every student search, book, and pay from their phone before they even leave their room.
      </p>
    </div>
    <div class="reveal reveal-delay-2" style="border-left:2px solid var(--r);padding:28px 32px;background:var(--n2);position:relative">
      <div style="position:absolute;top:12px;left:16px;font-size:40px;color:var(--r);opacity:0.15;font-family:Georgia,serif;line-height:1">❝</div>
      <p style="font-family:'Playfair Display',serif;font-size:19px;color:var(--dk);line-height:1.65;font-style:italic;margin-bottom:14px">
        "No more wasted trips. No more queues. Just show up at your time and collect your parcel."
      </p>
      <cite style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa)">-Deqmie-</cite>
    </div>
  </div>
</div>

{{-- WHY EZPARCEL --}}
<div style="padding:72px 52px 52px;background:var(--n)">
  <div class="reveal">
    <div style="font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r);display:flex;align-items:center;gap:8px;margin-bottom:12px">
      <span style="display:inline-block;width:20px;height:1px;background:var(--r)"></span>Why EZParcel
    </div>
    <h2 style="font-family:'Playfair Display',serif;font-size:36px;font-weight:600;color:var(--dk);line-height:1.12;margin-bottom:40px;letter-spacing:-0.5px">
      Four reasons students <em style="color:var(--r)">love it.</em>
    </h2>
  </div>
  <div class="wg">
    <div class="wc reveal reveal-delay-1">
      <div class="wc-ico">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C27080" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </div>
      <div class="wn">01</div>
      <h4>Instant Search</h4>
      <p>Track any parcel in seconds with your tracking number.</p>
    </div>
    <div class="wc reveal reveal-delay-2">
      <div class="wc-ico">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C27080" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
      </div>
      <div class="wn">02</div>
      <h4>Slot Booking</h4>
      <p>Pick a precise time from admin-managed slots. Zero confusion.</p>
    </div>
    <div class="wc reveal reveal-delay-3">
      <div class="wc-ico">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C27080" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
      </div>
      <div class="wn">03</div>
      <h4>Pay Online</h4>
      <p>COD or QR. Confirm before you leave your room.</p>
    </div>
    <div class="wc reveal reveal-delay-4">
      <div class="wc-ico">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C27080" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div class="wn">04</div>
      <h4>Zero Queues</h4>
      <p>Show up at your slot and collect immediately. No waiting.</p>
    </div>
  </div>
</div>

{{-- HOW IT WORKS --}}
<div style="background:var(--dk);padding:72px 52px">
  <div class="reveal">
    <div style="font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r3);display:flex;align-items:center;gap:8px;margin-bottom:12px">
      <span style="display:inline-block;width:20px;height:1px;background:var(--r3)"></span>How It Works
    </div>
    <h2 style="font-family:'Playfair Display',serif;font-size:36px;font-weight:600;color:#FBF8F5;line-height:1.12;margin-bottom:40px;letter-spacing:-0.5px">
      Four steps to <em style="color:var(--r3)">your parcel.</em>
    </h2>
  </div>

  <div class="sg">
    <div class="step reveal reveal-delay-1">
      <div class="step-n">01</div>
      <h4>Register</h4>
      <p>Sign up with your matric number. One account for all parcels.</p>
    </div>
    <div class="step reveal reveal-delay-2">
      <div class="step-n">02</div>
      <h4>Search</h4>
      <p>Enter tracking number. Details appear instantly.</p>
    </div>
    <div class="step reveal reveal-delay-3">
      <div class="step-n">03</div>
      <h4>Book & Pay</h4>
      <p>Select time slot. Pay via COD or QR scan.</p>
    </div>
    <div class="step reveal reveal-delay-4">
      <div class="step-n">04</div>
      <h4>Collect</h4>
      <p>Show up at your time. No queue. Parcel ready.</p>
    </div>
  </div>
</div>

{{-- STATS --}}
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
// Scroll reveal animation
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@endpush