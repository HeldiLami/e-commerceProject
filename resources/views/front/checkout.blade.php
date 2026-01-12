<!DOCTYPE html>
<html>
  <head>
    <title>Checkout</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/general.css', 'resources/css/pages/checkout/checkout-header.css', 'resources/css/pages/checkout/checkout.css'])
  </head>
  <body>
    <div class="checkout-header">
      <div class="header-content">
        <div class="checkout-header-left-section">
          <a href="{{ url('/amazon') }}">
            <img class="amazon-logo" src="{{ asset('images/amazon-logo.png') }}">
            <img class="amazon-mobile-logo" src="{{ asset('images/amazon-mobile-logo.png') }}">
          </a>
        </div>

        <div class="checkout-header-middle-section">
          Checkout (<a class="return-to-home-link"
            href="{{ url('/amazon') }}"><span class="js-checkout-quantity"></span>
          </a>)
        </div>

        <div class="checkout-header-right-section">
          <img src="{{ asset('images/icons/checkout-lock-icon.png') }}">
        </div>
      </div>
    </div>

    <div class="main">
      <div class="page-title">Review your order</div>

      <div class="checkout-grid">
        <div class="order-summary js-order-summary">
        </div>

        <div class="payment-summary js-payment-summary">
          
        </div>
      </div>
    </div>

    {{-- <script type="module" src="{{ asset('scripts/checkout.js') }}"></script> --}}
  </body>
</html>

