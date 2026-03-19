@extends('admin.layout')
@section('title', 'Manage Bookings')

@section('content')

<div class="pg-hd">
  <div>
    <div class="pg-tag">Admin · EZParcel</div>
    <div class="pg-title">Manage <em>Bookings</em></div>
  </div>
  <div style="display:flex;gap:6px;flex-wrap:wrap;">
    @foreach([''=>'All','pending'=>'Pending','ready'=>'Ready','on_the_way'=>'On The Way','done'=>'Done','cancelled'=>'Cancelled'] as $val=>$lbl)
    <a href="{{ route('admin.bookings', $val ? ['status'=>$val] : []) }}"
      style="font-size:10px;font-weight:600;padding:7px 14px;text-decoration:none;transition:all .2s;border:0.5px solid {{ request('status')===$val && ($val || !request('status')) ? 'var(--dk)' : 'var(--bo)' }};background:{{ request('status')===$val && ($val || !request('status')) ? 'var(--dk)' : 'transparent' }};color:{{ request('status')===$val && ($val || !request('status')) ? '#FBF8F5' : 'var(--mu)' }};">
      {{ $lbl }}
    </a>
    @endforeach
  </div>
</div>

<div class="tbl-wrap">
  <div class="tbl-hd">
    <div class="tbl-hd-title">All Bookings</div>
    <div style="font-size:11px;color:var(--fa);">{{ $bookings->count() }} booking(s)</div>
  </div>
  <table class="t">
    <tr>
      <th>ID</th>
      <th>Student</th>
      <th>Method</th>
      <th>Date & Time</th>
      <th>Total</th>
      <th>Payment</th>
      <th>Update</th>
      <th>Status</th>
    </tr>
    @forelse($bookings as $booking)
    <tr>
      <td><strong style="font-family:'Playfair Display',serif;font-size:16px;">#{{ $booking->id }}</strong></td>
      <td>
        <div style="font-weight:600;">{{ $booking->user->name ?? 'N/A' }}</div>
        <div style="font-size:10px;color:var(--fa);">{{ $booking->user->matric_number ?? '' }}</div>
        <div style="font-size:10px;color:var(--fa);">{{ $booking->user->phone ?? '' }}</div>
      </td>
      <td>
        <span class="badge {{ $booking->method==='delivery' ? 'b-p' : 'b-g' }}">
          {{ $booking->method==='delivery' ? '🚗 Delivery' : '🏪 Pickup' }}
        </span>
        @if($booking->delivery_address)
        <div style="font-size:10px;color:var(--fa);margin-top:3px;max-width:120px;line-height:1.4;">{{ $booking->delivery_address }}</div>
        @endif
      </td>
      <td>
        <div style="font-weight:600;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</div>
        <div style="font-size:10px;color:var(--fa);">{{ $booking->booking_time }}</div>
      </td>
      <td style="font-family:'Playfair Display',serif;font-size:16px;font-weight:600;color:var(--r);">
        RM {{ number_format($booking->total_amount,2) }}
      </td>
      <td>
        @if($booking->payment)
          <span class="badge {{ $booking->payment->status==='paid' ? 'b-g' : 'b-b' }}">
            {{ strtoupper($booking->payment->method) }}
          </span>
          <div style="font-size:10px;color:var(--fa);margin-top:2px;">
            {{ $booking->payment->status==='paid' ? '✓ Paid' : 'Pending' }}
          </div>
        @else
          <span class="badge b-b">Unpaid</span>
        @endif
      </td>
      <td>
        @if($booking->status !== 'cancelled')
        <form method="POST" action="{{ route('admin.bookings.status',$booking->id) }}">
          @csrf @method('PUT')
          <div style="display:flex;gap:6px;">
            <select name="status" class="inp" style="font-size:11px;padding:6px 10px;min-width:150px;">
              <option value="pending"    {{ $booking->status==='pending'    ?'selected':'' }}>🕐 Pending</option>
              <option value="ready"      {{ $booking->status==='ready'      ?'selected':'' }}>✅ Ready to Pickup</option>
              <option value="on_the_way" {{ $booking->status==='on_the_way' ?'selected':'' }}>🚗 On The Way</option>
              <option value="done"       {{ $booking->status==='done'       ?'selected':'' }}>✓ Done</option>
              <option value="cancelled"  {{ $booking->status==='cancelled'  ?'selected':'' }}>✕ Cancelled</option>
            </select>
            <button type="submit" class="btn-r btn-sm">Save</button>
          </div>
        </form>
        @else
          <span style="font-size:11px;color:var(--fa);font-style:italic;">—</span>
        @endif
      </td>
      <td>
        @php
          $b = match($booking->status) {
            'pending'    => ['b-r','Pending'],
            'ready'      => ['b-g','Ready to Pickup'],
            'on_the_way' => ['b-p','On The Way'],
            'done'       => ['b-gr','Done'],
            'cancelled'  => ['b-rd','Cancelled'],
            default      => ['b-gr',ucfirst($booking->status)],
          };
        @endphp
        <span class="badge {{ $b[0] }}"><span class="bdot"></span>{{ $b[1] }}</span>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="8" style="text-align:center;color:var(--fa);padding:48px;">No bookings found.</td>
    </tr>
    @endforelse
  </table>
</div>

@endsection