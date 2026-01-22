import { addToCart, updateCartQuantityHTML } from "./cart.js";

export function setupAddToCartButtons() {
    document.querySelectorAll(".js-add-to-cart").forEach((button) => {
        button.addEventListener("click", () => {
            const productId = button.dataset.productId;
            const quantitySelector = document.querySelector(
                `.js-quantity-selector-${productId}`,
            );
            const quantity = quantitySelector
                ? Number(quantitySelector.value)
                : 1;
            addToCart(productId, quantity);
            updateCartQuantityHTML(".js-cart-quantity");
            addedToCart(productId);
        });
    });
}

const addedTimers = {};

function addedToCart(productId) {
  const message = document.querySelector(`.js-added-to-cart-${productId}`);  
  if (!message) return;

  message.classList.add('added-to-cart-visible');

  if (addedTimers[productId]) {
    clearTimeout(addedTimers[productId]);
  }

  const timeoutId = setTimeout(() => {
    message.classList.remove('added-to-cart-visible');
    addedTimers[productId] = undefined;
  }, 3000);

  addedTimers[productId] = timeoutId;
}

setupAddToCartButtons();
