import { addToCart, updateCartQuantity } from "./data/cart.js";

function setupBuyAgain() {
  document.querySelectorAll(".js-buy-again").forEach((button) => {
    button.addEventListener("click", () => {
      const productId = button.dataset.productId;
      const quantity = Number(button.dataset.quantity) || 1;

      addToCart(productId, quantity);
      updateCartQuantity(".js-cart-quantity");
      window.location.href = "/cart";
    });
  });
}

setupBuyAgain();
