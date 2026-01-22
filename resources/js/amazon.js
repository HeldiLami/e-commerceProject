import { addToCart, updateCartQuantityHTML } from "./cart.js";
import { addedToCart } from "./utils/addedToCartDisplay.js";
//aktivizon butonat add to cart ne faqet e produkteve
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

setupAddToCartButtons();
