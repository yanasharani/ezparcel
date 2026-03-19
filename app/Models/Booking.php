<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id','recipient_name','delivery_address',
        'booking_date','booking_time','method',
        'total_amount','status',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function items()   { return $this->hasMany(BookingItem::class); }
    public function payment() { return $this->hasOne(Payment::class); }

    // Auto mark uncollected — 1 jam lepas booking time
    public function isUncollected(): bool
    {
        if ($this->status === 'done') return false;
        $bookingAt = \Carbon\Carbon::parse($this->booking_date . ' ' . $this->booking_time);
        return now()->gt($bookingAt->addHour());
    }
}