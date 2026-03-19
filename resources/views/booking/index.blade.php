@extends('layouts.app')
@section('title', 'My Bookings')

@section('content')

<div class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-tag">EZParcel · UPSI</div>
    <h1>My <em>Bookings</em></h1>
  </div>
</div>

<div class="page-wrap">

  @if(session('success'))
  <div class="alert-success" style="margin-bottom:20px;">✓ {{ session('success') }}</div>
  @endif

  @if($bookings->isEmpty())
  <div style="text-align:center;padding:80px 20px;">
    <div style="font-size:40px;margin-bottom:16px;">📋</div>
    <div style="font-family:'Playfair Display',serif;font-size:22px;color:var(--dk);margin-bottom:8px;">No bookings yet</div>
    <div style="font-size:13px;color:var(--fa);margin-bottom:24px;">Search for your parcel and make a booking</div>
    <a href="{{ route('home') }}" class="btn-dk">Search Parcel</a>
  </div>

  @else
  <div class="tbl">
    <div class="tbl-top">
      <span>All Bookings <span style="color:var(--fa);font-weight:400;">({{ $bookings->count() }})</span></span>
    </div>
    <table class="t">
      <tr>
        <th>Booking</th>
        <th>Parcels</th>
        <th>Type</th>
        <th>Date & Time</th>
        <th>Total</th>
        <th>Status</th>
      </tr>
      @foreach($bookings as $booking)
      @php
        $badge = match($booking->status) {
          'pending'    => ['b-r','Pending'],
          'ready'      => ['b-g','Ready to Pickup'],
          'on_the_way' => ['b-p','On The Way'],
          'done'       => ['b-gr','Done'],
          'cancelled'  => ['b-rd','Cancelled'],
          default      => ['b-gr',ucfirst($booking->status)],
        };
      @endphp
      <tr>
        <td>
          <strong style="font-family:'Playfair Display',serif;font-size:16px;">#{{ $booking->id }}</strong>
          <div style="font-size:10px;color:var(--fa);margin-top:2px;">{{ $booking->created_at->format('d M Y') }}</div>
        </td>
        <td>
          @foreach($booking->items as $item)
          <div style="font-size:12px;font-weight:600;color:var(--dk);">{{ $item->parcel->tracking_number ?? '—' }}</div>
          @endforeach
        </td>
        <td style="font-size:13px;">{{ ucfirst($booking->method) }}</td>
        <td>
          <div style="font-size:13px;font-weight:600;color:var(--dk);">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</div>
          <div style="font-size:11px;color:var(--fa);margin-top:2px;">{{ $booking->booking_time }}</div>
        </td>
        <td style="font-family:'Playfair Display',serif;font-size:16px;font-weight:600;color:var(--r);">
          RM {{ number_format($booking->total_amount, 2) }}
        </td>
        <td>
          <span class="badge {{ $badge[0] }}"><span class="bdot"></span>{{ $badge[1] }}</span>
          @if($booking->status === 'cancelled')
          <div style="font-size:10px;color:var(--fa);margin-top:4px;">Sila buat booking baru</div>
          @endif
        </td>
      </tr>
      @endforeach
    </table>
  </div>
  @endif

</div>
@endsection