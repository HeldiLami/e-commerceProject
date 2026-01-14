import { renderOrderSummary } from "./checkout/orderSummary.js";
import { renderPaymentSummary} from "./checkout/paymentSummary.js";

async function loadPage() {
  try {
  } catch (error) {
    console.log('Unexpected error:', error);
  }

  renderOrderSummary();
  renderPaymentSummary();
}