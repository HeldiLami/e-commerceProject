import { loadCartFromStorage, updateCartQuantityHTML } from "./cart.js";
import "./amazon.js";
import "./orders.js";

function setupProductButtons() {
    loadCartFromStorage();
    updateCartQuantityHTML();
    setupReviewModal();
    //addToCartButton button inicializohet direkte nga amazon.js
    //e hoqa se psj inicializohej 2 here dhe jepte do jepte dyfishin
    //nuk eshte zgjidhja me e mire besoj

    //same thing per setupBuyButton
}

function setupReviewModal() {
    const modal = document.getElementById("reviewModal");
    const openBtn = document.getElementById("openReviewBtn");
    const closeBtn = document.querySelector(".close-modal");

    if (openBtn && modal) {
        openBtn.addEventListener("click", () => {
            modal.style.display = "block";
            document.body.style.overflow = "hidden";
        });
    }

    if (closeBtn && modal) {
        closeBtn.addEventListener("click", () => {
            closeModal(modal);
        });
    }
}

function closeModal(modalElement) {
    modalElement.style.display = "none";
    document.body.style.overflow = "auto";
}

setupProductButtons();
