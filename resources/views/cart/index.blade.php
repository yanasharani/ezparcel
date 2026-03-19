@extends('layouts.app')
@section('title', 'My Cart')

@section('content')

<div class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-tag">EZParcel · UPSI</div>
    <h1>My <em>Cart</em></h1>
  </div>
</div>

<div class="page-wrap">

  @if($items->isEmpty())
    <div style="text-align:center;padding:80px 20px;">
      <div style="font-size:40px;margin-bottom:16px;">🛒</div>
      <div style="font-family:'Playfair Display',serif;font-size:22px;color:var(--dk);margin-bottom:8px;">Your cart is empty</div>
      <div style="font-size:13px;color:var(--fa);margin-bottom:24px;">Search for parcels and add them here</div>
      <a href="{{ route('home') }}" class="btn-dk">Search Parcel</a>
    </div>

  @else

  @foreach($items as $item)
  <div style="background:var(--w);border:0.5px solid var(--bo);padding:13px 18px;display:flex;align-items:center;gap:16px;margin-bottom:6px;transition:all 0.2s;" onmouseover="this.style.background='var(--n)';this.style.borderColor='var(--r4)'" onmouseout="this.style.background='var(--w)';this.style.borderColor='var(--bo)'">
    <div style="font-size:13px;font-weight:700;color:var(--dk);flex:1;">{{ $item->parcel->tracking_number }}</div>
    <div style="font-size:11px;color:var(--fa);">{{ $item->parcel->courier }}</div>
    <div style="font-size:13px;font-weight:700;color:var(--r);">
      RM {{ number_format($item->parcel->price + $item->parcel->late_fee, 2) }}
      @if($item->parcel->late_fee > 0)
      <span style="font-size:10px;color:var(--fa);font-weight:400;">(incl. denda)</span>
      @endif
    </div>
    <form method="POST" action="{{ route('cart.remove', $item->id) }}">
      @csrf @method('DELETE')
      <button type="submit" style="font-size:10px;font-weight:600;color:var(--fa);cursor:pointer;padding:5px 12px;border:0.5px solid var(--bo);background:var(--n);transition:all 0.2s;font-family:inherit;" onmouseover="this.style.borderColor='var(--r)';this.style.color='var(--r)'" onmouseout="this.style.borderColor='var(--bo)';this.style.color='var(--fa)'">Remove</button>
    </form>
  </div>
  @endforeach

  <div style="background:var(--dk);padding:14px 18px;display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
    <div>
      <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(251,248,245,0.4);">Total Amount</div>
      @if($items->sum(fn($i) => $i->parcel->late_fee) > 0)
      <div style="font-size:10px;color:rgba(212,144,154,0.7);margin-top:2px;">Termasuk denda RM {{ number_format($items->sum(fn($i) => $i->parcel->late_fee), 2) }}</div>
      @endif
    </div>
    <div style="display:flex;align-items:center;gap:16px;">
      <div style="font-family:'Playfair Display',serif;font-size:28px;font-weight:600;color:var(--r3);">RM {{ number_format($total, 2) }}</div>
      <a href="{{ route('checkout') }}" class="btn-r">Proceed to Checkout</a>
    </div>
  </div>

  <div style="margin-top:12px;">
    <a href="{{ route('home') }}" style="font-size:12px;color:var(--fa);text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--r)'" onmouseout="this.style.color='var(--fa)'">← Add More Parcels</a>
  </div>

  @endif
</div>
@endsection