<x-layouts.front-layout title="{{ $product->name }}">
  <x-slot name="css">
  @vite(['resources/css/product.css'])
  @vite(['resources/css/pages/amazon.css'])
</x-slot>

<x-slot name="scripts">
  @vite(['resources/js/product-page.js'])
</x-slot>


  <div style="margin-top: 100px;"></div>

  <div class="pwrap">
    <a class="back" href="{{ url('/') }}">← Back</a>

    <div class="card">
      <div class="imgbox">
        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
      </div>

      <div class="info">
        <div class="type">{{ ucfirst($product->type ?? 'product') }}</div>

        <h1 class="title">{{ $product->name }}</h1>

        <div class="rating">
          <img class="stars" src="{{ $product->stars_image }}"  alt="rating">
          <span class="count">{{ $product->rating_count }} ratings</span>
        </div>

        <div class="price">
          ${{ number_format($product->price_cents / 100, 2) }}
        </div>

        <div class="stock">
          @if(($product->quantity ?? 0) > 0)
            <span class="in">In stock</span>
            <span class="qty">({{ $product->quantity }})</span>
          @else
            <span class="out">Out of stock</span>
          @endif
        </div>

        <div class="buybox">
          <div class="qty-row">
            <label for="qty">Qty</label>
           <select id="qty" class="qty-select js-quantity-selector-{{ $product->id }}">
              @for ($i=1; $i<=10; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
              @endfor
            </select>

          </div>
          @can('is-verified')
            <button
              class="add-to-cart-button button-yellow  js-add-to-cart" 
              style="border-radius: 50px; font-size: 15px"
              type="button"
              data-product-id="{{ $product->id }}"
            >
              Add to Cart
            </button>
          @else
            <a href="{{ route('login') }}" class="add-to-cart-button button-yellow" style="border-radius: 50px;">
              Add to Cart
            </a>
          @endcan

          <div style="margin-top: 8px;">
            @can('is-verified')
              <button 
                class="button-gold w100 js-buy" 
                type="button"
                data-product-id="{{ $product->id }}"
              >
                Buy Now 
              </button>
            @else
              <a href="{{ route('login') }}" class="button-gold ">
                Buy Now
              </a>
              @auth
              <div class="verify-message">
                Please verify your email
              </div>
            @endauth
            @endcan
        </div>

        @if($product->keywords && count($product->keywords) > 0)
          <div class="keywords-section">
            @foreach($product->keywords as $keyword)
              <span class="keyword">{{ $keyword }}</span>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    <div id="reviews" class="reviews-section">
      <hr class="section-divider">
      
      <div class="reviews-header">
        <h2 class="reviews-main-title">Customer Reviews</h2>
        <button id="openReviewBtn" class="button-gold">
            Write review
        </button>
      </div>

      <x-review-modal :product="$product" />

      <div class="reviews-grid">
        <div class="reviews-summary">
          <div class="summary-header">
            <img class="stars-large" src="{{ $product->stars_image }}" alt="rating">
            <span class="average-text">{{ $product->stars_average }} out of 5</span>
          </div>
          <p class="total-count">{{ $product->rating_count }} global ratings</p>  
        </div>
    
        <div class="reviews-list">
          <h3>Top reviews</h3>
          
          @forelse($product->ratings as $rating)
            <div class="review-item">
              <div class="user-info">
                <img src="{{ asset('images/icons/default-user-icon.png') }}" class="avatar">
                <span class="username">{{ $rating->user->name }}</span>
              </div>
              
              <div class="review-rating">
                <img src="{{ $rating->stars_image }}" class="stars-small">
              </div>
              
              <div class="review-date">Reviewed on {{ $rating->created_at->format('M d, Y') }}</div>
              
              <div class="review-body">
                <p>{{ $rating->comment }}</p>
              </div>
            </div>
          @empty
            <p class="no-reviews">No reviews yet. Be the first to review this product!</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</x-layouts.front-layout>