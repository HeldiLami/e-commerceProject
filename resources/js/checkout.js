import { renderOrderSummary } from "./checkout/orderSummary.js";
import { renderPaymentSummary} from "./checkout/paymentSummary.js";

async function loadPage() {
  try {    
    renderOrderSummary();
    renderPaymentSummary();
  } catch (error) {
    console.log('Unexpected error:', error);
  }
}

loadPage();