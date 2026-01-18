import { updateCartQuantity } from "../cart.js";

// Update cart quantity display on page load
function updateCartDisplay() {
  updateCartQuantity('.js-cart-quantity');
}

function setupLogoutHandler() {
  const logoutForm = document.querySelector('.js-logout-form');
  if (!logoutForm) {
    return;
  }

  logoutForm.addEventListener('submit', () => {
    localStorage.removeItem('cart');
  });
}

// If DOM is already loaded, update immediately
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    updateCartDisplay();
    setupLogoutHandler();
  });
} else {
  // DOM is already loaded
  updateCartDisplay();
  setupLogoutHandler();
}
