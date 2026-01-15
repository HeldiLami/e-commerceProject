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
        $products = Product::withAvg('ratings as rating_avg', 'stars')
                       ->withCount('ratings as rating_count')
                       ->get();
        return view('front.amazon', ['products' => $products]);
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
