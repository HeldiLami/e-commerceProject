<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::withAvg('ratings as rating_avg', 'stars')
            ->withCount('ratings as rating_count')
            ->get();
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
        $product->loadAvg('ratings as rating_avg', 'stars')
        ->loadCount('ratings as rating_count')
        ->load(['ratings.user']);
        return view('front.product', [
            'product' => $product,
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
