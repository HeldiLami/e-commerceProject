export const orders = JSON.parse(localStorage.getItem('orders')) || [];


export function addOrder(order){
  orders.unshift(order);
  saveCartToStorage();
}

function saveCartToStorage(){
  localStorage.setItem('orders', JSON.stringify(order));
}