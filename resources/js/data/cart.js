export let cart;

loadFromStorage();

export function loadFromStorage(){
  cart= JSON.parse(localStorage.getItem('cart'))
  if(!cart){
    cart = [{
      productId: "e43638ce-6aa0-4b85-b27f-e1d07eb678c6",
      quantity: 2,
      deliveryOptionId: '1' 
    },  {
      productId: "15b6fc6f-327a-4ec4-896f-486349e85a3d",
      quantity: 1,
      deliveryOptionId: '2' 
    }];
  }

}


function saveToStorage(){
  localStorage.setItem('cart', JSON.stringify(cart));
}

export function addToCart(productId){
  let matchingItem;
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