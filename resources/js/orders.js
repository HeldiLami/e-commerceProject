import { addToCart, updateCartQuantity } from "./data/cart.js";

export function setupBuyButton() {
  document.querySelectorAll(".js-buy").forEach((button) => {
    button.addEventListener("click", () => {
      const productId = button.dataset.productId;
      const quantity = Number(button.dataset.quantity) || 1;
      addToCart(productId, quantity);
      updateCartQuantity(".js-cart-quantity");
      window.location.href = "/cart";
    });
  });
}

setupBuyButton();
