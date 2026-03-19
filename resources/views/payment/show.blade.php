@extends('layouts.app')
@section('title', 'Payment')

@section('content')

<div class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-tag">EZParcel · UPSI</div>
    <h1>Pay<em>ment</em></h1>
  </div>
</div>

<div class="page-wrap">

  @if($errors->any())
  <div class="alert-error" style="margin-bottom:20px;">
    @foreach($errors->all() as $e)<div>⚠ {{ $e }}</div>@endforeach
  </div>
  @endif

  <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

    <div>
      <form method="POST" action="{{ route('payment.process', $booking->id) }}"
        enctype="multipart/form-data" id="payment-form" novalidate>
        @csrf

        {{-- PAYMENT METHOD --}}
        <div style="background:var(--w);border:0.5px solid var(--bo);padding:22px;margin-bottom:16px;">
          <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);margin-bottom:16px;">Payment Method</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">

            <label id="opt-cod" onclick="setPay('cod')"
              style="padding:18px;border:1px solid var(--r);background:rgba(194,112,128,0.05);cursor:pointer;text-align:center;transition:all .25s;">
              <input type="radio" name="method" value="cod" checked style="display:none">
              <div style="font-size:20px;margin-bottom:6px;">💵</div>
              <div style="font-size:12px;font-weight:700;color:var(--dk);margin-bottom:2px;">Cash on Delivery</div>
              <div style="font-size:10px;color:var(--fa);">Pay when you collect</div>
            </label>

            <label id="opt-qr" onclick="setPay('qr')"
              style="padding:18px;border:0.5px solid var(--bo);cursor:pointer;text-align:center;transition:all .25s;">
              <input type="radio" name="method" value="qr" style="display:none">
              <div style="font-size:20px;margin-bottom:6px;">📱</div>
              <div style="font-size:12px;font-weight:700;color:var(--dk);margin-bottom:2px;">QR Payment</div>
              <div style="font-size:10px;color:var(--fa);">Scan & upload receipt</div>
            </label>

          </div>

          {{-- COD --}}
          <div id="cod-section">
            <div style="background:rgba(194,112,128,0.06);border:0.5px solid rgba(194,112,128,0.2);padding:16px 18px;">
              <div style="font-size:10px;font-weight:700;letter-spacing:1px;color:var(--r2);margin-bottom:5px;">Cash on Delivery Selected</div>
              <div style="font-size:13px;color:var(--mu);">Please prepare <strong>RM {{ number_format($booking->total_amount, 2) }}</strong> when collecting your parcel.</div>
            </div>
          </div>

          {{-- QR --}}
          <div id="qr-section" style="display:none;">
            <div style="background:var(--n);border:0.5px solid var(--bo);padding:24px;text-align:center;margin-bottom:16px;">
              @if($qrFilename)
                <img src="{{ asset('images/' . $qrFilename) }}" alt="QR Code"
                  style="width:180px;height:180px;object-fit:contain;display:block;margin:0 auto 10px;">
              @else
                <div style="width:140px;height:140px;background:var(--bo);margin:0 auto 10px;display:flex;align-items:center;justify-content:center;font-size:11px;color:var(--fa);">QR Code</div>
              @endif
              <div style="font-size:13px;color:var(--mu);">Scan to pay <strong style="color:var(--dk)">RM {{ number_format($booking->total_amount, 2) }}</strong></div>
            </div>

            {{-- UPLOAD RECEIPT --}}
            <div style="background:rgba(194,112,128,0.06);border:0.5px solid rgba(194,112,128,0.2);padding:12px 16px;margin-bottom:14px;">
              <div style="font-size:11px;font-weight:700;color:var(--r2);margin-bottom:3px;">⚠ Receipt Upload Required</div>
              <div style="font-size:12px;color:var(--mu);">You must upload your payment receipt before proceeding.</div>
            </div>

            <span class="lbl">Upload Payment Receipt *</span>
            <p style="font-size:12px;color:var(--fa);margin-bottom:12px;">After scanning the QR code, upload a screenshot of your receipt. Format: JPG, PNG (max 5MB).</p>

            <div id="pay-drop-zone" onclick="document.getElementById('pay-receipt-input').click()"
              style="border:1px dashed var(--bo);padding:28px;text-align:center;cursor:pointer;transition:all .3s;background:var(--n);"
              onmouseover="this.style.borderColor='var(--r)'" onmouseout="if(!hasPayReceipt)this.style.borderColor='var(--bo)'">
              <div style="font-size:28px;margin-bottom:8px;">📎</div>
              <div style="font-size:13px;color:var(--mu);">Click to upload receipt</div>
              <div style="font-size:11px;color:var(--fa);margin-top:4px;">JPG or PNG — max 5MB</div>
            </div>

            <input type="file" id="pay-receipt-input" name="receipt"
              accept=".jpg,.jpeg,.png" style="display:none" onchange="handlePayFile(this)">

            <div id="pay-file-preview" style="display:none;margin-top:10px;">
              <div style="display:flex;align-items:center;gap:10px;background:rgba(21,128,61,0.08);border:0.5px solid rgba(21,128,61,0.2);padding:12px 16px;">
                <div style="font-size:20px;">🖼️</div>
                <div style="flex:1;">
                  <div id="pay-file-name" style="font-size:12px;font-weight:700;color:var(--dk);"></div>
                  <div id="pay-file-size" style="font-size:10px;color:var(--fa);margin-top:2px;"></div>
                </div>
                <button type="button" onclick="clearPayFile()" style="background:none;border:none;cursor:pointer;font-size:16px;color:var(--fa);">✕</button>
              </div>
              <img id="pay-img-preview" src="" alt="Receipt"
                style="width:100%;max-height:200px;object-fit:contain;background:var(--n);margin-top:6px;">
            </div>

            @error('receipt')
            <div style="font-size:11px;color:var(--r2);margin-top:6px;">⚠ {{ $message }}</div>
            @enderror
          </div>

        </div>

        {{-- SUBMIT --}}
        <button type="button" onclick="submitPayment()" id="pay-submit-btn"
          style="width:100%;background:var(--r);color:#fff;padding:15px;font-size:13px;font-weight:700;border:none;cursor:pointer;transition:all 0.25s;letter-spacing:0.3px;">
          Confirm Booking →
        </button>

        <div id="pay-hint" style="display:none;text-align:center;font-size:12px;color:var(--r2);margin-top:8px;padding:10px;background:rgba(194,112,128,0.08);border:0.5px solid rgba(194,112,128,0.2);">
          ⚠ Please upload your QR payment receipt before confirming.
        </div>

      </form>
    </div>

    {{-- ORDER SUMMARY --}}
    <div style="position:sticky;top:80px;">
      <div class="tbl">
        <div class="tbl-top"><span>Order Summary</span></div>
        <table class="t">
          <tr><td style="color:var(--fa);font-size:11px;">Booking ID</td><td><strong style="font-family:'Playfair Display',serif;">#{{ $booking->id }}</strong></td></tr>
          <tr><td style="color:var(--fa);font-size:11px;">Method</td><td>{{ ucfirst($booking->method) }}</td></tr>
          <tr><td style="color:var(--fa);font-size:11px;">Recipient</td><td>{{ $booking->recipient_name }}</td></tr>
          <tr><td style="color:var(--fa);font-size:11px;">Date</td><td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td></tr>
          <tr><td style="color:var(--fa);font-size:11px;">Time</td><td>{{ $booking->booking_time }}</td></tr>
          <tr>
            <td style="color:var(--fa);font-size:11px;">Total</td>
            <td><strong style="font-family:'Playfair Display',serif;font-size:22px;color:var(--r);">RM {{ number_format($booking->total_amount, 2) }}</strong></td>
          </tr>
        </table>
      </div>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
let currentPay    = 'cod';
let hasPayReceipt = false;

function setPay(m) {
  currentPay = m;
  const cod = document.getElementById('opt-cod');
  const qr  = document.getElementById('opt-qr');
  const hint = document.getElementById('pay-hint');

  if (m === 'cod') {
    cod.style.border = '1px solid var(--r)';
    cod.style.background = 'rgba(194,112,128,0.05)';
    qr.style.border  = '0.5px solid var(--bo)';
    qr.style.background = 'transparent';
    cod.querySelector('input').checked = true;
    document.getElementById('cod-section').style.display = 'block';
    document.getElementById('qr-section').style.display  = 'none';
    hint.style.display = 'none';
  } else {
    qr.style.border  = '1px solid var(--r)';
    qr.style.background = 'rgba(194,112,128,0.05)';
    cod.style.border = '0.5px solid var(--bo)';
    cod.style.background = 'transparent';
    qr.querySelector('input').checked = true;
    document.getElementById('qr-section').style.display  = 'block';
    document.getElementById('cod-section').style.display = 'none';
  }
}

function handlePayFile(input) {
  const file = input.files[0];
  if (!file) return;
  hasPayReceipt = true;

  const dz = document.getElementById('pay-drop-zone');
  dz.style.borderColor = 'var(--r)';
  dz.style.background  = 'rgba(194,112,128,0.04)';
  dz.style.borderStyle = 'solid';

  document.getElementById('pay-file-preview').style.display = 'block';
  document.getElementById('pay-file-name').textContent = file.name;
  document.getElementById('pay-file-size').textContent = (file.size / 1024).toFixed(1) + ' KB';

  const reader = new FileReader();
  reader.onload = e => { document.getElementById('pay-img-preview').src = e.target.result; };
  reader.readAsDataURL(file);

  document.getElementById('pay-hint').style.display = 'none';
}

function clearPayFile() {
  document.getElementById('pay-receipt-input').value = '';
  document.getElementById('pay-file-preview').style.display = 'none';
  const dz = document.getElementById('pay-drop-zone');
  dz.style.borderColor = 'var(--bo)';
  dz.style.background  = 'var(--n)';
  dz.style.borderStyle = 'dashed';
  hasPayReceipt = false;
}

function submitPayment() {
  if (currentPay === 'qr' && !hasPayReceipt) {
    const hint = document.getElementById('pay-hint');
    hint.style.display = 'block';
    document.getElementById('pay-drop-zone').style.borderColor = 'var(--r2)';
    document.getElementById('pay-drop-zone').style.borderStyle = 'solid';
    document.getElementById('pay-drop-zone').scrollIntoView({ behavior:'smooth', block:'center' });
    return;
  }
  document.getElementById('payment-form').submit();
}

document.addEventListener('DOMContentLoaded', () => { setPay('cod'); });
</script>
@endpush