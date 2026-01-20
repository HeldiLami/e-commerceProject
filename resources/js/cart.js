import { formatCurrency } from "./utils/money.js";

export let cart;

loadCartFromStorage();

export function loadCartFromStorage() {
    const storedCart = JSON.parse(localStorage.getItem("cart"));
    //thirret vtm per siguri ne rastin kur localStorage eshte bosh
    setCart(storedCart);
}

export function setCart(newCart) {
    cart = Array.isArray(newCart) ? newCart : [];
    localStorage.setItem("cart", JSON.stringify(cart));
}

export function addToCart(productId, quantity = 1) {
    const quantityToAdd = Number(quantity) || 1;
    if (quantityToAdd <= 0) {
        return;
    }

    const matchingItem = cart.find(
        (cartItem) => cartItem.productId === productId,
    );
    if (matchingItem) {
        matchingItem.quantity += quantityToAdd;
    } else {
        cart.push({
            productId: productId,
            quantity: quantityToAdd,
            deliveryOptionId: "1",
        });
    }
    setCart(cart);
}

export function removeFromCart(productId) {
    if (!cart) return;
    const itemToRemove = cart.find(
        (cartItem) => cartItem.productId === productId,
    );
    if (!itemToRemove) return;
    setCart(cart.filter((cartItem) => cartItem !== itemToRemove));
}

export function updateQuantity(productId, newQuantity) {
    if (!cart) return;
    cart.forEach((cartItem) => {
        if (cartItem.productId === productId) {
            cartItem.quantity = newQuantity;
            setCart(cart);
        }
    });
}

let cartSnapshot = [];
const CART_CLEAR_FLAG = "cart_clear_after_order";

function refreshUI() {
    renderCart();
    updateCartQuantityHTML();
}

function clearCartIfFlagged() {
    if (localStorage.getItem(CART_CLEAR_FLAG) === "1") {
        setCart([]);
        localStorage.removeItem(CART_CLEAR_FLAG);
    }
}

function syncCartFromStorage() {
    loadCartFromStorage();
    refreshUI();
}

function renderCart() {
    const products = Array.isArray(window.cartProductsData)
        ? window.cartProductsData
        : [];
    const productMap = new Map(
        products.map((product) => [product.id, product]),
    );
    const itemsContainer = document.querySelector(".js-cart-items");
    const summaryContainer = document.querySelector(".js-cart-summary");

    if (!itemsContainer || !summaryContainer) return;

    const validItems = cart.filter(
        (item) => productMap.has(item.productId) && item.quantity > 0,
    );
    if (validItems.length !== cart.length) setCart(validItems);
    cartSnapshot = validItems;

    if (validItems.length === 0) {
        itemsContainer.innerHTML = `<div class="cart-empty">Your cart is empty.</div>`;
        summaryContainer.innerHTML = getSummaryHtml(0, 0, true);
        return;
    }

    let totalQuantity = 0;
    let totalCents = 0;

    itemsContainer.innerHTML = validItems
        .map((item) => {
            const product = productMap.get(item.productId);
            totalQuantity += item.quantity;
            totalCents += product.price_cents * item.quantity;
            return getCartItemHtml(product, item);
        })
        .join("");

    summaryContainer.innerHTML = getSummaryHtml(totalQuantity, totalCents);
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
                Accept: "application/json",
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
                } catch (parseError) {}
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
                Accept: "application/json",
                "X-CSRF-TOKEN": token,
            },
            body: JSON.stringify({ order_id: data.order_id }),
        });

        const sessionData = await sessionResponse.json();
        if (!sessionResponse.ok || !sessionData.checkoutUrl) {
            throw new Error("Could not start Stripe checkout.");
        }

        localStorage.removeItem("cart");
        localStorage.setItem(CART_CLEAR_FLAG, "1");
        setCart([]);
        refreshUI();
        if (statusElement) {
            statusElement.textContent = "Redirecting to payment...";
        }
        requestAnimationFrame(() => {
            window.location.href = sessionData.checkoutUrl;
        });
    } catch (error) {
        if (statusElement) {
            statusElement.textContent =
                error.message || "Order could not be created.";
        }
    } finally {
        button.disabled = false;
    }
}

function initCartPage() {
    clearCartIfFlagged();
    syncCartFromStorage();

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
            const target = event.target;
            // Gjejmë butonin më të afërt që ka data-product-id
            const btn = target.closest("[data-product-id]");
            if (!btn) return;

            const productId = btn.dataset.productId;
            const cartItem = cart.find((i) => i.productId === productId);

            if (target.closest(".js-remove-item")) {
                removeFromCart(productId);
            } else if (target.closest(".js-quantity-plus")) {
                updateQuantity(productId, cartItem.quantity + 1);
            } else if (target.closest(".js-quantity-minus")) {
                const newQty = cartItem.quantity - 1;
                newQty <= 0
                    ? removeFromCart(productId)
                    : updateQuantity(productId, newQty);
            } else {
                return;
            }

            refreshUI();
        });
    }
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initCartPage);
} else {
    initCartPage();
}

window.addEventListener("pageshow", (event) => {
    const navigation = performance.getEntriesByType("navigation")[0];
    const isBackForward =
        event.persisted || (navigation && navigation.type === "back_forward");
    if (!isBackForward) {
        return;
    }

    if (localStorage.getItem(CART_CLEAR_FLAG) === "1") {
        localStorage.removeItem(CART_CLEAR_FLAG);
        setCart([]);
        refreshUI();
        window.location.reload();
        return;
    }

    syncCartFromStorage();
});

document.addEventListener("visibilitychange", () => {
    if (document.visibilityState === "visible") {
        syncCartFromStorage();
    }
});

window.addEventListener("focus", () => {
    syncCartFromStorage();
});

export function updateCartQuantityHTML() {
    let cartQuantity = 0;
    if (!cart) return;

    cart.forEach((cartItem) => {
        cartQuantity += cartItem.quantity;
    });

    const element = document.querySelector(".js-cart-quantity");
    element.innerHTML = `${cartQuantity}`;
}

function getCartItemHtml(product, cartItem) {
    const imageUrl = product.image.startsWith("http")
        ? product.image
        : `${window.location.origin}/${product.image.replace(/^\/+/, "")}`;

    return `
    <div class="cart-item">
      <img class="cart-item-image" src="${imageUrl}" alt="${product.name}">
      <div class="cart-item-details">
        <div class="cart-item-name">${product.name}</div>
        <div class="cart-item-price">$${formatCurrency(product.price_cents)}</div>
        <div class="cart-item-quantity">
          <button class="quantity-button js-quantity-minus" data-product-id="${product.id}">-</button>
          <span class="quantity-value">${cartItem.quantity}</span>
          <button class="quantity-button js-quantity-plus" data-product-id="${product.id}">+</button>
        </div>
      </div>
      <button class="remove-button js-remove-item" data-product-id="${product.id}">
        <span class="remove-icon">🗑</span>
      </button>
    </div>
  `;
}

function getSummaryHtml(totalQuantity, totalCents, isEmpty = false) {
    return `
    <div class="cart-summary-card">
      <div class="summary-row">
        <span>Items</span>
        <span>${totalQuantity}</span>
      </div>
      <div class="summary-row summary-total">
        <span>Total</span>
        <span>$${formatCurrency(totalCents)}</span>
      </div>
      <button class="place-order-button button-yellow ${!isEmpty ? "js-place-order" : ""}" 
              type="button" ${isEmpty ? "disabled" : ""}>
        Place order
      </button>
      <div class="cart-status js-cart-status" aria-live="polite"></div>
    </div>
  `;
}
