@extends('layouts.app')
@section('title', 'Search Parcel')

@section('content')

<div class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-tag">EZParcel · UPSI</div>
    <h1>Search <em>Parcel</em></h1>
  </div>
</div>

<div class="page-wrap">

  @if(session('success'))
  <div class="alert-success" style="margin-bottom:20px;">✓ {{ session('success') }}</div>
  @endif

  <form method="GET" action="{{ route('parcels.search') }}" style="margin-bottom:24px;">
    <div style="background:var(--w);border:0.5px solid var(--bo);padding:22px;">
      <span class="lbl">Tracking Number</span>
      <div style="display:flex;gap:8px;">
        <input class="inp" type="text" name="tracking_number"
          placeholder="e.g. JT123456789MY, MY123456789"
          value="{{ request('tracking_number') }}" autofocus>
        <button type="submit" class="btn-dk">Search</button>
      </div>
    </div>
  </form>

  @isset($parcels)
    @if($parcels->isEmpty())
      <div style="text-align:center;padding:64px 20px;">
        <div style="font-size:40px;margin-bottom:16px;">🔍</div>
        <div style="font-family:'Playfair Display',serif;font-size:22px;color:var(--dk);margin-bottom:8px;">No parcel found</div>
        <div style="font-size:13px;color:var(--fa);">Try a different tracking number</div>
      </div>
    @else
      @foreach($parcels as $parcel)
      <div style="background:var(--w);border:0.5px solid var(--bo);overflow:hidden;margin-bottom:16px;transition:box-shadow 0.25s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(44,24,16,0.08)'" onmouseout="this.style.boxShadow='none'">
        <div style="padding:13px 20px;background:var(--n2);border-bottom:0.5px solid var(--bo);display:flex;justify-content:space-between;align-items:center;">
          <strong style="font-size:13px;font-weight:700;color:var(--dk);">{{ $parcel->tracking_number }}</strong>
          @php
            $badge = match($parcel->status) {
              'registered' => ['b-r','Registered'],
              'booked'     => ['b-b','Booked'],
              'late'       => ['b-rd','Late'],
              'done'       => ['b-g','Collected'],
              default      => ['b-gr',ucfirst($parcel->status)],
            };
          @endphp
          <span class="badge {{ $badge[0] }}"><span class="bdot"></span>{{ $badge[1] }}</span>
        </div>
        <div style="padding:16px 20px;display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
          <div>
            <label style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);display:block;margin-bottom:3px;">Recipient</label>
            <span style="font-size:13px;font-weight:600;color:var(--dk);">{{ $parcel->recipient_name }}</span>
          </div>
          <div>
            <label style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);display:block;margin-bottom:3px;">Courier</label>
            <span style="font-size:13px;font-weight:600;color:var(--dk);">{{ $parcel->courier }}</span>
          </div>
          <div>
            <label style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);display:block;margin-bottom:3px;">Registered</label>
            <span style="font-size:13px;font-weight:600;color:var(--dk);">{{ \Carbon\Carbon::parse($parcel->arrived_date)->format('d M Y') }}</span>
          </div>
        </div>

        @if($parcel->status === 'late')
        <div style="padding:10px 20px;background:rgba(194,112,128,0.06);border-top:0.5px solid rgba(194,112,128,0.2);">
          <span style="font-size:12px;color:var(--r2);">⚠ Parcel melebihi 2 minggu. Denda: <strong>RM {{ number_format($parcel->late_fee, 2) }}</strong></span>
        </div>
        @endif

        @if(in_array($parcel->status,['registered','late']))
        <div style="padding:12px 20px;border-top:0.5px solid var(--bo);display:flex;justify-content:flex-end;">
          <form method="POST" action="{{ route('cart.add') }}">
            @csrf
            <input type="hidden" name="parcel_id" value="{{ $parcel->id }}">
            <button type="submit" class="btn-sm">Add to Cart</button>
          </form>
        </div>
        @elseif($parcel->status === 'booked')
        <div style="padding:12px 20px;border-top:0.5px solid var(--bo);">
          <span style="font-size:12px;color:var(--fa);">This parcel has already been booked.</span>
        </div>
        @elseif($parcel->status === 'done')
        <div style="padding:12px 20px;border-top:0.5px solid var(--bo);">
          <span style="font-size:12px;color:#166534;">This parcel has been collected. ✓</span>
        </div>
        @endif
      </div>
      @endforeach
    @endif
  @endisset

  @if(!request('tracking_number'))
  <div style="text-align:center;padding:64px 20px;">
    <div style="font-size:40px;margin-bottom:16px;">📦</div>
    <div style="font-family:'Playfair Display',serif;font-size:22px;color:var(--dk);">Search your parcel</div>
  </div>
  @endif

</div>
@endsection