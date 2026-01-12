import { cart, removeFromCart, updateCartQuantity, updateQuantity, updateDeliveryOption } from "../data/cart.js";
import { getProduct } from "../data/products.js";
import { formatCurrency } from "../utils/money.js";
import dayjs from "https://unpkg.com/dayjs@1.11.10/esm/index.js";
import {deliveryOptions, getDeliveryOption} from "../data/deliveryOption.js"
import { renderPaymentSummary } from "./paymentSummary.js";


export function renderOrderSummary(){
  let cartSummaryHTML= '';


  cart.forEach((cartItem)=>{

    const productId = cartItem.productId;
    const matchingProduct = getProduct(productId);

      const deliveryOptionId = cartItem.deliveryOptionId;

      const deliveryOption = getDeliveryOption(deliveryOptionId);

      const today = dayjs();
      const deliveryDate = today.add(deliveryOption.deliveryDays, 'days');
      const dateString = deliveryDate.format('dddd, MMMM D');


      cartSummaryHTML +=
    `<div class="cart-item-container js-cart-item-container-${matchingProduct.id}">
        <div class="delivery-date">
          Delivery date: ${dateString}
        </div>

        <div class="cart-item-details-grid">
          <img class="product-image"
            src="${matchingProduct.image}">

          <div class="cart-item-details">
            <div class="product-name">
            ${matchingProduct.name}
            </div>
            <div class="product-price">
            ${matchingProduct.getPrice()}
            </div>
            <div class="product-quantity">
              <span>
                Quantity: <span class="quantity-label js-quantity-label-${matchingProduct.id}">${cartItem.quantity}</span>
              </span>
              <span class="update-quantity-link link-primary js-update-button" data-product-id="${matchingProduct.id}">
                Update
              </span>
              <input class="quantity-input js-quantity-input-${matchingProduct.id}">
              <span class="save-quantity-link  js-save-quantity link-primary" data-product-id="${matchingProduct.id}">
                Save
              </span>
              <span class="delete-quantity-link link-primary js-delete-link" data-product-id="${matchingProduct.id}">
                Delete
              </span>
            </div>
          </div>

          <div class="delivery-options">
            <div class="delivery-options-title">
              Choose a delivery option:
            </div>
            ${deliveryOptionsHTML(matchingProduct, cartItem)}
          </div>
        </div>
      </div>`;
  });

  document.querySelector('.js-order-summary').innerHTML= cartSummaryHTML;

  document.querySelectorAll('.js-delete-link')
  .forEach((link)=>{
    link.addEventListener('click', ()=>{
    const productId = link.dataset.productId;
    removeFromCart(productId);
    const container = document.querySelector(`.js-cart-item-container-${productId}`);
    container.remove();
    updateCartQuantity('.js-checkout-quantity');
    renderPaymentSummary();
    });
  });

  updateCartQuantity('.js-checkout-quantity');

  document.querySelectorAll('.js-update-button')
  .forEach((link)=>{
    link.addEventListener('click', ()=>{
      const productId = link.dataset.productId;
      document.querySelector(`.js-cart-item-container-${productId}`).classList.add("is-editing-quantity");
      document.querySelector(`.js-quantity-label-${productId}`).innerHTML = '';
    });
  });

  document.querySelectorAll('.js-save-quantity').forEach((link)=>{
    const productId = link.dataset.productId;
    link.addEventListener('click', ()=>{
      document.querySelector(`.js-cart-item-container-${productId}`).classList.remove("is-editing-quantity");
      const quantityInput =  document.querySelector(`.js-quantity-input-${productId}`);
      let newQuantity  = Number(quantityInput.value);
      updateQuantity(productId, newQuantity);
      updateCartQuantity('.js-checkout-quantity');
      document.querySelector(`.js-quantity-label-${productId}`).innerHTML = newQuantity;
      renderPaymentSummary();

    });
  });


  function deliveryOptionsHTML(matchingProduct, cartItem){
    let HTML = ' ';
  deliveryOptions.forEach((deliveryOption)=>{

      const today = dayjs();
      const deliveryDate = today.add(deliveryOption.deliveryDays, 'days');
      const dateString = deliveryDate.format('dddd, MMMM D');

      const priceString = deliveryOption.priceCents === 0
      ? 'FREE'
      : `${formatCurrency(deliveryOption.priceCents)} -`;

      const isChecked= deliveryOption.id === cartItem.deliveryOptionId ;

  HTML+= ` 

      <div class="delivery-option js-delivery-option" 
      data-product-id="${matchingProduct.id}" 
      data-delivery-option-id = "${deliveryOption.id}">
        <input type="radio"
          ${isChecked ? 'checked' : ''}
          class="delivery-option-input"
          name="delivery-option-${matchingProduct.id}">
        <div>
          <div class="delivery-option-date">
            ${dateString}
          </div>
          <div class="delivery-option-price">
            ${priceString} Shipping
          </div>
        </div>
      </div>`
  });

  return HTML;
  }


  document.querySelectorAll('.js-delivery-option')
  .forEach((element)=>{
  element.addEventListener('click', ()=>{
    const { productId, deliveryOptionId} = element.dataset;
    updateDeliveryOption(productId, deliveryOptionId);
    renderOrderSummary();
    renderPaymentSummary();
  });
  });
}