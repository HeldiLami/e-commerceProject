<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::withAvg('ratings as rating_avg', 'stars')
            ->withCount('ratings as rating_count')
            ->get();

        return view('front.amazon', ['products' => $products]);
    }

    public function show(Product $product)
    {
        $product->loadAvg('ratings as rating_avg', 'stars')
                ->loadCount('ratings as rating_count')
                ->load(['ratings' => function ($query){
                    $query->latest()->with('user');
                }]);
        return view('front.product', [
            'product' => $product,
        ]);
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        //ben ndarjen e fjaleve sipas hapsirave ose presjeve
        $terms = collect(preg_split('/[\s,]+/', $query, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($term) => mb_strtolower($term))
            //ben secure qe arrayit te mos i ndryshoj renditja e indekseve
            ->values();

        $products = Product::withAvg('ratings as rating_avg', 'stars')
            ->withCount('ratings as rating_count')
            ->when($terms->isNotEmpty(), fn($query) => 
            $query->where(function ($sub) use ($terms) {
                foreach ($terms as $term) {
                    $sub->orWhere('name', 'like', "%$term%")
                        ->orWhereJsonContains('keywords', $term);
                }
            }))
            ->get();

        return view('front.amazon', [
            'products' => $products,
            'query' => $query,
        ]);
    }

    /**
     * ADMIN: show add product form
     * GET /admin/products/create
     */
    public function adminCreate()
    {
        return view('admin.products-create');
    }

    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|url|max:2048',
            'type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'keywords' => 'nullable|string|max:1000',
        ]);

        $priceCents = (int) round(((float)$data['price']) * 100);

        $keywordsArr = [];
        if (!empty($data['keywords'])) {
            $keywordsArr = collect(explode(',', $data['keywords']))
                ->map(fn ($k) => trim($k))
                ->filter()
                ->values()
                ->all();
        }

        Product::create([
            'id' => (string) Str::uuid(),
            'user_id' => $request->user()->id,
            'name' => $data['name'],
            'image' => $data['image'],
            'type' => $data['type'],
            'price_cents' => $priceCents,
            'quantity' => $data['quantity'],
            'keywords' => $keywordsArr,
        ]);

        return back()->with('success', 'Produkti u shtua me sukses!');
    }

    /**
     * Show the form for creating a new resource (unused for now).
     */
    public function create()
    {
        return redirect()->route('admin.products.create');
    }

    /**
     * Store a newly created resource in storage (unused for now).
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.products.create');
    }


    /**
     * Show the form for editing the specified resource (ADMIN optional).
     */
    public function edit(Product $product)
    {
        return view('admin.products-edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage (ADMIN optional).
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|url|max:2048',
            'type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'keywords' => 'nullable|string|max:1000',
        ]);

        $priceCents = (int) round(((float)$data['price']) * 100);

        $keywordsArr = [];
        if (!empty($data['keywords'])) {
            $keywordsArr = collect(explode(',', $data['keywords']))
                ->map(fn ($k) => trim($k))
                ->filter()
                ->values()
                ->all();
        }

        $product->update([
            'name' => $data['name'],
            'image' => $data['image'],
            'type' => $data['type'],
            'price_cents' => $priceCents,
            'quantity' => $data['quantity'],
            'keywords' => $keywordsArr,
        ]);

        return back()->with('success', 'Produkti u përditësua me sukses!');
    }

    /**
     * Remove the specified resource from storage (ADMIN optional).
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produkti u fshi me sukses!');
    }
}
