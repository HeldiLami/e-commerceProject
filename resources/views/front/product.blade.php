@php
  $ratingStars = (float) ($product->rating_stars ?? 0);
  $ratingFile = 'images/ratings/rating-' . ((int) round($ratingStars * 10)) . '.png';

  // keywords mund të jetë JSON string ose null
  $keywords = $product->keywords;
  if (is_string($keywords)) {
    $decoded = json_decode($keywords, true);
    $keywords = is_array($decoded) ? $decoded : [];
  }
  if (!is_array($keywords)) $keywords = [];
@endphp

<x-layouts.front-layout title="{{ $product->name }}">
  <x-slot name="css">
    @vite(['resources/css/product.css'])
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
          <img class="stars" src="/{{ $ratingFile }}" alt="rating">
          <span class="count">{{ (int) $product->rating_count }} ratings</span>
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
            <select id="qty" class="qty-select">
              @for ($i=1; $i<=10; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
              @endfor
            </select>
          </div>

          <button class="button-primary w100" style="padding:5px;" type="button">
            Add to Cart
          </button>

          <button class="button-gold w100" type="button">
            Buy Now
          </button>

          <div class="note">Secure transaction • Amazon Clone</div>
        </div>

        @if(count($keywords))
          <div class="keywords">
            @foreach($keywords as $k)
              <span class="pill">{{ $k }}</span>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</x-layouts.front-layout>
