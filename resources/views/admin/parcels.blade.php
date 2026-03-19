@extends('admin.layout')
@section('title', 'Manage Parcels')

@section('content')

<div class="pg-hd">
  <div>
    <div class="pg-tag">Admin · EZParcel</div>
    <div class="pg-title">Manage <em>Parcels</em></div>
  </div>
  <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
    <form method="POST" action="{{ route('admin.logo.update') }}" enctype="multipart/form-data">
      @csrf
      <label style="font-size:10px;font-weight:600;color:var(--fa);cursor:pointer;background:var(--w);border:0.5px solid var(--bo);padding:9px 16px;display:flex;align-items:center;gap:6px;transition:all .2s;" onmouseover="this.style.borderColor='var(--r)';this.style.color='var(--r)'" onmouseout="this.style.borderColor='var(--bo)';this.style.color='var(--fa)'">
        Upload Logo
        <input type="file" name="logo" accept="image/*" style="display:none" onchange="this.closest('form').submit()">
      </label>
    </form>
    <a href="{{ route('admin.parcel-form') }}" class="btn-dk btn-sm">+ Add Parcel</a>
  </div>
</div>

<form method="GET" style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
  <input class="inp" style="max-width:240px;font-size:12px;" type="text" name="search"
    placeholder="Search tracking / name..." value="{{ request('search') }}">
  <select class="inp" name="status" style="max-width:160px;font-size:12px;" onchange="this.form.submit()">
    <option value="">All Status</option>
    <option value="registered" {{ request('status')==='registered'?'selected':'' }}>Registered</option>
    <option value="booked"     {{ request('status')==='booked'?'selected':'' }}>Booked</option>
    <option value="done"       {{ request('status')==='done'?'selected':'' }}>Done</option>
  </select>
  <button type="submit" class="btn-r btn-sm">Search</button>
  @if(request('search')||request('status'))
  <a href="{{ route('admin.parcels') }}" class="btn-out btn-sm">Clear</a>
  @endif
</form>

<div class="tbl-wrap" style="margin-bottom:24px;">
  <div class="tbl-hd">
    <div class="tbl-hd-title">Active Parcels</div>
    <div style="font-size:11px;color:var(--fa);">{{ $parcels->count() }} parcel(s)</div>
  </div>
  <table class="t">
    <tr>
      <th>#</th>
      <th>Tracking Number</th>
      <th>Recipient</th>
      <th>Courier</th>
      <th>Arrived</th>
      <th>Update Status</th>
      <th>Current Status</th>
      <th>Actions</th>
    </tr>
    @forelse($parcels as $i => $parcel)
    <tr>
      <td style="color:var(--fa);font-size:11px;">{{ $i+1 }}</td>
      <td><strong style="font-family:'Playfair Display',serif;font-size:15px;">{{ $parcel->tracking_number }}</strong></td>
      <td>
        <div style="font-weight:600;">{{ $parcel->recipient_name }}</div>
        <div style="font-size:10px;color:var(--fa);">{{ $parcel->recipient_phone }}</div>
      </td>
      <td>{{ $parcel->courier }}</td>
      <td>
        <div>{{ \Carbon\Carbon::parse($parcel->arrived_date)->format('d M Y') }}</div>
        <div style="font-size:10px;color:var(--fa);">{{ $parcel->arrived_time }}</div>
      </td>
      <td>
        <form method="POST" action="{{ route('admin.parcels.quick-status', $parcel->id) }}" style="display:flex;gap:6px;">
          @csrf @method('PATCH')
          <select name="status" class="inp" style="font-size:11px;padding:6px 10px;min-width:120px;">
            <option value="registered" {{ $parcel->status==='registered'?'selected':'' }}>Registered</option>
            <option value="booked"     {{ $parcel->status==='booked'?'selected':'' }}>Booked</option>
            <option value="done"       {{ $parcel->status==='done'?'selected':'' }}>Done</option>
          </select>
          <button type="submit" class="btn-r btn-sm">Save</button>
        </form>
      </td>
      <td>
        @php
          $tag = match($parcel->status) {
            'registered' => ['b-r',  'Registered'],
            'booked'     => ['b-b',  'Booked'],
            'done'       => ['b-g',  'Done'],
            'late'       => ['b-rd', 'Late'],
            default      => ['b-gr', ucfirst($parcel->status)],
          };
        @endphp
        <span class="badge {{ $tag[0] }}"><span class="bdot"></span>{{ $tag[1] }}</span>
      </td>
      <td>
        <div style="display:flex;gap:6px;">
          <a href="{{ route('admin.parcels.edit',$parcel->id) }}" class="btn-out btn-sm">Edit</a>
          <form method="POST" action="{{ route('admin.parcels.delete',$parcel->id) }}" onsubmit="return confirm('Delete this parcel?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-out btn-sm" style="border-color:rgba(185,28,28,0.2);color:#991b1b;">Delete</button>
          </form>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" style="text-align:center;color:var(--fa);padding:40px;">No parcels found.</td></tr>
    @endforelse
  </table>
</div>

<div class="tbl-wrap">
  <div class="tbl-hd">
    <div>
      <div class="tbl-hd-title">Uncollected Parcels <span style="color:#991b1b;font-size:14px;">(Fine RM1/day)</span></div>
      <div style="font-size:11px;color:var(--fa);margin-top:2px;">Parcels uncollected for more than 2 weeks</div>
    </div>
    <form method="POST" action="{{ route('admin.uncollected-link') }}">
      @csrf
      <button type="submit" class="btn-r btn-sm">Generate Link</button>
    </form>
  </div>

  @if(session('uncollected_link'))
  <div style="padding:12px 20px;background:rgba(194,112,128,0.06);border-bottom:0.5px solid var(--bo);display:flex;gap:10px;align-items:center;">
    <input id="unc-link" class="inp" style="flex:1;font-size:12px;padding:8px 12px;" value="{{ session('uncollected_link') }}" readonly>
    <button onclick="copyUncLink()" class="btn-dk btn-sm">Copy</button>
  </div>
  @endif

  <table class="t">
    <tr>
      <th>Tracking Number</th>
      <th>Recipient</th>
      <th>Arrived</th>
      <th>Late Since</th>
      <th>Days Late</th>
      <th>Fine</th>
      <th>Update Status</th>
    </tr>
    @forelse($lateParcels as $parcel)
    <tr style="background:rgba(185,28,28,0.02);">
      <td><strong style="font-family:'Playfair Display',serif;">{{ $parcel->tracking_number }}</strong></td>
      <td>
        <div style="font-weight:600;">{{ $parcel->recipient_name }}</div>
        <div style="font-size:10px;color:var(--fa);">{{ $parcel->recipient_phone }}</div>
      </td>
      <td>{{ \Carbon\Carbon::parse($parcel->arrived_date)->format('d M Y') }}</td>
      <td style="color:#991b1b;">{{ \Carbon\Carbon::parse($parcel->late_since)->format('d M Y') }}</td>
      <td style="font-weight:700;color:#991b1b;">{{ \Carbon\Carbon::parse($parcel->late_since)->diffInDays(now()) }} days</td>
      <td style="font-family:'Playfair Display',serif;font-size:16px;font-weight:600;color:#991b1b;">RM {{ number_format($parcel->late_fee,2) }}</td>
      <td>
        <form method="POST" action="{{ route('admin.parcels.quick-status', $parcel->id) }}" style="display:flex;gap:6px;">
          @csrf @method('PATCH')
          <select name="status" class="inp" style="font-size:11px;padding:6px 10px;">
            <option value="late" selected>Late</option>
            <option value="done">Done</option>
          </select>
          <button type="submit" class="btn-r btn-sm">Save</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;color:var(--fa);padding:32px;">No uncollected parcels. ✓</td></tr>
    @endforelse
  </table>
</div>

@endsection

@push('scripts')
<script>
function copyUncLink() {
  const input = document.getElementById('unc-link');
  input.select();
  document.execCommand('copy');
  alert('Link copied!');
}
</script>
@endpush