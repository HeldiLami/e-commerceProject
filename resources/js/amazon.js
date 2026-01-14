import { addToCart, updateCartQuantity } from "./data/cart.js";

function setupAddToCart() {
  document.querySelectorAll('.js-add-to-cart')
    .forEach((button) => {
      button.addEventListener('click', () => {
        const productId = button.dataset.productId;
        addToCart(productId);
        updateCartQuantity('.js-cart-quantity');
      });
    });
}

setupAddToCart();