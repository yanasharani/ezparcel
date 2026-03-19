@extends('layouts.app')
@section('title', 'Checkout')

@section('content')

<div class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-tag">EZParcel · UPSI</div>
    <h1>Check<em>out</em></h1>
  </div>
</div>

<div class="page-wrap">

  @if($errors->any())
  <div class="alert-error" style="margin-bottom:20px;">
    @foreach($errors->all() as $e)<div>⚠ {{ $e }}</div>@endforeach
  </div>
  @endif

  <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

    <form method="POST" action="{{ route('booking.store') }}" id="checkout-form" novalidate>
      @csrf

      {{-- PARCELS --}}
      <div style="background:var(--w);border:0.5px solid var(--bo);padding:22px;margin-bottom:16px;">
        <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);margin-bottom:14px;">
          Parcels ({{ $items->count() }})
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
          @foreach($items as $item)
          <span style="display:inline-flex;align-items:center;gap:6px;background:var(--n);border:0.5px solid var(--bo);padding:6px 14px;font-size:12px;color:var(--dk);font-weight:600;">
            📦 {{ $item->parcel->tracking_number }}
          </span>
          @endforeach
        </div>
      </div>

      {{-- RECIPIENT --}}
      <div style="background:var(--w);border:0.5px solid var(--bo);padding:22px;margin-bottom:16px;">
        <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);margin-bottom:16px;">Recipient Details</div>
        <span class="lbl">Full Name *</span>
        <input class="inp" type="text" name="recipient_name" id="recipient_name"
          value="{{ old('recipient_name', auth()->user()->name) }}"
          placeholder="Your full name" required oninput="updateChecklist()">
        @error('recipient_name')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
      </div>

      {{-- METHOD --}}
      <div style="background:var(--w);border:0.5px solid var(--bo);padding:22px;margin-bottom:16px;">
        <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);margin-bottom:16px;">Collection Method</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
          <label id="opt-pickup" onclick="setMethod('pickup')"
            style="padding:18px;border:1px solid var(--r);background:rgba(194,112,128,0.05);cursor:pointer;transition:all .25s;text-align:center;">
            <input type="radio" name="method" value="pickup" checked style="display:none">
            <div style="font-size:20px;margin-bottom:6px;">🏪</div>
            <div style="font-size:12px;font-weight:700;color:var(--dk);margin-bottom:2px;">Pickup</div>
            <div style="font-size:10px;color:var(--fa);">No extra charge</div>
          </label>
          <label id="opt-delivery" onclick="setMethod('delivery')"
            style="padding:18px;border:0.5px solid var(--bo);cursor:pointer;transition:all .25s;text-align:center;">
            <input type="radio" name="method" value="delivery" style="display:none">
            <div style="font-size:20px;margin-bottom:6px;">🚗</div>
            <div style="font-size:12px;font-weight:700;color:var(--dk);margin-bottom:2px;">Delivery</div>
            <div style="font-size:10px;color:var(--fa);">+ RM 3.00</div>
          </label>
        </div>
        <div id="addr-wrap" style="display:none">
          <span class="lbl">Delivery Address *</span>
          <textarea class="inp" name="delivery_address" rows="3"
            placeholder="Room number, block, college..."
            oninput="updateChecklist()">{{ old('delivery_address') }}</textarea>
          @error('delivery_address')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- DATE & TIME --}}
      <div style="background:var(--w);border:0.5px solid var(--bo);padding:22px;margin-bottom:16px;">
        <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);margin-bottom:16px;">Date & Time</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div>
            <span class="lbl">Booking Date *</span>
            <input class="inp" type="date" name="booking_date" id="booking_date"
              min="{{ date('Y-m-d') }}" value="{{ old('booking_date') }}" required
              onchange="updateChecklist()">
            @error('booking_date')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div>
            <span class="lbl">Booking Time *</span>
            @if(count($slots) > 0)
            <select class="inp" name="booking_time" id="booking_time" required onchange="updateChecklist()">
              <option value="">— Select time —</option>
              @foreach($slots as $slot)
              <option value="{{ $slot }}" {{ old('booking_time')===$slot?'selected':'' }}>{{ $slot }}</option>
              @endforeach
            </select>
            @else
            <div style="padding:12px 16px;background:var(--n);border:0.5px solid var(--bo);font-size:12px;color:var(--fa);">
              No time slots available. Please contact admin.
            </div>
            <input type="hidden" name="booking_time" value="">
            @endif
            @error('booking_time')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>

    </form>

    {{-- SUMMARY SIDEBAR --}}
    <div style="position:sticky;top:80px;">
      <div style="background:var(--w);border:0.5px solid var(--bo);overflow:hidden;">
        <div style="padding:16px 20px;background:var(--n2);border-bottom:0.5px solid var(--bo);">
          <span style="font-size:13px;font-weight:700;color:var(--dk);">Order Summary</span>
        </div>
        <div style="padding:18px 20px;">

          {{-- CHECKLIST --}}
          <div style="margin-bottom:16px;display:flex;flex-direction:column;gap:7px;">
            <div id="chk-name" style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--fa);">
              <span id="chk-name-ico">○</span> Full name
            </div>
            <div id="chk-date" style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--fa);">
              <span id="chk-date-ico">○</span> Booking date
            </div>
            <div id="chk-time" style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--fa);">
              <span id="chk-time-ico">○</span> Booking time
            </div>
            <div id="chk-addr" style="display:none;align-items:center;gap:8px;font-size:12px;color:var(--fa);">
              <span id="chk-addr-ico">○</span> Delivery address
            </div>
          </div>

          <div style="height:0.5px;background:var(--bo);margin-bottom:14px;"></div>

          <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
            <span style="font-size:12px;color:var(--fa);">Parcels</span>
            <span style="font-size:12px;color:var(--dk);font-weight:600;">{{ $items->count() }} item(s)</span>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
            <span style="font-size:12px;color:var(--fa);">Subtotal</span>
            <span style="font-size:12px;color:var(--dk);">RM {{ number_format($items->sum(fn($i) => $i->parcel->price), 2) }}</span>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
            <span style="font-size:12px;color:var(--fa);">Delivery fee</span>
            <span style="font-size:12px;color:var(--fa);" id="delivery-display">—</span>
          </div>
          <div style="height:0.5px;background:var(--bo);margin-bottom:14px;"></div>

          <button type="button" onclick="submitCheck()" id="submit-btn"
            style="width:100%;background:var(--r);color:#fff;padding:14px;font-size:13px;font-weight:700;border:none;cursor:not-allowed;transition:all 0.25s;opacity:0.4;letter-spacing:0.3px;">
            Proceed to Payment →
          </button>

          <div id="submit-hint" style="font-size:11px;color:var(--fa);text-align:center;margin-top:8px;">
            Please fill in all required fields
          </div>

          <a href="{{ route('cart.index') }}" style="display:block;text-align:center;margin-top:10px;font-size:12px;color:var(--fa);text-decoration:none;" onmouseover="this.style.color='var(--r)'" onmouseout="this.style.color='var(--fa)'">
            ← Back to Cart
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
let currentMethod = 'pickup';

function setMethod(m) {
  currentMethod = m;
  const pickup   = document.getElementById('opt-pickup');
  const delivery = document.getElementById('opt-delivery');
  const addrWrap = document.getElementById('addr-wrap');
  const display  = document.getElementById('delivery-display');
  const chkAddr  = document.getElementById('chk-addr');

  if (m === 'pickup') {
    pickup.style.border   = '1px solid var(--r)';
    pickup.style.background = 'rgba(194,112,128,0.05)';
    delivery.style.border = '0.5px solid var(--bo)';
    delivery.style.background = 'transparent';
    addrWrap.style.display = 'none';
    chkAddr.style.display  = 'none';
    display.textContent = '—';
    pickup.querySelector('input').checked = true;
  } else {
    delivery.style.border = '1px solid var(--r)';
    delivery.style.background = 'rgba(194,112,128,0.05)';
    pickup.style.border   = '0.5px solid var(--bo)';
    pickup.style.background = 'transparent';
    addrWrap.style.display = 'block';
    chkAddr.style.display  = 'flex';
    display.textContent = '+ RM 3.00';
    delivery.querySelector('input').checked = true;
  }
  updateChecklist();
}

function updateChecklist() {
  const name = document.getElementById('recipient_name')?.value.trim();
  const date = document.getElementById('booking_date')?.value;
  const time = document.getElementById('booking_time')?.value;
  const addr = document.querySelector('[name="delivery_address"]')?.value.trim();
  const needAddr = currentMethod === 'delivery';

  const fields = {
    name: !!name,
    date: !!date,
    time: !!time,
  };

  Object.entries(fields).forEach(([k, ok]) => {
    const ico = document.getElementById('chk-' + k + '-ico');
    const row = document.getElementById('chk-' + k);
    if (!ico || !row) return;
    ico.textContent = ok ? '✓' : '○';
    row.style.color = ok ? '#166534' : 'var(--fa)';
  });

  if (needAddr) {
    const addrIco = document.getElementById('chk-addr-ico');
    const addrRow = document.getElementById('chk-addr');
    if (addrIco && addrRow) {
      addrIco.textContent = addr ? '✓' : '○';
      addrRow.style.color = addr ? '#166534' : 'var(--fa)';
    }
  }

  const allOk = fields.name && fields.date && fields.time && (!needAddr || !!addr);
  const btn  = document.getElementById('submit-btn');
  const hint = document.getElementById('submit-hint');

  if (allOk) {
    btn.style.opacity = '1';
    btn.style.cursor  = 'pointer';
    hint.style.display = 'none';
  } else {
    btn.style.opacity = '0.4';
    btn.style.cursor  = 'not-allowed';
    hint.style.display = 'block';
  }
}

function submitCheck() {
  const name = document.getElementById('recipient_name')?.value.trim();
  const date = document.getElementById('booking_date')?.value;
  const time = document.getElementById('booking_time')?.value;
  const addr = document.querySelector('[name="delivery_address"]')?.value.trim();
  const needAddr = currentMethod === 'delivery';

  if (!name || !date || !time || (needAddr && !addr)) {
    document.getElementById('submit-hint').style.display = 'block';
    return;
  }
  document.getElementById('checkout-form').submit();
}

document.addEventListener('DOMContentLoaded', () => {
  setMethod('pickup');
  updateChecklist();
});
</script>
@endpush