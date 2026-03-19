<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
public function index()
{
    $reviews    = Review::with('user')->latest()->get();
    $shopStatus = cache()->get('shop_status', ['is_open' => true, 'notice' => '']);
    return view('contact', compact('reviews', 'shopStatus'));
}
    public function store(Request $request)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'admin_reply' => $request->admin_reply,
        ]);

        return back()->with('success', 'Reply saved.');
    }

    public function adminIndex()
{
    $reviews = Review::with('user')->latest()->get();
    return view('admin.reviews', compact('reviews'));
}
}