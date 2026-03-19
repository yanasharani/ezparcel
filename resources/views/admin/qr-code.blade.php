@extends('admin.layout')
@section('title', 'QR Code')

@section('content')

<div class="admin-topbar">
  <div>
    <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:var(--t4);margin-bottom:6px;font-weight:500">Admin Panel</div>
    <div class="at-greet">Payment <span>QR Code</span></div>
  </div>
</div>

<div style="max-width:560px;">

  @if($qrFilename)
  {{-- CURRENT QR --}}
  <div class="admin-table" style="margin-bottom:20px;">
    <div class="at-head">
      <div class="at-title">Current QR Code</div>
      <form method="POST" action="{{ route('admin.qr-code.delete') }}"
        onsubmit="return confirm('Delete this QR code?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-sec btn-sm"
          style="border-color:rgba(180,80,60,.2);color:#8A3020">
          Delete
        </button>
      </form>
    </div>
    <div style="padding:36px;text-align:center;">
      <div style="display:inline-block;background:white;border-radius:20px;padding:20px;border:1px solid var(--linen);box-shadow:0 8px 32px rgba(58,28,12,.08);">
        <img src="{{ asset('images/' . $qrFilename) }}" alt="QR Code"
          style="width:200px;height:200px;object-fit:contain;display:block;">
      </div>
      <div style="margin-top:16px;font-size:12.5px;font-weight:300;color:var(--t3);">
        Students will scan this to make payment
      </div>
    </div>
  </div>

  @else
  {{-- NO QR --}}
  <div class="admin-table" style="margin-bottom:20px;">
    <div style="padding:48px 32px;text-align:center;">
      <div style="font-size:56px;margin-bottom:16px;">📲</div>
      <div style="font-family:'Cormorant',serif;font-size:24px;font-weight:400;color:var(--ink);margin-bottom:8px;">No QR Code Yet</div>
      <div style="font-size:13px;font-weight:300;color:var(--t3);">Upload your payment QR code below</div>
    </div>
  </div>
  @endif

  {{-- UPLOAD FORM --}}
  <div class="admin-table">
    <div class="at-head">
      <div class="at-title">{{ $qrFilename ? 'Replace QR Code' : 'Upload QR Code' }}</div>
    </div>
    <div style="padding:28px 32px;">
      <form method="POST" action="{{ route('admin.qr-code.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
          <label class="label">QR Code Image</label>
          <div style="border:1.5px dashed var(--blush);border-radius:14px;padding:28px;text-align:center;cursor:pointer;transition:all .3s;position:relative;">
            <div style="font-size:28px;margin-bottom:8px;">📎</div>
            <div style="font-size:13.5px;font-weight:400;color:var(--t2);margin-bottom:4px;">Click to upload QR image</div>
            <div style="font-size:12px;font-weight:300;color:var(--t4);">JPG, PNG · Max 5MB</div>
            <input type="file" name="qr_image" accept="image/*" required
              style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%">
          </div>
          @error('qr_image')
          <div style="font-size:11px;color:#8A3020;margin-top:5px">{{ $message }}</div>
          @enderror
        </div>

        <div class="divider"></div>

        <button type="submit" class="btn-main">
          {{ $qrFilename ? 'Replace QR Code' : 'Upload QR Code' }}
        </button>

      </form>
    </div>
  </div>

</div>

@endsection