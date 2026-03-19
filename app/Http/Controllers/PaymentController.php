<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        $qrFilename = \DB::table('settings')->where('key', 'qr_filename')->value('value');
        return view('payment.show', compact('booking', 'qrFilename'));
    }

    public function process(Request $request, Booking $booking)
    {
        $request->validate([
            'method' => 'required|in:cod,qr',
        ]);

        if ($request->method === 'qr') {
            $request->validate([
                'receipt' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            ],[
                'receipt.required' => 'Sila upload resit pembayaran QR sebelum confirm.',
                'receipt.image'    => 'Resit mesti dalam format imej.',
                'receipt.mimes'    => 'Resit mesti JPG atau PNG.',
                'receipt.max'      => 'Saiz resit tidak boleh melebihi 5MB.',
            ]);
        }

        $receiptPath = null;
        if ($request->method === 'qr' && $request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        Payment::create([
            'booking_id'   => $booking->id,
            'method'       => $request->method,
            'receipt_path' => $receiptPath,
            'status'       => 'pending',
        ]);

        try {
            $booking->load(['user', 'items.parcel', 'payment']);
            if ($booking->user && $booking->user->email) {
                Mail::to($booking->user->email)->send(new BookingConfirmation($booking));
            }
        } catch (\Exception $e) {
            // Email gagal — tak stop flow
        }

        return redirect()->route('booking.index')->with('success', 'Booking confirmed! Check your email for receipt.');
    }
}