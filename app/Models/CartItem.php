<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'parcel_id'];

    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}