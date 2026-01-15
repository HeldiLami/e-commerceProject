<x-layouts.front-layout title="Your Orders - Amazon">
    <x-slot name="css">
        @vite(['resources/css/pages/orders.css'])
    </x-slot>

    <div class="page-title">Your Orders</div>

    <div class="orders-grid">
        @forelse ($orders as $order)
            <div class="order-container">
                <div class="order-header">
                    <div class="order-header-left-section">
                        <div class="order-date">
                            <div class="order-header-label">Order Placed:</div>
                            <div>{{ $order->created_at->format('F d') }}</div>
                        </div>
                        <div class="order-total">
                            <div class="order-header-label">Total:</div>
                            <div>${{ number_format($order->total_cents / 100, 2) }}</div>
                        </div>
                        <div class="order-total">
                            <div class="order-header-label">Status:</div>
                            <div>{{ $order->status === 'paid' ? 'paid' : 'not paid' }}</div>
                        </div>
                    </div>

                    <div class="order-header-right-section">
                        <div class="order-header-label">Order ID:</div>
                        <div>{{ $order->id }}</div>
                    </div>
                </div>

                <div class="order-details-grid">
                    @foreach ($order->products as $product)
                        <div class="product-image-container">
                            <img src="{{ asset($product->image) }}">
                        </div>
                        <div class="product-details">
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-delivery-date">
                                Arriving on: {{ $order->created_at->copy()->addWeek()->format('F d') }}
                            </div>
                            <div class="product-quantity">Quantity: {{ $product->pivot->quantity }}</div>
                            <button class="buy-again-button button-primary">
                                <img class="buy-again-icon" src="{{ asset('images/icons/buy-again.png') }}">
                                <span class="buy-again-message">Buy it again</span>
                            </button>
                        </div>
                        <div class="product-actions">
                            <a href="{{ url('/tracking') }}">
                                <button class="track-package-button button-secondary">Track package</button>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="order-container">
                <div class="order-header">
                    <div class="order-header-left-section">
                        <div class="order-date">
                            <div class="order-header-label">No orders yet</div>
                            <div>Place an order to see it here.</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</x-layouts.front-layout>
