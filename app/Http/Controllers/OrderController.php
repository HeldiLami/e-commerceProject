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

        return DB::transaction(function () use ($request, $items) {
            $productIds = collect($items)->pluck('product_id')->unique()->values();

            // Lock products to avoid stock conflicts.
            $products = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);

                if (!$product) {
                    return response()->json([
                        'message' => 'Product not found.'
                    ], 404);
                }

                $requestedQty = (int) $item['quantity'];

                if ($requestedQty > (int) $product->quantity) {
                    return response()->json([
                        'message' => "Nuk ka mjaftueshem sasi per {$product->name}. Ne stok: {$product->quantity}"
                    ], 422);
                }
            }

            $totalCents = 0;
            $orderItems = [];
            $now = now();

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);
                $qty = (int) $item['quantity'];

                $totalCents += $product->price_cents * $qty;

                $orderItems[] = [
                    'order_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price_cents' => $product->price_cents,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_cents' => $totalCents,
                'status' => 'pending',
            ]);

            foreach ($orderItems as &$orderItem) {
                $orderItem['order_id'] = $order->id;
            }

            DB::table('order_product')->insert($orderItems);

            return response()->json([
                'order_id' => $order->id,
            ], 201);
        });
    }


    public function destroy(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $order->delete();

        return redirect()->route('orders')->with('status', 'Order removed.');
    }
}
