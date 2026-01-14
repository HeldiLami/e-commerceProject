<!doctype html>
<html lang="sq">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Card Checkout (Stripe Test)</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#eaeded;margin:0;}
    .wrap{max-width:720px;margin:30px auto;background:#fff;border:1px solid #d5d9d9;border-radius:10px;padding:18px;}
    label{display:block;margin:10px 0 6px;color:#565959;font-size:13px;font-weight:700}
    input{width:100%;padding:10px 12px;border:1px solid #d5d9d9;border-radius:8px;outline:none;}
    #card-element{padding:12px;border:1px solid #d5d9d9;border-radius:8px;}
    .btn{margin-top:14px;width:100%;padding:11px 12px;border:0;border-radius:8px;background:#febd69;font-weight:800;cursor:pointer;}
    .btn:hover{background:#f3a847;}
    .msg{margin-top:12px;padding:10px 12px;border-radius:8px;font-size:13px;}
    .ok{background:#e6f7ef;border:1px solid #9fe3c2;color:#067d3f;}
    .bad{background:#fde8e8;border:1px solid #f3b4b4;color:#a40000;}
    .note{color:#565959;font-size:12px;margin-top:10px;}
  </style>
</head>
<body>
  <div class="wrap">
    <h2 style="margin:0 0 6px;">Stripe Test Checkout</h2>
    <div class="note">
      Përdor “test card” p.sh. 4242 4242 4242 4242, çdo CVC, çdo datë në të ardhmen.
    </div>

    <label>Amount (cents)</label>
    <input id="amount" value="1999" />

    <label>Currency</label>
    <input id="currency" value="EUR" />

    <label>Card details</label>
    <div id="card-element"></div>

    <button class="btn" id="payBtn">Pay (Test)</button>
    <div id="result"></div>
  </div>

  <script src="https://js.stripe.com/v3/"></script>
  <script>
    const stripe = Stripe(@json($stripeKey));
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const resultEl = document.getElementById('result');

    function showMsg(type, text){
      resultEl.innerHTML = `<div class="msg ${type}">${text}</div>`;
    }

    document.getElementById('payBtn').addEventListener('click', async () => {
      try {
        const amount = parseInt(document.getElementById('amount').value, 10);
        const currency = document.getElementById('currency').value;

        // 1) create intent (backend)
        const r1 = await fetch(@json(route('checkout.intent')), {
          method: 'POST',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN': csrf},
          body: JSON.stringify({ amount, currency })
        });
        const d1 = await r1.json();
        if (!r1.ok) {
          showMsg('bad', 'Create intent failed');
          return;
        }

        // 2) confirm card payment (frontend -> Stripe)
        const { paymentIntent, error } = await stripe.confirmCardPayment(d1.clientSecret, {
          payment_method: { card }
        });

        if (error) {
          showMsg('bad', error.message || 'Payment failed');
          return;
        }

        // 3) mark succeeded + save last4/brand (backend)
        const r2 = await fetch(@json(route('checkout.confirm')), {
          method: 'POST',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN': csrf},
          body: JSON.stringify({ payment_intent_id: paymentIntent.id })
        });
        const d2 = await r2.json();

        if (!r2.ok) {
          showMsg('bad', 'Could not save payment locally');
          return;
        }

        showMsg('ok', `Success! Saved locally. Status: ${d2.status}`);
      } catch (e) {
        showMsg('bad', 'Unexpected error');
      }
    });
  </script>
</body>
</html>
