<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('front.amazon', ['products' => $products]);
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $terms = collect(preg_split('/[\s,]+/', $query, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($term) => mb_strtolower($term))
            ->values();

        $products = Product::withAvg('ratings as rating_avg', 'stars')
            ->withCount('ratings as rating_count')
            ->when($terms->isNotEmpty(), function ($builder) use ($terms) {
                $builder->where(function ($subquery) use ($terms) {
                    foreach ($terms as $term) {
                        $like = '%' . $term . '%';
                        $subquery->orWhere('name', 'like', $like)
                            ->orWhereJsonContains('keywords', $term)
                            ->orWhere('keywords', 'like', $like);
                    }
                });
            })
            ->get();

        return view('front.amazon', [
            'products' => $products,
            'query' => $query,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
   public function show(Product $product)
    {
        $product->load(['ratings.user']);
        
        $stats = $product->ratings()
        ->selectRaw('AVG(stars) as average, COUNT(*) as count')
        ->first();

        $average = $stats->average ?? 0;
        $count = $stats->count ?? 0;

        $ratingStars = round($average * 2) / 2;


        return view('front.product', [
            'product' => $product,
            'ratingStars' => $ratingStars,
            'ratingCount' => $count
        ]);    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
