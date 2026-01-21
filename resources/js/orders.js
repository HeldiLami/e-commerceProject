import { addToCart, updateCartQuantityHTML, cart } from "./cart.js";

export function setupBuyButton() {
    document.querySelectorAll(".js-buy").forEach((button) => {
        button.addEventListener("click", () => {
            const productId = button.dataset.productId;

            const matchingItem = cart.find(item => item.productId === productId);
            if (!matchingItem) {
                addToCart(productId, 1);
                updateCartQuantityHTML();
            }
            
            window.location.href = "/cart";
        });
    });
}

setupBuyButton();
