import { updateCartQuantity } from "../data/cart.js";

// Update cart quantity display on page load
function updateCartDisplay() {
  updateCartQuantity('.js-cart-quantity');
}

// If DOM is already loaded, update immediately
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', updateCartDisplay);
} else {
  // DOM is already loaded
  updateCartDisplay();
}

