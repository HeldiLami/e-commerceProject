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
    
        return back()->with('success', 'Review saved successfully!');
    }

    public function destroy(Rating $rating)
    {
        if ($rating->user_id !== Auth::id()) {
            abort(403);
        }

        $rating->delete();

        return back()->with('success', 'Rating removed.');
    }

    public function getStats($productId)
    {
        $stats = Rating::where('product_id', $productId)
            ->selectRaw('AVG(stars) as average, COUNT(*) as count')
            ->first();

        return [
            'average' => round($stats->average * 2) / 2,
            'count'   => (int) $stats->count
        ];
    }
}