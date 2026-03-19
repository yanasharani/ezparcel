<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Senarai Parcel Belum Diambil — EZParcel</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300;0,400;1,400&family=Outfit:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#FDFAF6;--surface:#F8F2EA;--linen:#EDE5D8;--copper:#B07848;--ink:#2C1A0E;--t2:#5A3820;--t3:#987050;--t4:#BCA080;}
body{font-family:'Outfit',sans-serif;background:var(--bg);color:var(--ink);-webkit-font-smoothing:antialiased;padding:40px 20px;}
.container{max-width:700px;margin:0 auto;}
.eyebrow{font-size:10px;letter-spacing:3px;text-transform:uppercase;color:var(--copper);font-weight:500;margin-bottom:10px;display:flex;align-items:center;gap:10px;}
.eyebrow::before{content:'';width:20px;height:1px;background:var(--copper);}
h1{font-family:'Cormorant',serif;font-size:36px;font-weight:300;margin-bottom:6px;}
h1 em{font-style:italic;color:var(--copper);}
.sub{font-size:13px;color:var(--t3);margin-bottom:32px;}
.card{background:#fff;border:1px solid var(--linen);border-radius:16px;overflow:hidden;margin-bottom:12px;}
.row{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--linen);}
.row:last-child{border-bottom:none;}
.tag-late{display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:100px;background:rgba(180,80,60,.1);color:#8A3020;font-size:11px;font-weight:500;}
.tag-reg{display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:100px;background:rgba(196,160,58,.12);color:#8A6A10;font-size:11px;font-weight:500;}
.updated{font-size:11px;color:var(--t4);text-align:center;margin-top:24px;}
</style>
</head>
<body>
<div class="container">
  <div class="eyebrow">EZParcel · UPSI</div>
  <h1>Parcel <em>Belum Diambil</em></h1>
  <p class="sub">Senarai parcel yang masih belum diambil di Kiosk Batu KZ. Sila ambil secepat mungkin.</p>

  @forelse($parcels as $parcel)
  <div class="card">
    <div class="row">
      <div>
        <div style="font-family:'Cormorant',serif;font-size:18px;color:var(--ink);">
          {{ $parcel->tracking_number }}
        </div>
        <div style="font-size:12px;color:var(--t3);margin-top:3px;">
          {{ $parcel->courier }} · Tiba {{ \Carbon\Carbon::parse($parcel->arrived_date)->format('d M Y') }}
        </div>
      </div>
      @if($parcel->status === 'late')
        <span class="tag-late">⚠ Lewat — Denda RM {{ number_format($parcel->late_fee, 2) }}</span>
      @else
        <span class="tag-reg">🟡 Belum Diambil</span>
      @endif
    </div>
    <div class="row" style="background:var(--surface);">
      <span style="font-size:12px;color:var(--t3);">Penerima</span>
      <span style="font-size:13px;color:var(--t2);font-weight:400;">{{ $parcel->recipient_name }}</span>
    </div>
    @if($parcel->status === 'late')
    <div class="row" style="background:rgba(180,80,60,.03);">
      <span style="font-size:12px;color:#8A3020;">Denda bermula</span>
      <span style="font-size:13px;color:#8A3020;font-weight:500;">
        {{ \Carbon\Carbon::parse($parcel->late_since)->format('d M Y') }}
        ({{ \Carbon\Carbon::parse($parcel->late_since)->diffInDays(now()) }} hari)
      </span>
    </div>
    @endif
  </div>
  @empty
  <div style="text-align:center;padding:60px 20px;color:var(--t3);">
    <div style="font-size:40px;margin-bottom:12px;">✅</div>
    <div style="font-size:16px;">Tiada parcel yang belum diambil.</div>
  </div>
  @endforelse

  <p class="updated">Dikemaskini: {{ now()->format('d M Y, g:i A') }}</p>
</div>
</body>
</html>