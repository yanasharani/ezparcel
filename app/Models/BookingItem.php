<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'parcel_id'];

    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}