<x-layouts.front-layout title="Amazon Project">
    <x-slot name="css">
        @vite(['resources/css/pages/amazon.css'])
    </x-slot>

    <div class="products-grid js-products-grid">
        @foreach($products as $product)
            <div class="product-container">
                <div class="product-image-container">
                <a href="{{ route('product.show', $product) }}">
                    <img class="product-image" src="{{ asset($product->image) }}">
                </a>
                </div>

               <div class="product-name">
                <a class="product-link" href="{{ route('product.show', $product) }}">
                    {{ $product->name }}
                </a>
               </div>
                <div class="product-rating-container">
                <img class="product-rating-stars" src="{{ $product->stars_image }}">
                    <div class="product-rating-count link-primary">
                        {{ $product->rating_count }}
                    </div>
                </div>

                <div class="product-price">
                    ${{ number_format($product->price_cents / 100, 2) }}
                </div>

                <div class="product-quantity-container">
                    <select class="js-quantity-selector-{{ $product->id }}">
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="product-spacer"></div>
            @auth    
                <button class="add-to-cart-button button-primary js-add-to-cart" 
                        data-product-id="{{ $product->id }}">
                    Add to Cart
                </button>
            @else
                <a href="{{ route('login') }}" class="add-to-cart-button">
                    Add to Cart
                </a>
            @endauth
            </div>
        @endforeach
    </div>

    <x-slot name="scripts">
        @vite(['resources/js/amazon.js'])
    </x-slot>
</x-layouts.front-layout>
