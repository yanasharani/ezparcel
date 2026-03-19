@extends('admin.layout')
@section('title', 'Reviews')

@section('content')

<div class="pg-hd">
  <div>
    <div class="pg-tag">Admin · EZParcel</div>
    <div class="pg-title">Customer <em>Reviews</em></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
  @foreach([['Total Reviews',$reviews->count(),'var(--dk)'],['Replied',$reviews->whereNotNull('admin_reply')->count(),'#166534'],['Pending',$reviews->whereNull('admin_reply')->count(),'var(--r2)']] as $s)
  <div style="background:var(--w);border:0.5px solid var(--bo);padding:20px 22px;">
    <div style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);margin-bottom:8px;">{{ $s[0] }}</div>
    <div style="font-family:'Playfair Display',serif;font-size:36px;font-weight:600;color:{{ $s[2] }};letter-spacing:-1px;line-height:1;">{{ $s[1] }}</div>
  </div>
  @endforeach
</div>

@forelse($reviews as $review)
<div class="tbl-wrap" style="margin-bottom:14px;">
  <div class="tbl-hd">
    <div style="display:flex;align-items:center;gap:12px;">
      <div style="width:36px;height:36px;border-radius:50%;background:var(--r);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">
        {{ strtoupper(substr($review->user->name??'A',0,1)) }}
      </div>
      <div>
        <div style="font-size:13px;font-weight:700;color:var(--dk);">{{ $review->user->name??'Anonymous' }}</div>
        <div style="font-size:10px;color:var(--fa);margin-top:1px;">{{ $review->user->matric_number??'' }} · {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}</div>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
      <div style="font-size:13px;letter-spacing:2px;">
        @for($i=1;$i<=5;$i++)<span style="color:{{ $i<=$review->rating ? 'var(--r)' : 'var(--bo)' }};">★</span>@endfor
      </div>
      @if($review->admin_reply)
        <span class="badge b-g">✓ Replied</span>
      @else
        <span class="badge b-r">Pending</span>
      @endif
    </div>
  </div>
  <div style="padding:20px 24px;">
    <p style="font-family:'Playfair Display',serif;font-size:15px;font-style:italic;color:var(--dk);line-height:1.75;margin-bottom:16px;">"{{ $review->comment }}"</p>
    @if($review->admin_reply)
    <div style="background:rgba(194,112,128,0.07);border-left:2px solid var(--r);padding:12px 16px;margin-bottom:16px;">
      <div style="font-size:8px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--r);margin-bottom:4px;">Your Reply</div>
      <div style="font-size:12.5px;color:var(--mu);">{{ $review->admin_reply }}</div>
    </div>
    @endif
    <form method="POST" action="{{ route('admin.reviews.reply',$review->id) }}">
      @csrf
      <div class="form-group" style="margin-bottom:10px;">
        <label class="lbl">{{ $review->admin_reply ? 'Edit Reply' : 'Write Reply' }}</label>
        <textarea class="inp" name="admin_reply" rows="2" placeholder="Write a reply...">{{ $review->admin_reply }}</textarea>
      </div>
      <button type="submit" class="btn-dk btn-sm">{{ $review->admin_reply ? 'Update Reply' : 'Send Reply' }}</button>
    </form>
  </div>
</div>
@empty
<div style="text-align:center;padding:60px;background:var(--w);border:0.5px solid var(--bo);">
  <div style="font-size:40px;margin-bottom:12px;">⭐</div>
  <div style="font-family:'Playfair Display',serif;font-size:22px;color:var(--dk);">No reviews yet</div>
</div>
@endforelse

@endsection