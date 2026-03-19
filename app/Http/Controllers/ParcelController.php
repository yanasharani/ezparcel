<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use Illuminate\Http\Request;

class ParcelController extends Controller
{
    public function index()
    {
        return view('parcels.search');
    }

    public function search(Request $request)
    {
        $parcels = collect();

        if ($request->has('tracking_number') && $request->tracking_number != '') {
            $parcels = Parcel::where('tracking_number', 'LIKE', '%' . $request->tracking_number . '%')
                            ->get();
        }

        return view('parcels.search', compact('parcels'));
    }
}