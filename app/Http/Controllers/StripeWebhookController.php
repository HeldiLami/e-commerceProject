<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $endpointSecret = config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Throwable $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $this->finalizeOrderAndDecreaseStock($orderId);
            }
        }

        return response('OK', 200);
    }

    private function finalizeOrderAndDecreaseStock($orderId)
    {
        DB::transaction(function () use ($orderId) {
            $order = Order::lockForUpdate()->find($orderId);
            if (!$order) return;

            if ($order->status === 'paid') return;

            $items = DB::table('order_product')
                ->where('order_id', $orderId)
                ->get();

            foreach ($items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if (!$product) continue;

                if ($product->quantity < $item->quantity) {
                    $order->status = 'failed';
                    $order->save();
                    return;
                }

                $product->quantity -= $item->quantity;
                $product->save();
            }

            $order->status = 'paid';
            $order->save();
        });
    }
}