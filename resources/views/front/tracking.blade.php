<x-layouts.front-layout title="Tracking - Amazon">
    <x-slot name="css">
        @vite(['resources/css/pages/tracking.css'])
    </x-slot>

    <div class="order-tracking">
        <a class="back-to-orders-link link-primary" href="{{ url('/orders') }}">
            View all orders
        </a>

        <div class="delivery-date">Arriving on {{ $deliveryDate->format('l, F j') }}</div>

        <div class="product-info">{{ $product->name }}</div>
        <div class="product-info">Quantity: {{ $quantity }}</div>
        <div class="product-info">Order Placed: {{ $orderDate->format('F j') }}</div>

        <img class="product-image" src="{{ asset($product->image) }}">

        <div class="progress-labels-container">
            <div class="progress-label {{ $status === 'preparing' ? 'current-status' : '' }}">Preparing</div>
            <div class="progress-label {{ $status === 'shipped' ? 'current-status' : '' }}">Shipped</div>
            <div class="progress-label {{ $status === 'delivered' ? 'current-status' : '' }}">Delivered</div>
        </div>

        <div class="progress-bar-container">
            <div class="progress-bar" style="width: {{ $progressPercent }}%;"></div>
        </div>
    </div>
</x-layouts.front-layout>
