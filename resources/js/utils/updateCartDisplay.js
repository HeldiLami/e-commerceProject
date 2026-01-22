import { updateCartQuantityHTML } from "../cart.js";

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        updateCartQuantityHTML(".js-cart-quantity");
    });
} else {
    updateCartQuantityHTML(".js-cart-quantity");
}
