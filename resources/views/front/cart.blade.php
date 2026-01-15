<x-layouts.front-layout title="Your Cart - Amazon">
    <x-slot name="css">
        @vite(['resources/css/pages/cart.css'])
    </x-slot>

    <div class="cart-page">
        <div class="cart-title">Your Cart</div>

        <div class="cart-grid">
            <div class="cart-items js-cart-items"></div>

            <div class="cart-summary js-cart-summary">
                <button class="place-order-button button-primary js-place-order" type="button" disabled>
                    Place order
                </button>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            window.cartProductsData = @json($products);
        </script>
        @vite(['resources/js/cart.js'])
    </x-slot>
</x-layouts.front-layout>
