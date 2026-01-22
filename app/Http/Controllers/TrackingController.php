<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function show(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'product_id' => ['required', 'uuid'],
        ]);

        $order = Order::with('products')
            ->where('id', $validated['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $product = $order->products->firstWhere('id', $validated['product_id']);
        if (!$product) {
            abort(404);
        }

        $orderDate = $order->created_at->copy();
        $deliveryDate = $order->created_at->copy()->addWeek();
        $totalSeconds = max(1, $orderDate->diffInSeconds($deliveryDate));
        $elapsedSeconds = $orderDate->diffInSeconds(now());
        $progressPercent = (int) round(min(1, $elapsedSeconds / $totalSeconds) * 100);

        $status = 'preparing';
        if ($progressPercent >= 100) {
            $status = 'delivered';
        } elseif ($progressPercent >= 50) {
            $status = 'shipped';
        }

        return view('front.tracking', [
            'order' => $order,
            'product' => $product,
            'quantity' => $product->pivot->quantity,
            'orderDate' => $orderDate,
            'deliveryDate' => $deliveryDate,
            'progressPercent' => $progressPercent,
            'status' => $status,
        ]);
    }
}
