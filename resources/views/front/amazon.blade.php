<!DOCTYPE html>
<html>
  <head>
    <title>Amazon Project</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/general.css', 'resources/css/amazon-header.css', 'resources/css/pages/amazon.css'])
  </head>
  <body>
    <div class="amazon-header">
      <div class="amazon-header-left-section">
        <a href="{{ url('/amazon') }}" class="header-link">
          <img class="amazon-logo"
            src="{{ asset('images/amazon-logo-white.png') }}">
          <img class="amazon-mobile-logo"
            src="{{ asset('images/amazon-mobile-logo-white.png') }}">
        </a>
      </div>

      <div class="amazon-header-middle-section">
        <input class="search-bar" type="text" placeholder="Search">

        <button class="search-button">
          <img class="search-icon" src="{{ asset('images/icons/search-icon.png') }}">
        </button>
      </div>

      <div class="amazon-header-right-section">
        <a class="orders-link header-link" href="{{ url('/orders') }}">
          <span class="returns-text">Returns</span>
          <span class="orders-text">& Orders</span>
        </a>

        <a class="cart-link header-link" href="{{ url('/checkout') }}">
          <img class="cart-icon" src="{{ asset('images/icons/cart-icon.png') }}">
          <div class="cart-quantity js-cart-quantity"></div>
          <div class="cart-text">Cart</div>
        </a>
      </div>
    </div>
    
    <div class="main">
      <div class="products-grid js-products-grid">
       
      </div>
    </div>
    
    {{-- <script type="module" src="{{ asset('scripts/amazon.js') }}"></script> --}}
  </body>
</html>

