<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stars' => 'required|numeric|min:0.5|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
    
        // HasUuids will automatically handle the 'id' field
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
        // Llogarisim te dhenat direkt nga tabela ratings
        $stats = Rating::where('product_id', $productId)
            ->selectRaw('AVG(stars) as average, COUNT(*) as count')
            ->first();

        return [
            'average' => round($stats->average * 2) / 2, // Rrumbullakim ne 0.5 me te afert
            'count'   => (int) $stats->count
        ];
    }
}