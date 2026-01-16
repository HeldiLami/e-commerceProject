export let cart;

loadCartFromStorage();

function save() {
  localStorage.setItem('cart', JSON.stringify(cart));
}

export function loadCartFromStorage(){
  cart = JSON.parse(localStorage.getItem('cart'));
  if (!Array.isArray(cart)) {
    cart = [];
    save();
  }
}

export function setCart(newCart){
  cart = Array.isArray(newCart) ? newCart : [];
  save();
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
  save();
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
  save();
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
      save();
    }
  });
}