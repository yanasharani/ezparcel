@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')

@php
  $isOpen  = \DB::table('settings')->where('key','shop_is_open')->value('value');
  $notice  = \DB::table('settings')->where('key','shop_notice')->value('value');
  $shopOpen = $isOpen === null ? true : (bool)(int)$isOpen;
@endphp

{{-- DARK HERO --}}
<div style="position:relative;background:var(--dk);overflow:hidden;">
  <div style="position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1600518464441-9154a4dea21b?w=1400&q=60') center/cover;filter:brightness(0.06) saturate(0.2)"></div>
  <div style="position:absolute;inset:0;background:rgba(40,20,12,0.95)"></div>
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(194,112,128,0.04) 1px,transparent 1px);background-size:30px 30px"></div>
  <div style="position:relative;z-index:2;padding:56px 52px;">
    <div style="font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r3);display:flex;align-items:center;gap:8px;margin-bottom:12px">
      <span style="display:inline-block;width:20px;height:1px;background:var(--r3)"></span>Get In Touch
    </div>
    <h1 style="font-family:'Playfair Display',serif;font-size:42px;font-weight:600;color:#FBF8F5;letter-spacing:-0.8px;line-height:1.1;margin-bottom:8px;">
      Contact <em style="color:var(--r3)">Us.</em>
    </h1>

    {{-- INFO GRID --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:32px;">
      <div style="background:rgba(255,255,255,0.035);border:0.5px solid rgba(251,248,245,0.08);padding:20px 22px;transition:all 0.25s;" onmouseover="this.style.background='rgba(255,255,255,0.06)';this.style.borderColor='rgba(194,112,128,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.035)';this.style.borderColor='rgba(251,248,245,0.08)'">
        <h5 style="font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r3);margin-bottom:8px;">Address</h5>
        <p style="font-size:12px;color:rgba(251,248,245,0.5);line-height:1.7;font-weight:300;">Deqmie Printing, Kiosk Batu KZ,<br>UPSI, 35900 Tanjong Malim, Perak.</p>
      </div>
      <div style="background:rgba(255,255,255,0.035);border:0.5px solid rgba(251,248,245,0.08);padding:20px 22px;transition:all 0.25s;" onmouseover="this.style.background='rgba(255,255,255,0.06)';this.style.borderColor='rgba(194,112,128,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.035)';this.style.borderColor='rgba(251,248,245,0.08)'">
        <h5 style="font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r3);margin-bottom:8px;">Operating Hours</h5>
        <p style="font-size:12px;color:rgba(251,248,245,0.5);line-height:1.7;font-weight:300;">
          Mon – Fri: 9:00 AM – 6:00 PM<br>
          Saturday: 9:00 AM – 1:00 PM<br>
          @if($shopOpen)
            <span style="color:rgba(100,200,100,0.8);font-weight:600;">● Open Now</span>
          @else
            <span style="color:rgba(200,100,100,0.8);font-weight:600;">● Closed</span>
          @endif
          @if($notice) <br><span style="color:rgba(251,248,245,0.35);">{{ $notice }}</span> @endif
        </p>
      </div>
      <div style="background:rgba(255,255,255,0.035);border:0.5px solid rgba(251,248,245,0.08);padding:20px 22px;transition:all 0.25s;" onmouseover="this.style.background='rgba(255,255,255,0.06)';this.style.borderColor='rgba(194,112,128,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.035)';this.style.borderColor='rgba(251,248,245,0.08)'">
        <h5 style="font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r3);margin-bottom:8px;">WhatsApp Group</h5>
        <p style="font-size:12px;color:rgba(251,248,245,0.5);line-height:1.7;font-weight:300;">
          Join for parcel arrival alerts<br>
          <a href="https://chat.whatsapp.com/DVRnDFt5r5YGbkdrHELLvV" target="_blank" style="color:var(--r3);font-weight:700;text-decoration:none;">Click here to join →</a>
        </p>
      </div>
    </div>
  </div>
</div>

{{-- REVIEW FORM + REVIEWS --}}
<div style="background:var(--w);padding:40px 52px;">

  @auth
  <div style="margin-bottom:32px;">
    <h3 style="font-family:'Playfair Display',serif;font-size:24px;font-weight:600;color:var(--dk);margin-bottom:6px;">
      Leave a <em style="color:var(--r)">Review.</em>
    </h3>
    <p style="font-size:12px;color:var(--fa);margin-bottom:24px;font-weight:300;">Share your experience with EZParcel at Kiosk Batu KZ</p>

    <form method="POST" action="{{ route('reviews.store') }}">
      @csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 24px;margin-bottom:0;">
        <div class="form-group">
          <label class="lbl">Your Rating</label>
          <div style="display:flex;gap:6px;margin-top:6px;" id="star-row">
            @for($i=1;$i<=5;$i++)
              <button type="button" data-val="{{ $i }}" onclick="setStars({{ $i }})"
                style="background:none;border:none;font-size:28px;cursor:pointer;color:var(--bo);transition:color .15s,transform .15s;line-height:1;padding:0 2px;">★</button>
            @endfor
          </div>
          <input type="hidden" name="rating" id="rating-input" value="5">
        </div>
      </div>
      <div class="form-group">
        <label class="lbl">Your Review</label>
        <textarea class="inp" name="comment" rows="3"
          placeholder="Tell us about your experience..." required></textarea>
        @error('comment')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
      </div>
      <button type="submit" style="width:100%;background:var(--r);color:#fff;padding:14px;font-size:13px;font-weight:700;border:none;cursor:pointer;transition:all 0.25s;letter-spacing:0.3px;">
        Submit Review
      </button>
    </form>
  </div>
  @else
  <div style="background:var(--n);border:0.5px solid var(--bo);padding:24px;text-align:center;margin-bottom:32px;">
    <div style="font-size:24px;margin-bottom:10px;">✍️</div>
    <div style="font-family:'Playfair Display',serif;font-size:18px;color:var(--dk);margin-bottom:6px;">Want to leave a review?</div>
    <div style="font-size:12px;color:var(--fa);margin-bottom:16px;">Sign in to share your experience.</div>
    <a href="{{ route('login') }}" class="btn-dk" style="text-decoration:none;display:inline-block;">Sign In</a>
  </div>
  @endauth

  {{-- REVIEWS --}}
  <div style="border-top:0.5px solid var(--bo);padding-top:28px;">
    <div style="display:flex;align-items:center;margin-bottom:18px;">
      <h4 style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--dk);flex:1;">Student Reviews</h4>
      <span style="font-size:11px;color:var(--fa);">{{ $reviews->count() }} reviews</span>
    </div>
    @forelse($reviews as $review)
    <div style="background:var(--n);border:0.5px solid var(--bo);padding:16px 18px;margin-bottom:10px;transition:all 0.2s;" onmouseover="this.style.background='var(--n2)';this.style.transform='translateY(-1px)'" onmouseout="this.style.background='var(--n)';this.style.transform='none'">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
        <span style="font-size:13px;font-weight:700;color:var(--dk);">{{ $review->user->name ?? 'Anonymous' }}</span>
        <div style="display:flex;gap:2px;">
          @for($i=1;$i<=5;$i++)
          <span style="font-size:11px;color:{{ $i <= $review->rating ? 'var(--r)' : 'var(--bo)' }};">★</span>
          @endfor
        </div>
      </div>
      <p style="font-size:12px;color:var(--mu);line-height:1.65;font-style:italic;">"{{ Str::limit($review->comment, 120) }}"</p>
      <div style="font-size:10px;color:var(--fa);margin-top:6px;">{{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}</div>
      @if($review->admin_reply)
      <div style="background:rgba(194,112,128,0.07);border-left:2px solid var(--r);padding:8px 12px;margin-top:10px;">
        <div style="font-size:8.5px;letter-spacing:2px;text-transform:uppercase;color:var(--r);margin-bottom:3px;font-weight:700;">Reply</div>
        <div style="font-size:11px;color:var(--mu);">{{ Str::limit($review->admin_reply, 100) }}</div>
      </div>
      @endif
    </div>
    @empty
    <div style="text-align:center;padding:32px;color:var(--fa);font-size:13px;">No reviews yet.</div>
    @endforelse
  </div>

</div>

@endsection

@push('scripts')
<script>
function setStars(val) {
  document.getElementById('rating-input').value = val;
  document.querySelectorAll('#star-row button').forEach(function(btn) {
    const v = parseInt(btn.dataset.val);
    btn.style.color     = v <= val ? 'var(--r)' : 'var(--bo)';
    btn.style.transform = v === val ? 'scale(1.2)' : 'scale(1)';
  });
}
setStars(5);
</script>
@endpush