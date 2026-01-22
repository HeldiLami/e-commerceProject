import { formatCurrency } from "./utils/money.js";

export let cart;

loadCartFromStorage();

export function loadCartFromStorage() {
    //Lexon shporten nga lolcalstorage  dhe e vendos ne gjendjen e aplikacionit
    const storedCart = JSON.parse(localStorage.getItem("cart"));
    //thirret vtm per siguri ne rastin kur localStorage eshte bosh
    setCart(storedCart);
}
//Funksioni export mund te perdoret ne module te tjera
export function setCart(newCart) {
    //Ruan shporten ne local storage
    cart = Array.isArray(newCart) ? newCart : [];
    localStorage.setItem("cart", JSON.stringify(cart));
}

export function addToCart(productId, quantity = 1) {
    //Shton sasin qe mer nga f.end
    const quantityToAdd = Number(quantity) || 1;
    if (quantityToAdd <= 0) {
        return;
    }

    const matchingItem = cart.find(
        //kthen elementin e pare qe plotson kushtin
        //Kontrollon nese egziston ne shport produkti qe do shtojm sasin
        (cartItem) => cartItem.productId === productId, //cartItem esht cdo element i arrayt
    );
    if (matchingItem) {
        matchingItem.quantity += quantityToAdd;
    } else {
        //Ndryshe shton nje tjt ne list
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
    setCart(cart.filter((cartItem) => cartItem !== itemToRemove)); //shton ne kart elementet nga e para pervec atij qe duhet te heqi
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

let cartSnapshot = []; //Siguron nje gjendje te vlefshme te shportes pa produkte te vjeteruara
const CART_CLEAR_FLAG = "cart_clear_after_order"; //Nese shporta duhet pastruar pas porosise

function refreshUI() {
    //Rifreskon pamjen e shportes
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
    //Ngarkon shporten ne localStorage dhe pastaj rifreson UI
    loadCartFromStorage();
    refreshUI();
}

function renderCart() {
    const products = Array.isArray(window.cartProductsData)
        ? window.cartProductsData
        : [];
    const productMap = new Map( //Krijon map per te gjetur produktin sipas id
        products.map((product) => [product.id, product]),
    );
    const itemsContainer = document.querySelector(".js-cart-items");
    const summaryContainer = document.querySelector(".js-cart-summary");

    if (!itemsContainer || !summaryContainer) return;

    const validItems = cart.filter(
        //filtron shporten duke mbajt produktet qe egzistojn dhe qe kan sasi>0
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
        .join(""); //join bashkon te gjitha pjeset ne njee string

    summaryContainer.innerHTML = getSummaryHtml(totalQuantity, totalCents);
}

async function handlePlaceOrder(button) {
    //Shfaq creating order dhe pergatit nje objekt jason per serverin
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

    const tokenElement = document.querySelector('meta[name="csrf-token"]'); //kerkon nje tag<meta> me emer name
    const token = tokenElement ? tokenElement.getAttribute("content") : ""; //mer vleren e token nga front dhe nese ve bosh

    //Objekt ku items eshte liste e ndertuar nga cartSnapshot per tu derguar tek serveri
    const payload = {
        items: cartSnapshot.map((item) => ({
            product_id: item.productId,
            quantity: item.quantity,
        })),
    };

    try {
        button.disabled = true;
        const response = await fetch("/orders", {
            //Ben kerkese  http dhe pret pergjigje
            method: "POST", //krijon porosi
            credentials: "same-origin", //Dergon cookies vtm per te njejtin origin(domain)
            headers: {
                //Fillon objektin e headerave
                "Content-Type": "application/json", //Tregon qe trupi i kerkeses eshte json
                Accept: "application/json", //Pranon json
                "X-CSRF-TOKEN": token, //dergon tokenin csrf per mbrojtje nga kerkesa te rreme
            },
            body: JSON.stringify(payload), //dergon te dhenat si jason
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

        //fillon krijimin e stripe checkout session
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
        //Kap error nese /orders kthen jo ok ose mungon order_id ose /checkout/session deshton
        if (statusElement) {
            statusElement.textContent =
                error.message || "Order could not be created.";
        }
    } finally {
        button.disabled = false;
    }
}

function initCartPage() {
    //Inicializon faqen e shportes pra pastron sinkronizon karten dhe vendos listenera
    clearCartIfFlagged();
    syncCartFromStorage();

    const summaryContainer = document.querySelector(".js-cart-summary");
    if (summaryContainer) {
        summaryContainer.addEventListener("click", (event) => {
            const button = event.target.closest(".js-place-order"); //Gjen butonin me te afert me klasen js-place-order
            if (!button || cart.length === 0) {
                //Nese nuk eshte klikuar butoni ose shporta bosh
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

            const productId = btn.dataset.productId; //Merr vleren e data-product-id nga ai element
            const cartItem = cart.find((i) => i.productId === productId); //Gjen produktin perkatex ne shporte

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
    //Shton listener per pageshow
    const navigation = performance.getEntriesByType("navigation")[0]; //Merr informacionin e navigimit të fundit të faqes si psh perdorusi klikoj shigjeten back.
    const isBackForward =
        event.persisted || (navigation && navigation.type === "back_forward"); //event.persisted është true kur faqja vjen nga bfcache (back/forward cache).
    if (!isBackForward) {
        return;
    }

    if (localStorage.getItem(CART_CLEAR_FLAG) === "1") {
        localStorage.removeItem(CART_CLEAR_FLAG);
        setCart([]); //Boshatis shportën në memorje.
        refreshUI(); //Rifreskon pamjen e shportës.
        window.location.reload(); //Rifreskon faqen.
        return;
    }

    syncCartFromStorage(); //Nëse s’ka flamur, thjesht sinkronizon shportën nga localStorage.
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
    // Kthen foto,emer,sasi,cmim + butona +/- dhe remove
    const imageUrl = product.image.startsWith("http")
        ? product.image
        : `${window.location.origin}/${product.image.replace(/^\/+/, "")}`; //baza e faqes + replace qe heq /

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
    // Kthen permbledhjen e shportes nr total te artikujve + totali cmimit  butoni place order dhe statusi
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
