import { loadFromStorage, addToCart, updateCartQuantity } from "./data/cart.js";

function setupProductButtons() {
  loadFromStorage();
  updateCartQuantity(".js-cart-quantity");

  // ✅ Add To Cart (me quantity)
  const addBtn = document.querySelector(".js-add-to-cart");
  if (addBtn) {
    addBtn.addEventListener("click", () => {
      const productId = addBtn.dataset.productId;

      const qtySelect = document.querySelector(".js-product-qty");
      const quantity = qtySelect ? Number(qtySelect.value) : 1;

      addToCart(productId, quantity);
      updateCartQuantity(".js-cart-quantity");
    });
  }

  // ✅ Buy Now (VETEM redirect, pa shtuar ne cart)
  const buyBtn = document.querySelector(".js-buy-now");
  if (buyBtn) {
    buyBtn.addEventListener("click", (e) => {
      e.preventDefault();
      window.location.href = "/cart";
    });
  }
}

setupProductButtons();