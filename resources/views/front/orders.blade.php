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
                        @if ($order->status !== 'paid')
                            <form method="POST" action="{{ route('checkout.session.redirect') }}" style="margin-right: 8px;">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <button type="submit" class="track-package-button button-yellow">Pay now</button>
                            </form>
                        @endif
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
                            <button
                                type="button"
                                class="buy-again-button button-yellow js-buy"
                                data-product-id="{{ $product->id }}"
                                data-quantity="{{ $product->pivot->quantity }}"
                            >
                                <img class="buy-again-icon" src="{{ asset('images/icons/buy-again.png') }}">
                                <span class="buy-again-message">Buy it again</span>
                            </button>
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('tracking', ['order_id' => $order->id, 'product_id' => $product->id]) }}">
                                <button class="track-package-button button-silver">Track package</button>
                            </a>
                        </div>
                    @endforeach
                    <div class="order-remove-cell">
                        <form method="POST" action="{{ route('orders.destroy', $order) }}">
                            @csrf
                            @method('DELETE') {{--metode spoofing qe te bej metoda delete pasi html mbeshtet vtm get dhe post--}}
                            <button type="submit" class="order-remove-button" aria-label="Remove order">
                                <svg class="order-remove-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M9 3h6l1 2h4v2H4V5h4l1-2zM6 9h12l-1 11H7L6 9zm3 2h2v7H9v-7zm4 0h2v7h-2v-7z" fill="currentColor"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty {{--direktive blade qe kontrollon nese nje variabel bosh--}}
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

    <x-slot name="scripts">
        @vite(['resources/js/orders.js'])
    </x-slot>
</x-layouts.front-layout>
