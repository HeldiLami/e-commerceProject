class Cart {
  cartItems;
  #localStorageKey;

  constructor(localStorageKey){
    this.#localStorageKey= localStorageKey;
    this.#loadFromStorage();
  }

  #loadFromStorage(){
    this.cartItems= JSON.parse(localStorage.getItem(this.#localStorageKey))
  if(!this.cartItems){
    this.cartItems = [{
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
saveToStorage(){
  localStorage.setItem(this.#localStorageKey, JSON.stringify(this.cartItems));
}
addToCart(productId){
  let matchingItem;
  this.cartItems.forEach((cartItem)=>{
    if(cartItem.productId === productId){
      matchingItem = cartItem;
    }
  });

  if(matchingItem){
    matchingItem.quantity++;
  }
  else{
    this.cartItems.push({
      productId: productId,
      quantity: 1,
      deliveryOptionId: '1'
    });
  }
  this.saveToStorage();
}
updateCartQuantity(quantityElement){
  let cartQuantity = 0;
  this.cartItems.forEach((cartItem) => {
  cartQuantity += cartItem.quantity;
  
});

if(cartQuantity === 0){
  document.querySelector(quantityElement).innerHTML = '0';
} else {
document.querySelector(quantityElement).innerHTML = `${cartQuantity}`;
}
}

 updateQuantity(productId, newQuantity){
  this.cartItems.forEach((cartItem)=>{
    if(cartItem.productId === productId){
      cartItem.quantity = newQuantity;
      this.saveToStorage();
    }
  });
}

 updateDeliveryOption(productId, deliveryOptionId){
  let matchingItem;
  this.cartItems.forEach((cartItem)=>{
    if(cartItem.productId === productId){
      matchingItem = cartItem;
    }
  });

  matchingItem.deliveryOptionId = deliveryOptionId;

  this.saveToStorage();
}



}

const cart = new Cart('cart-oop');
const businessCart = new Cart('cart-business');

console.log(cart);
console.log(businessCart);