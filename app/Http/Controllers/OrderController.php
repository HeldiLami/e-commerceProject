<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('products')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('front.orders', ['orders' => $orders]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'uuid', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $items = $data['items'];
        $productIds = collect($items)->pluck('product_id')->unique()->values();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $totalCents = 0;
        $orderItems = [];
        $now = now();

        foreach ($items as $item) {
            $product = $products->get($item['product_id']);
            if (!$product) {
                continue;
            }

            $quantity = $item['quantity'];
            $totalCents += $product->price_cents * $quantity;

            $orderItems[] = [
                'order_id' => null,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price_cents' => $product->price_cents,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (count($orderItems) === 0) {
            return response()->json(['message' => 'No valid items found.'], 422);
        }

        $order = DB::transaction(function () use ($request, $orderItems, $totalCents) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_cents' => $totalCents,
                'status' => 'pending',
            ]);

            foreach ($orderItems as &$orderItem) {
                $orderItem['order_id'] = $order->id;
            }

            DB::table('order_product')->insert($orderItems);

            return $order;
        });

        return response()->json([
            'order_id' => $order->id,
        ], 201);
    }
}
