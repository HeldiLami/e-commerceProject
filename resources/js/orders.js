import { addToCart, updateCartQuantityHTML } from "./cart.js";
//logjika per butonin buy
export function setupBuyButton() {
    document.querySelectorAll(".js-buy").forEach((button) => {
        button.addEventListener("click", () => {
            const productId = button.dataset.productId;
            const quantity = Number(button.dataset.quantity) || 1;
            addToCart(productId, quantity);
            updateCartQuantityHTML();
            window.location.href = "/cart"; //ben ridirect tek /cart
        });
    });
}

setupBuyButton();
