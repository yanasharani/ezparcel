<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $totalParcels  = \App\Models\Parcel::count();
        $totalStudents = \App\Models\User::where('is_admin', false)->count();
        $avgRating     = \App\Models\Review::avg('rating') ?? 0;

        $reviews = \App\Models\Review::with('user')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('landing', compact('totalParcels', 'totalStudents', 'avgRating', 'reviews'));
    }
}