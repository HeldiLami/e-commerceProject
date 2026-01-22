<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stars' => 'required|numeric|min:0.5|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
    
        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ],
            [
                'stars' => $request->stars,
                'comment' => $request->comment,
            ]
        );
    
        return back()->withFragment('reviews');
    }

    public function destroy(Rating $rating)
    {
        if ($rating->user_id !== Auth::id()) {
            abort(403);
        }

        $rating->delete();

        return back()->with('success', 'Rating removed.');
    }
}