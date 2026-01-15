export let cart;

loadFromStorage();

export function loadFromStorage(){
  cart = JSON.parse(localStorage.getItem('cart'));
  if (!Array.isArray(cart)) {
    cart = [];
    saveToStorage();
  }

}


function saveToStorage(){
  localStorage.setItem('cart', JSON.stringify(cart));
}

export function setCart(newCart){
  cart = Array.isArray(newCart) ? newCart : [];
  saveToStorage();
}

export function addToCart(productId, quantity = 1){
  let matchingItem;
  const quantityToAdd = Number(quantity) || 1;
  if (quantityToAdd <= 0) {
    return;
  }
  cart.forEach((cartItem)=>{
    if(cartItem.productId === productId){
      matchingItem = cartItem;
    }
  });

  if(matchingItem){
    matchingItem.quantity += quantityToAdd;
  }
  else{
    cart.push({
      productId: productId,
      quantity: quantityToAdd,
      deliveryOptionId: '1'
    });
  }
  saveToStorage();
}

export function removeFromCart(productId){
  const newCartArray=[];
  cart.forEach((cartItem)=>{
    if(cartItem.productId !== productId){
      newCartArray.push(cartItem);
    }
  });

  cart = newCartArray;
  saveToStorage();
}
export function updateCartQuantity(quantityElement){
  let cartQuantity = 0;
  cart.forEach((cartItem) => {
    cartQuantity += cartItem.quantity;
  });

  const element = document.querySelector(quantityElement);
  if(element) {
    if(cartQuantity === 0){
      element.innerHTML = '0';
    } else {
      element.innerHTML = `${cartQuantity}`;
    }
  }
}

export function updateQuantity(productId, newQuantity){
  cart.forEach((cartItem)=>{
    if(cartItem.productId === productId){
      cartItem.quantity = newQuantity;
      saveToStorage();
    }
  });
}

export function updateDeliveryOption(productId, deliveryOptionId){
  let matchingItem;
  cart.forEach((cartItem)=>{
    if(cartItem.productId === productId){
      matchingItem = cartItem;
    }
  });

  matchingItem.deliveryOptionId = deliveryOptionId;

  saveToStorage();
}


export async function loadCart() {
  loadFromStorage();
}
