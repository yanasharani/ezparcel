<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()
            ->bookings()
            ->with(['items.parcel','payment'])
            ->latest()
            ->get();
        return view('booking.index', compact('bookings'));
    }

    public function checkout()
    {
        $cart  = auth()->user()->cart;
        $items = $cart ? $cart->items()->with('parcel')->get() : collect();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $slotsRaw = DB::table('settings')->where('key','booking_slots')->value('value');
        $slots    = $slotsRaw ? json_decode($slotsRaw, true) : [];

        return view('booking.checkout', compact('items','slots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_name'   => 'required|string',
            'booking_date'     => 'required|date|after_or_equal:today',
            'booking_time'     => 'required',
            'method'           => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:method,delivery',
        ],[
            'recipient_name.required'      => 'Nama penerima wajib diisi.',
            'booking_date.required'        => 'Tarikh tempahan wajib diisi.',
            'booking_date.after_or_equal'  => 'Tarikh mestilah hari ini atau selepasnya.',
            'booking_time.required'        => 'Masa tempahan wajib diisi.',
            'delivery_address.required_if' => 'Alamat penghantaran wajib diisi untuk delivery.',
        ]);

        $cart  = auth()->user()->cart;
        $items = $cart->items()->with('parcel')->get();

        $deliveryFee = $request->method === 'delivery' ? 3 : 0;
        $total       = $items->sum(fn($i) => (float)$i->parcel->price + (float)$i->parcel->late_fee) + $deliveryFee;

        $initialStatus = $request->method === 'pickup' ? 'ready' : 'pending';

        // Convert booking_time — handle any format like "10:00 PM", "22:00", "10.p.m"
        $bookingTime = $request->booking_time;
        try {
            $bookingTime = \Carbon\Carbon::parse($request->booking_time)->format('H:i:s');
        } catch (\Exception $e) {
            $bookingTime = '00:00:00';
        }

        $booking = Booking::create([
            'user_id'          => auth()->id(),
            'recipient_name'   => $request->recipient_name,
            'delivery_address' => $request->delivery_address,
            'booking_date'     => $request->booking_date,
            'booking_time'     => $bookingTime,
            'method'           => $request->method,
            'total_amount'     => $total,
            'status'           => $initialStatus,
        ]);

        foreach ($items as $item) {
            BookingItem::create([
                'booking_id' => $booking->id,
                'parcel_id'  => $item->parcel_id,
            ]);
            $item->parcel->update(['status' => 'booked']);
        }

        $cart->items()->delete();

        return redirect()->route('payment.show', $booking->id);
    }
}