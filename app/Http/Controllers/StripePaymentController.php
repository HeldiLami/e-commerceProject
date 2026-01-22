<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\StripeClient;

//Merr order_id dhe kontrollon nese eshte pending porosia. Krijon Checkout Session dhe kthen JSON me checkoutUrl.

class StripePaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ]);

        $order = Order::where('id', $data['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order is not payable.'], 422);
        }

        $session = $this->buildCheckoutSession($order);

        return response()->json([
            'checkoutUrl' => $session->url,
        ]);
    }

    public function redirectToCheckout(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ]);

        $order = Order::where('id', $data['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return redirect('/orders')->withErrors(['order' => 'Order is not payable.']);
        }

        $session = $this->buildCheckoutSession($order);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'session_id' => ['required', 'string'],
        ]);

        $order = Order::where('id', $data['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $stripe = new StripeClient(config('services.stripe.secret'));
        $session = $stripe->checkout->sessions->retrieve($data['session_id'], [
            'expand' => ['payment_intent'],
        ]);

        if ($session->payment_status !== 'paid') {
            return response()->json(['message' => 'Payment not completed.'], 422);
        }

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'amount_cents' => $order->total_cents,
                'provider' => 'stripe',
                'status' => 'success',
                'transaction_ref' => $session->payment_intent->id ?? $session->id,
                'paid_at' => now(),
            ]
        );

        $order->update(['status' => 'paid']);

        return redirect('/orders');
    }

    public function cancel()
    {
        return redirect('/cart');
    }

    private function buildCheckoutSession(Order $order)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $successUrl = url('/checkout/success') . '?order_id=' . $order->id . '&session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = url('/checkout/cancel') . '?order_id=' . $order->id;

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $order->total_cents,
                        'product_data' => [
                            'name' => "Order #{$order->id}",
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'order_id' => (string) $order->id,
            ],
        ]);

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'amount_cents' => $order->total_cents,
                'provider' => 'stripe',
                'status' => 'pending',
                'transaction_ref' => $session->id,
            ]
        );

        return $session;
    }
}
