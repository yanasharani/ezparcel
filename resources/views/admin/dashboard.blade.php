@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')

<div class="pg-hd">
  <div>
    <div class="pg-tag">Admin · EZParcel</div>
    <div class="pg-title">Dashboard</div>
  </div>
  <div style="font-size:11px;color:var(--fa);">{{ now()->format('l, d F Y') }}</div>
</div>

{{-- REVENUE HERO --}}
<div style="background:var(--dk);padding:28px 28px 32px;margin-bottom:20px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:-60px;right:-60px;width:220px;height:220px;background:rgba(255,255,255,0.03);border-radius:50%;pointer-events:none;"></div>
  <div style="position:absolute;bottom:-40px;left:-20px;width:150px;height:150px;background:rgba(194,112,128,0.05);border-radius:50%;pointer-events:none;"></div>
  <div style="position:relative;z-index:1;">
    <div style="font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(251,248,245,0.35);margin-bottom:10px;">Revenue Today</div>
    <div style="font-family:'Playfair Display',serif;font-size:48px;font-weight:600;color:#FBF8F5;letter-spacing:-2px;line-height:1;margin-bottom:8px;">RM {{ number_format($todayTotal, 2) }}</div>
    <div style="font-size:12px;color:rgba(251,248,245,0.35);margin-bottom:20px;">From {{ $todayBookings ?? 0 }} bookings · {{ now()->format('d M Y') }}</div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      @foreach([['QR',$todayQR ?? 0],['COD',$todayCOD ?? 0],['QR Total','RM '.number_format($todayQRTotal ?? 0,2)],['COD Total','RM '.number_format($todayCODTotal ?? 0,2)]] as $chip)
      <div style="background:rgba(255,255,255,0.07);border:0.5px solid rgba(255,255,255,0.1);padding:10px 16px;">
        <div style="font-family:'Playfair Display',serif;font-size:20px;color:#FBF8F5;font-weight:600;letter-spacing:-0.5px;">{{ $chip[1] }}</div>
        <div style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(251,248,245,0.35);margin-top:3px;">{{ $chip[0] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;">
  @foreach([
    ['Total Parcels',$totalParcels,'+'.($todayParcels??0).' today'],
    ['Total Bookings',$totalBookings,'+'.($todayBookings??0).' today'],
    ['Registered Users',$totalUsers,'+'.($newUsers??0).' new'],
    ['Pending',$pendingBookings,$pendingBookings > 0 ? 'Needs action' : 'All clear'],
  ] as $stat)
  <div style="background:var(--w);border:0.5px solid var(--bo);padding:20px 22px;transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(44,24,16,0.08)'" onmouseout="this.style.boxShadow='none'">
    <div style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);margin-bottom:8px;">{{ $stat[0] }}</div>
    <div style="font-family:'Playfair Display',serif;font-size:36px;font-weight:600;color:var(--dk);letter-spacing:-1px;line-height:1;margin-bottom:8px;">{{ $stat[1] }}</div>
    <span style="display:inline-block;padding:3px 10px;font-size:10px;font-weight:600;background:var(--n2);color:var(--mu);">{{ $stat[2] }}</span>
  </div>
  @endforeach
</div>

{{-- QUICK ACTIONS --}}
<div style="background:var(--w);border:0.5px solid var(--bo);padding:20px 22px;margin-bottom:20px;">
  <div style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--fa);margin-bottom:14px;">Quick Actions</div>
  <div style="display:flex;flex-wrap:wrap;gap:8px;">
    <a href="{{ route('admin.parcel-form') }}" class="btn-dk btn-sm">+ Add Parcel</a>
    <a href="{{ route('admin.parcels') }}" class="btn-out btn-sm">Manage Parcels</a>
    <a href="{{ route('admin.bookings') }}" class="btn-out btn-sm">Manage Bookings</a>
    <a href="{{ route('admin.reviews.index') }}" class="btn-out btn-sm">Reviews</a>
    <a href="{{ route('admin.qr-code') }}" class="btn-out btn-sm">QR Code</a>
    <a href="{{ route('admin.shop-status') }}" class="btn-out btn-sm">Shop Status</a>
  </div>
</div>

{{-- RECENT BOOKINGS --}}
<div class="tbl-wrap">
  <div class="tbl-hd">
    <div class="tbl-hd-title">Recent Bookings</div>
  </div>
  <table class="t">
    <tr>
      <th>ID</th><th>Student</th><th>Method</th><th>Total</th><th>Status</th>
    </tr>
    @forelse($recentBookings ?? [] as $booking)
    <tr>
      <td><strong style="font-family:'Playfair Display',serif;">#{{ $booking->id }}</strong></td>
      <td>{{ $booking->user->name ?? 'N/A' }}</td>
      <td>{{ ucfirst($booking->method) }}</td>
      <td style="font-family:'Playfair Display',serif;font-weight:600;color:var(--r);">RM {{ number_format($booking->total_amount,2) }}</td>
      <td>
        @php
          $b = match($booking->status) {
            'pending'=>['b-r','Pending'],'ready'=>['b-g','Ready'],
            'on_the_way'=>['b-p','On The Way'],'done'=>['b-gr','Done'],
            'cancelled'=>['b-rd','Cancelled'],default=>['b-gr',ucfirst($booking->status)]
          };
        @endphp
        <span class="badge {{ $b[0] }}"><span class="bdot"></span>{{ $b[1] }}</span>
      </td>
    </tr>
    @empty
    <tr><td colspan="5" style="text-align:center;color:var(--fa);padding:32px;">No bookings yet</td></tr>
    @endforelse
  </table>
</div>

@endsection