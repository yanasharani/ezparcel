<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') — EZParcel Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@stack('styles')
</head>
<body>
<div class="admin-layout">

  <div class="sidebar">
    <div class="sb-head">
      @php $logoFile = \DB::table('settings')->where('key','logo_filename')->value('value'); @endphp
      <a class="sb-logo" href="{{ route('admin.dashboard') }}">
        @if($logoFile && file_exists(public_path('images/'.$logoFile)))
          <img src="{{ asset('images/'.$logoFile) }}" alt="EZParcel">
        @else
          EZ<span>Parcel</span>
        @endif
      </a>
      <div class="sb-user">
        <div class="sb-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div>
          <div class="sb-nm">{{ auth()->user()->name }}</div>
          <div class="sb-role">Administrator</div>
        </div>
      </div>
    </div>

    <div class="sb-nav">
      <div class="sb-lbl">Main</div>
      <a class="sb-link {{ request()->routeIs('admin.dashboard') ? 'on' : '' }}" href="{{ route('admin.dashboard') }}">
        <span class="sb-ico">📊</span>Dashboard
      </a>
      <div class="sb-lbl">Manage</div>
      <a class="sb-link {{ request()->routeIs('admin.parcels*') ? 'on' : '' }}" href="{{ route('admin.parcels') }}">
        <span class="sb-ico">📦</span>Parcels
      </a>
      <a class="sb-link {{ request()->routeIs('admin.bookings*') ? 'on' : '' }}" href="{{ route('admin.bookings') }}">
        <span class="sb-ico">📋</span>Bookings
        @php $pendingCount = \App\Models\Booking::where('status','pending')->count(); @endphp
        @if($pendingCount > 0)
        <span class="sb-badge">{{ $pendingCount }}</span>
        @endif
      </a>
      <a class="sb-link {{ request()->routeIs('admin.reviews*') ? 'on' : '' }}" href="{{ route('admin.reviews.index') }}">
        <span class="sb-ico">⭐</span>Reviews
      </a>
      <div class="sb-lbl">Settings</div>
      <a class="sb-link {{ request()->routeIs('admin.shop*') ? 'on' : '' }}" href="{{ route('admin.shop-status') }}">
        <span class="sb-ico">🏪</span>Shop Status
      </a>
      <a class="sb-link {{ request()->routeIs('admin.qr*') ? 'on' : '' }}" href="{{ route('admin.qr-code') }}">
        <span class="sb-ico">📱</span>QR Code
      </a>
      <a class="sb-link {{ request()->routeIs('admin.booking-slots*') ? 'on' : '' }}" href="{{ route('admin.booking-slots') }}">
        <span class="sb-ico">🕐</span>Booking Slots
      </a>
      <div style="margin-top:auto;padding:12px 0;">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="sb-link" style="width:100%;background:none;border:none;font-family:inherit;cursor:pointer;color:rgba(251,248,245,0.25);">
            <span class="sb-ico">🚪</span>Sign Out
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="admin-main">
    <div class="page-body">
      @if(session('success'))
      <div class="alert-success">✓ {{ session('success') }}</div>
      @endif
      @if(session('error'))
      <div class="alert-error">{{ session('error') }}</div>
      @endif
      @yield('content')
    </div>
  </div>

</div>
@stack('scripts')
</body>
</html>