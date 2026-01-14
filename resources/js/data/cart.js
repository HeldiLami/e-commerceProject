export let cart;

loadFromStorage();

export function loadFromStorage(){
  cart= JSON.parse(localStorage.getItem('cart'))
}


function saveToStorage(){
  localStorage.setItem('cart', JSON.stringify(cart));
}

export function addToCart(productId){
  let matchingItem;
  if(!cart) return;
  cart.forEach((cartItem)=>{
    if(cartItem.productId === productId){
      matchingItem = cartItem;
    }
  });

  if(matchingItem){
    matchingItem.quantity++;
  }
  else{
    cart.push({
      productId: productId,
      quantity: 1,
      deliveryOptionId: '1'
    });
  }
  saveToStorage();
}

export function removeFromCart(productId){
  const newCartArray=[];
  if(!cart) return;
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
  if(!cart) return;

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
  if(!cart) return;
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
  try {
    const response = await fetch('https://supersimplebackend.dev/cart');
    const cartData = await response.json(); 
    cart = cartData;
    saveToStorage();
    console.log('Cart loaded from backend:', cart);
  } catch (error) {
    console.error('Unexpected error loading cart:', error);
  }
}