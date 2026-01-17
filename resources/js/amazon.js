import { addToCart, updateCartQuantity } from "./data/cart.js";

function setupAddToCart() {
  const grid = document.querySelector(".js-products-grid");
  if (!grid) return;

  grid.querySelectorAll(".js-add-to-cart").forEach((button) => {
    button.addEventListener("click", () => {
      const productId = button.dataset.productId;
      const quantitySelector = grid.querySelector(`.js-quantity-selector-${productId}`);
      const quantity = quantitySelector ? Number(quantitySelector.value) : 1;
      addToCart(productId, quantity);
      updateCartQuantity(".js-cart-quantity");
    });
  });
}

setupAddToCart();
