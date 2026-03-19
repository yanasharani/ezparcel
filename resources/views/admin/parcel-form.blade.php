@extends('admin.layout')
@section('title', isset($parcel) ? 'Edit Parcel' : 'Add Parcel')

@section('content')

<div class="pg-hd">
  <div>
    <div class="pg-tag">Admin · EZParcel</div>
    <div class="pg-title">{{ isset($parcel) ? 'Edit' : 'Add' }} <em>Parcel</em></div>
  </div>
  <a href="{{ route('admin.parcels') }}" class="btn-out btn-sm">← Back</a>
</div>

<div style="max-width:640px;">
  <div class="tbl-wrap">
    <div class="tbl-hd">
      <div class="tbl-hd-title">{{ isset($parcel) ? 'Update Parcel Details' : 'Register New Parcel' }}</div>
    </div>
    <div style="padding:28px 32px;">
      <form method="POST" action="{{ isset($parcel) ? route('admin.parcels.update',$parcel->id) : route('admin.parcels.store') }}">
        @csrf
        @if(isset($parcel)) @method('PUT') @endif

        @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

          <div class="form-group" style="grid-column:1/-1">
            <label class="lbl">Tracking Number</label>
            <input class="inp" type="text" name="tracking_number"
              placeholder="e.g. MY123456789"
              value="{{ old('tracking_number',$parcel->tracking_number??'') }}" required>
            @error('tracking_number')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="lbl">Recipient Name</label>
            <input class="inp" type="text" name="recipient_name"
              placeholder="Full name"
              value="{{ old('recipient_name',$parcel->recipient_name??'') }}" required>
            @error('recipient_name')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="lbl">Recipient Phone</label>
            <input class="inp" type="text" name="recipient_phone"
              placeholder="01X-XXXXXXX"
              value="{{ old('recipient_phone',$parcel->recipient_phone??'') }}"
              oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
            @error('recipient_phone')<div style="font-size:11px;color:var(--r2);margin-top:4px">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="lbl">Courier</label>
            <select class="inp" name="courier">
              @foreach(['J&T','Pos Laju','DHL','Ninja Van','Shopee Express','Lazada Logistics','GDex','City-Link'] as $c)
              <option value="{{ $c }}" {{ old('courier',$parcel->courier??'')==$c?'selected':'' }}>{{ $c }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label class="lbl">Status</label>
            <select class="inp" name="status">
              @foreach(['registered','booked','done'] as $s)
              <option value="{{ $s }}" {{ old('status',$parcel->status??'registered')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label class="lbl">Arrived Date</label>
            <input class="inp" type="date" name="arrived_date"
              value="{{ old('arrived_date',isset($parcel)?\Carbon\Carbon::parse($parcel->arrived_date)->format('Y-m-d'):'') }}" required>
          </div>

          <div class="form-group">
            <label class="lbl">Arrived Time</label>
            <input class="inp" type="time" name="arrived_time"
              value="{{ old('arrived_time',$parcel->arrived_time??'') }}" required>
          </div>

        </div>

        <div class="divider"></div>
        <div style="display:flex;gap:10px;">
          <button type="submit" class="btn-dk">
            {{ isset($parcel) ? 'Update Parcel' : '+ Add Parcel' }}
          </button>
          <a href="{{ route('admin.parcels') }}" class="btn-out">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection