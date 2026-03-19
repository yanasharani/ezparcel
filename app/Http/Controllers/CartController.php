<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Parcel;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart;
        $items = $cart ? $cart->items()->with('parcel')->get() : collect();
        $total = $items->count() * 1;

        return view('cart.index', compact('items', 'total'));
    }

public function add(Request $request)
{
    $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

    $exists = CartItem::where('cart_id', $cart->id)
                      ->where('parcel_id', $request->parcel_id)
                      ->exists();

    if (!$exists) {
        CartItem::create([
            'cart_id'   => $cart->id,
            'parcel_id' => $request->parcel_id,
        ]);
    }

    return redirect()->route('home')->with('success', 'Add to cart succesful! 🛒');
}

    public function remove($id)
    {
        CartItem::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Remove from cart');
    }

    
}