import { addToCart, updateCartQuantity } from "./data/cart.js";
import { loadProductsFetch } from "./data/products.js";

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

await loadProductsFetch();
setupAddToCart();