@component('mail::message')

# ✅ Booking Confirmed!

Hi **{{ $booking->user->name }}**,

Your parcel booking has been confirmed. Here are your booking details:

---

@component('mail::panel')
**Booking ID:** #{{ $booking->id }}

**Recipient:** {{ $booking->recipient_name }}

**Date:** {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}

**Time:** {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}

**Method:** {{ ucfirst($booking->method) }}

@if($booking->delivery_address)
**Delivery Address:** {{ $booking->delivery_address }}
@endif

**Total Paid:** RM {{ number_format($booking->total_amount, 2) }}

**Payment Method:** {{ strtoupper($booking->payment->method ?? 'N/A') }}
@endcomponent

---

**Parcels in this booking:**

@foreach($booking->items as $item)
- Tracking No: `{{ $item->parcel->tracking_number ?? '—' }}`
@endforeach

---

You will receive another email once your parcel is **on the way** or **ready for collection**.

For enquiries, contact us via WhatsApp:
**+60 13-604 6064**

@component('mail::button', ['url' => url('/my-booking'), 'color' => 'blue'])
View My Booking
@endcomponent

Thank you for using **Deqmie EzParcel**! 📦

© {{ date('Y') }} Deqmie. All rights reserved.
@endcomponent