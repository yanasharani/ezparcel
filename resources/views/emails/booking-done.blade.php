@component('mail::message')

@if($booking->method === 'pickup')
# 🎉 Parcel Collected!

Hi **{{ $booking->user->name }}**,

Your parcel has been **successfully collected** at Kiosk Batu KZ. Thank you for using Deqmie EzParcel!

@else
# 📦 Parcel Delivered!

Hi **{{ $booking->user->name }}**,

Your parcel has been **successfully delivered**. We hope you received it in great condition!

@endif

---

@component('mail::panel')
**Booking ID:** #{{ $booking->id }}

**Recipient:** {{ $booking->recipient_name }}

**Date:** {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}

**Method:** {{ ucfirst($booking->method) }}

@if($booking->delivery_address)
**Delivery Address:** {{ $booking->delivery_address }}
@endif

**Total Paid:** RM {{ number_format($booking->total_amount, 2) }}
@endcomponent

---

**Parcels in this booking:**

@foreach($booking->items as $item)
- Tracking No: `{{ $item->parcel->tracking_number ?? '—' }}`
@endforeach

---

@if($booking->method === 'pickup')
We hope to see you again soon! If you have any questions, contact us via WhatsApp:
@else
If you have any issues with your delivery, please contact us via WhatsApp:
@endif

**+60 13-604 6064**

@component('mail::button', ['url' => url('/my-booking'), 'color' => 'green'])
View My Booking
@endcomponent

Thank you for using **Deqmie EzParcel**! 📦

© {{ date('Y') }} Deqmie. All rights reserved.
@endcomponent