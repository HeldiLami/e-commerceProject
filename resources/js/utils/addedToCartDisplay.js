const addedTimers = {};

export function addedToCart(productId) {
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