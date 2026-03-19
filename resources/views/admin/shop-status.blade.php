@extends('admin.layout')
@section('title', 'Shop Status')

@section('content')

<div class="pg-hd">
  <div>
    <div class="pg-tag">Admin · EZParcel</div>
    <div class="pg-title">Shop <em>Status</em></div>
  </div>
</div>

@php
  $isOpen = \DB::table('settings')->where('key','shop_is_open')->value('value');
  $currentOpen = $isOpen === null ? true : (bool)(int)$isOpen;
@endphp

<div style="max-width:520px;">

  <div class="tbl-wrap" style="margin-bottom:16px;">
    <div style="padding:24px 28px;display:flex;align-items:center;gap:16px;">
      <div style="font-size:32px;">{{ $currentOpen ? '🟢' : '🔴' }}</div>
      <div>
        <div style="font-size:14px;font-weight:700;color:{{ $currentOpen ? '#166534' : '#991b1b' }};margin-bottom:3px;">
          Shop is currently {{ $currentOpen ? 'Open' : 'Closed' }}
        </div>
        <div style="font-size:12px;color:var(--fa);">Students can see the {{ $currentOpen ? 'Open Now' : 'Closed' }} badge</div>
      </div>
      <span class="badge {{ $currentOpen ? 'b-g' : 'b-rd' }}" style="margin-left:auto;">{{ $currentOpen ? '● Open' : '● Closed' }}</span>
    </div>
  </div>

  <div class="tbl-wrap">
    <div class="tbl-hd"><div class="tbl-hd-title">Update Status</div></div>
    <div style="padding:24px 28px;">
      <form method="POST" action="{{ route('admin.shop-status.update') }}">
        @csrf
        <div class="form-group">
          <label class="lbl">Status</label>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:4px;">
            <label id="opt-open" onclick="setShop('open')" style="padding:18px;border:0.5px solid var(--bo);text-align:center;cursor:pointer;transition:all .25s;">
              <input type="radio" name="is_open" value="1" style="display:none" {{ $currentOpen?'checked':'' }}>
              <div style="font-size:22px;margin-bottom:6px;">🏪</div>
              <div style="font-size:12px;font-weight:700;color:#166534;margin-bottom:2px;">Open</div>
              <div style="font-size:10px;color:var(--fa);">Show open badge</div>
            </label>
            <label id="opt-close" onclick="setShop('close')" style="padding:18px;border:0.5px solid var(--bo);text-align:center;cursor:pointer;transition:all .25s;">
              <input type="radio" name="is_open" value="0" style="display:none" {{ !$currentOpen?'checked':'' }}>
              <div style="font-size:22px;margin-bottom:6px;">🔒</div>
              <div style="font-size:12px;font-weight:700;color:#991b1b;margin-bottom:2px;">Closed</div>
              <div style="font-size:10px;color:var(--fa);">Show closed badge</div>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label class="lbl">Notice <span style="font-weight:300;text-transform:none;letter-spacing:0;">(optional)</span></label>
          <textarea class="inp" name="notice" rows="2"
            placeholder="e.g. Closed for public holiday">{{ \DB::table('settings')->where('key','shop_notice')->value('value') }}</textarea>
        </div>
        <div class="divider"></div>
        <button type="submit" class="btn-dk">Update Status</button>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function setShop(val) {
  const o = document.getElementById('opt-open');
  const c = document.getElementById('opt-close');
  if (val === 'open') {
    o.style.borderColor = 'rgba(21,128,61,0.4)'; o.style.background = 'rgba(21,128,61,0.05)';
    c.style.borderColor = 'var(--bo)'; c.style.background = 'transparent';
    o.querySelector('input').checked = true;
  } else {
    c.style.borderColor = 'rgba(185,28,28,0.3)'; c.style.background = 'rgba(185,28,28,0.04)';
    o.style.borderColor = 'var(--bo)'; o.style.background = 'transparent';
    c.querySelector('input').checked = true;
  }
}
@if($currentOpen) setShop('open'); @else setShop('close'); @endif
</script>
@endpush