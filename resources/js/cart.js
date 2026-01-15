import {
  cart,
  loadFromStorage,
  removeFromCart,
  setCart,
  updateCartQuantity,
  updateQuantity,
} from "./data/cart.js";
import { formatCurrency } from "./utils/money.js";

let cartSnapshot = [];

function renderCart() {
  const products = Array.isArray(window.cartProductsData) ? window.cartProductsData : [];
  const productMap = new Map(products.map((product) => [product.id, product]));

  const itemsContainer = document.querySelector(".js-cart-items");
  const summaryContainer = document.querySelector(".js-cart-summary");
  if (!itemsContainer || !summaryContainer) {
    return;
  }

  const validItems = cart.filter((item) => productMap.has(item.productId) && item.quantity > 0);
  if (validItems.length !== cart.length) {
    setCart(validItems);
  }
  cartSnapshot = validItems;

  if (validItems.length === 0) {
    itemsContainer.innerHTML = `<div class="cart-empty">Your cart is empty.</div>`;
    summaryContainer.innerHTML = `
      <div class="cart-summary-card">
        <div class="summary-row">
          <span>Items</span>
          <span>0</span>
        </div>
        <div class="summary-row summary-total">
          <span>Total</span>
          <span>$0.00</span>
        </div>
        <button class="place-order-button button-primary" type="button" disabled>
          Place order
        </button>
      </div>
    `;
    return;
  }

  let totalQuantity = 0;
  let totalCents = 0;
  let itemsHtml = "";

  validItems.forEach((cartItem) => {
    const product = productMap.get(cartItem.productId);
    if (!product) {
      return;
    }

    totalQuantity += cartItem.quantity;
    totalCents += product.price_cents * cartItem.quantity;

    const imageUrl = product.image.startsWith("http")
      ? product.image
      : `${window.location.origin}/${product.image.replace(/^\/+/, "")}`;

    itemsHtml += `
      <div class="cart-item">
        <img class="cart-item-image" src="${imageUrl}" alt="${product.name}">
        <div class="cart-item-details">
          <div class="cart-item-name">${product.name}</div>
          <div class="cart-item-price">$${formatCurrency(product.price_cents)}</div>
          <div class="cart-item-quantity">
            <button class="quantity-button js-quantity-minus" data-product-id="${product.id}" type="button">-</button>
            <span class="quantity-value">${cartItem.quantity}</span>
            <button class="quantity-button js-quantity-plus" data-product-id="${product.id}" type="button">+</button>
          </div>
        </div>
        <button class="remove-button js-remove-item" data-product-id="${product.id}" type="button">
          <span class="remove-icon">🗑</span>
        </button>
      </div>
    `;
  });

  itemsContainer.innerHTML = itemsHtml;
  summaryContainer.innerHTML = `
    <div class="cart-summary-card">
      <div class="summary-row">
        <span>Items</span>
        <span>${totalQuantity}</span>
      </div>
      <div class="summary-row summary-total">
        <span>Total</span>
        <span>$${formatCurrency(totalCents)}</span>
      </div>
      <button class="place-order-button button-primary js-place-order" type="button">
        Place order
      </button>
      <div class="cart-status js-cart-status" aria-live="polite"></div>
    </div>
  `;
}

async function handlePlaceOrder(button) {
  const statusElement = document.querySelector(".js-cart-status");
  if (statusElement) {
    statusElement.textContent = "Creating order...";
  }

  if (cartSnapshot.length === 0) {
    if (statusElement) {
      statusElement.textContent = "Your cart is empty.";
    }
    return;
  }

  const tokenElement = document.querySelector('meta[name="csrf-token"]');
  const token = tokenElement ? tokenElement.getAttribute("content") : "";

  const payload = {
    items: cartSnapshot.map((item) => ({
      product_id: item.productId,
      quantity: item.quantity,
    })),
  };

  try {
    button.disabled = true;
    const response = await fetch("/orders", {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": token,
      },
      body: JSON.stringify(payload),
    });

    if (!response.ok) {
      let errorText = "Order could not be created. Please try again.";
      if (response.status === 401 || response.status === 419) {
        errorText = "Please sign in to place an order.";
      } else {
        try {
          const errorData = await response.json();
          if (errorData && errorData.message) {
            errorText = errorData.message;
          }
        } catch (parseError) {
          // Ignore JSON parse errors
        }
      }
      throw new Error(errorText);
    }

    const data = await response.json();
    if (!data || !data.order_id) {
      throw new Error("Order could not be created.");
    }

    const sessionResponse = await fetch("/checkout/session", {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": token,
      },
      body: JSON.stringify({ order_id: data.order_id }),
    });

    const sessionData = await sessionResponse.json();
    if (!sessionResponse.ok || !sessionData.checkoutUrl) {
      throw new Error("Could not start Stripe checkout.");
    }

    localStorage.removeItem("cart");
    window.location.href = sessionData.checkoutUrl;
  } catch (error) {
    if (statusElement) {
      statusElement.textContent = error.message || "Order could not be created.";
    }
  } finally {
    button.disabled = false;
  }
}

function initCartPage() {
  loadFromStorage();
  renderCart();
  updateCartQuantity(".js-cart-quantity");

  const summaryContainer = document.querySelector(".js-cart-summary");
  if (summaryContainer) {
    summaryContainer.addEventListener("click", (event) => {
      const button = event.target.closest(".js-place-order");
      if (!button || cart.length === 0) {
        return;
      }
      handlePlaceOrder(button);
    });
  }

  const itemsContainer = document.querySelector(".js-cart-items");
  if (itemsContainer) {
    itemsContainer.addEventListener("click", (event) => {
      const removeButton = event.target.closest(".js-remove-item");
      if (removeButton) {
        const productId = removeButton.dataset.productId;
        removeFromCart(productId);
        renderCart();
        updateCartQuantity(".js-cart-quantity");
        return;
      }

      const plusButton = event.target.closest(".js-quantity-plus");
      if (plusButton) {
        const productId = plusButton.dataset.productId;
        const cartItem = cart.find((item) => item.productId === productId);
        if (cartItem) {
          updateQuantity(productId, cartItem.quantity + 1);
          renderCart();
          updateCartQuantity(".js-cart-quantity");
        }
        return;
      }

      const minusButton = event.target.closest(".js-quantity-minus");
      if (minusButton) {
        const productId = minusButton.dataset.productId;
        const cartItem = cart.find((item) => item.productId === productId);
        if (cartItem) {
          const newQuantity = cartItem.quantity - 1;
          if (newQuantity <= 0) {
            removeFromCart(productId);
          } else {
            updateQuantity(productId, newQuantity);
          }
          renderCart();
          updateCartQuantity(".js-cart-quantity");
        }
      }
    });
  }
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initCartPage);
} else {
  initCartPage();
}
