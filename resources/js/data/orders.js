import { save } from "./cart";
export const orders = JSON.parse(localStorage.getItem('cart')) || [];


export function addOrder(order){
  orders.unshift(order);
  save();
}
