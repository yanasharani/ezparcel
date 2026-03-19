@extends('admin.layout')
@section('title', 'Booking Slots')

@section('content')

<div class="pg-hd">
  <div>
    <div class="pg-tag">Admin · EZParcel</div>
    <div class="pg-title">Booking <em>Slots</em></div>
  </div>
</div>

<div style="max-width:520px;">
  <div class="tbl-wrap" style="margin-bottom:16px;">
    <div class="tbl-hd">
      <div class="tbl-hd-title">Available Time Slots</div>
    </div>
    <div style="padding:24px 28px;">
      <div style="font-size:12px;color:var(--fa);margin-bottom:16px;">Slot masa yang boleh dipilih user masa booking. Tekan Save untuk simpan.</div>
      <form method="POST" action="{{ route('admin.booking-slots.update') }}" id="slots-form">
        @csrf
        <div id="slots-wrap" style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;">
          @if(count($slots) > 0)
            @foreach($slots as $slot)
            <div class="slot-row" style="display:flex;gap:8px;align-items:center;">
              <input class="inp" type="text" name="slots[]" value="{{ $slot }}" placeholder="e.g. 9:00 AM">
              <button type="button" onclick="removeSlot(this)"
                style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;background:rgba(185,28,28,0.06);border:0.5px solid rgba(185,28,28,0.2);cursor:pointer;font-size:14px;color:#991b1b;flex-shrink:0;transition:all .2s;">✕</button>
            </div>
            @endforeach
          @else
            <div class="slot-row" style="display:flex;gap:8px;align-items:center;">
              <input class="inp" type="text" name="slots[]" placeholder="e.g. 9:00 AM">
              <button type="button" onclick="removeSlot(this)"
                style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;background:rgba(185,28,28,0.06);border:0.5px solid rgba(185,28,28,0.2);cursor:pointer;font-size:14px;color:#991b1b;flex-shrink:0;transition:all .2s;">✕</button>
            </div>
          @endif
        </div>
        <div style="display:flex;gap:8px;">
          <button type="button" onclick="addSlot()" class="btn-out btn-sm">+ Add Slot</button>
          <button type="submit" class="btn-dk btn-sm">💾 Save Slots</button>
        </div>
      </form>
    </div>
  </div>

  {{-- PREVIEW --}}
  <div class="tbl-wrap">
    <div class="tbl-hd"><div class="tbl-hd-title">Preview</div></div>
    <div style="padding:18px 24px;">
      @if(count($slots) > 0)
      <div style="display:flex;flex-wrap:wrap;gap:8px;">
        @foreach($slots as $slot)
        <span style="display:inline-flex;align-items:center;gap:6px;background:var(--n);border:0.5px solid var(--bo);padding:7px 14px;font-size:12px;color:var(--dk);font-weight:600;">
          🕐 {{ $slot }}
        </span>
        @endforeach
      </div>
      @else
      <div style="font-size:12px;color:var(--fa);font-style:italic;">Tiada slot masa lagi.</div>
      @endif
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function addSlot() {
  const wrap = document.getElementById('slots-wrap');
  const div  = document.createElement('div');
  div.className = 'slot-row';
  div.style.cssText = 'display:flex;gap:8px;align-items:center;';
  div.innerHTML = `
    <input class="inp" type="text" name="slots[]" placeholder="e.g. 10:00 AM">
    <button type="button" onclick="removeSlot(this)"
      style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;background:rgba(185,28,28,0.06);border:0.5px solid rgba(185,28,28,0.2);cursor:pointer;font-size:14px;color:#991b1b;flex-shrink:0;transition:all .2s;">✕</button>
  `;
  wrap.appendChild(div);
  div.querySelector('input').focus();
}

function removeSlot(btn) {
  const rows = document.querySelectorAll('.slot-row');
  if (rows.length <= 1) {
    btn.closest('.slot-row').querySelector('input').value = '';
    return;
  }
  btn.closest('.slot-row').remove();
}
</script>
@endpush