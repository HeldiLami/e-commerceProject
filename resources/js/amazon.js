import { addToCart, updateCartQuantity } from "./data/cart.js";

export function setupAddToCartButtons() {
  document.querySelectorAll('.js-add-to-cart')
    .forEach((button) => {
      button.addEventListener('click', () => {
        const productId = button.dataset.productId;
        const quantitySelector = document.querySelector(`.js-quantity-selector-${productId}`);
        const quantity = quantitySelector ? Number(quantitySelector.value) : 1;
        addToCart(productId, quantity);
        updateCartQuantity('.js-cart-quantity');
      });
    });
}

setupAddToCartButtons();