<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingDone extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        $subject = $this->booking->method === 'pickup'
            ? 'Your Parcel Has Been Collected — Deqmie EzParcel #' . $this->booking->id
            : 'Your Parcel Has Been Delivered — Deqmie EzParcel #' . $this->booking->id;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking-done',
            with: ['booking' => $this->booking],
        );
    }
}