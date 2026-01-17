import { loadCartFromStorage, addToCart, updateCartQuantity } from "./data/cart.js";

function setupProductButtons() {
  loadCartFromStorage();
  updateCartQuantity(".js-cart-quantity");

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

  const buyBtn = document.querySelector(".js-buy-now");
  if (buyBtn) {
    buyBtn.addEventListener("click", (e) => {
      e.preventDefault();
      window.location.href = "/cart";
    });
  }

  setupReviewModal();
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

    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        closeModal(modal);
      });
    }

    window.addEventListener("click", (event) => {
      if (event.target === modal) {
        closeModal(modal);
      }
    });
  }
}

function closeModal(modalElement) {
  modalElement.style.display = "none";
  document.body.style.overflow = "auto";
}

setupProductButtons();