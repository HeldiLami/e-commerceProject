<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (FRONT).
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
     * ADMIN: show add product form
     * GET /admin/products/create
     */
    public function adminCreate()
    {
        return view('admin.products-create');
    }

    /**
     * ADMIN: store product
     * POST /admin/products
     */
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

        // price (€) -> price_cents
        $priceCents = (int) round(((float)$data['price']) * 100);

        // keywords "a, b, c" -> ["a","b","c"]
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
        // nëse një ditë do CRUD normal (jo adminCreate), mund ta përdorësh këtu
        return redirect()->route('admin.products.create');
    }

    /**
     * Store a newly created resource in storage (unused for now).
     */
    public function store(Request $request)
    {
        // nëse do ta përdorësh si resource controller normal
        return redirect()->route('admin.products.create');
    }

    /**
     * Display the specified resource (FRONT).
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
     * Show the form for editing the specified resource (ADMIN optional).
     */
    public function edit(Product $product)
    {
        // opsionale: nëse do faqe edit në admin në të ardhmen
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
