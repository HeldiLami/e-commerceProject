@props(['title' => 'Amazon'])

<!DOCTYPE html>
<html>
  <head>
    <title>{{ $title }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/general.css', 'resources/css/amazon-header.css'])
    
    {{ $css ?? '' }}
  </head>
  <body>
    <div class="amazon-header">
      <div class="amazon-header-left-section">
        <a href="{{ url('/') }}" class="header-link">
          <img class="amazon-logo" src="{{ asset('images/amazon-logo-white.png') }}">
        </a>

        @can('is-verified')
            <a href="{{ route('users.show', auth()->id()) }}" class="header-user-avatar">
                <img 
                  src="{{ asset('images/icons/default-user-icon.png') }}"
                  alt="User Avatar"
                >
            </a>
        @endcan
      </div>

      <div class="amazon-header-middle-section">
        <form class="search-form" method="GET" action="{{ route('products.search') }}">
          <input
            class="search-bar"
            type="text"
            name="q"
            value="{{ $query ?? '' }}"
            placeholder="Search"
          >
          <button class="search-button" type="submit">
            <img class="search-icon" src="{{ asset('images/icons/search-icon.png') }}">
          </button>
        </form>
      </div>

      <div class="amazon-header-right-section">
        @auth
        @can('is-verified')
          <a class="orders-link header-link" href="{{ url('/orders') }}">
              <span class="returns-text">Returns</span>
              <span class="orders-text">& Orders</span>
          </a>
  
          <a class="cart-link header-link" href="{{ url('/cart') }}">
              <img class="cart-icon" src="{{ asset('images/icons/cart-icon.png') }}">
              <div class="cart-quantity js-cart-quantity">0</div>
              <div class="cart-text">Cart</div>
          </a>
          
          <form method="POST" action="{{ route('logout') }}" class="inline js-logout-form">
              @csrf
              <button type="submit" class="button-gold">
                  Log Out
              </button>
          </form>
          @endcan
        @endauth
    
        @if(auth()->guest() || auth()->user()->cannot('is-verified'))
          <a href="{{ route('login') }}" class="button-silver">Sign in</a>
          <a href="{{ route('register') }}" class="button-gold">Sign Up</a>
        @endif
    </div>
    </div>

    <div class="main">
        {{ $slot }}
    </div>

    {{ $scripts ?? '' }}
    
    @vite(['resources/js/utils/updateCartDisplay.js'])

    <script>
      const logoutForm = document.querySelector('.js-logout-form');
      if (logoutForm) {
        logoutForm.addEventListener('submit', () => {
          localStorage.removeItem('cart');
        });
      }
    </script>
  </body>
</html>
