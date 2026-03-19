<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    protected $fillable = [
        'tracking_number','recipient_name','recipient_phone',
        'courier','arrived_date','arrived_time',
        'status','price','late_fee','late_since',
    ];

    protected $casts = [
        'arrived_date' => 'date',
        'late_since'   => 'date',
        'late_fee'     => 'decimal:2',
        'price'        => 'decimal:2',
    ];

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }

    // Auto check & update late status
    public function checkLate(): void
    {
        if (in_array($this->status, ['done','booked'])) return;

        $arrivedDate = \Carbon\Carbon::parse($this->arrived_date);
        $daysOld     = $arrivedDate->diffInDays(now());

        if ($daysOld >= 14 && $this->status !== 'late') {
            $lateSince = $arrivedDate->copy()->addDays(14);
            $daysLate  = max(1, $lateSince->diffInDays(now()));
            $this->update([
                'status'     => 'late',
                'late_since' => $lateSince->toDateString(),
                'late_fee'   => $daysLate * 1.00,
            ]);
        } elseif ($this->status === 'late') {
            // Update denda setiap kali check
            $daysLate = max(1, \Carbon\Carbon::parse($this->late_since)->diffInDays(now()));
            $this->update(['late_fee' => $daysLate * 1.00]);
        }
    }

    // Total yang user kene bayar = price + late_fee
    public function getTotalChargeAttribute(): float
    {
        return (float)$this->price + (float)$this->late_fee;
    }

    // Parcel yang belum diambil (registered atau late, bukan done/booked)
    public function scopeUncollected($query)
    {
        return $query->whereIn('status', ['registered', 'late']);
    }
}