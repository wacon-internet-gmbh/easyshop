window.paypal
  .Buttons({
    style: {
      label: 'pay'
    },
    async createOrder() {
      let productData = JSON.parse(document.getElementById('paypal-button-container').getAttribute('data-product'));
      productData.amount = 1;
      let orderUrl = document.getElementById('paypal-button-container').getAttribute('data-order');

      const response = await fetch(orderUrl, {
          method: "POST",
          headers: {
              "Accept": "application/json",
              "Content-Type": "application/json",
          },
          body: JSON.stringify({
            tx_easyshop_order: {cart: [productData]}
          })
      });

      const order = await response.json();

      return order.id;
    },
    async onApprove(data, actions) {
      return actions.order.capture().then(function (details) {
        if (details.status === 'COMPLETED') {
          let successUrl = document.getElementById('paypal-button-container').getAttribute('data-success');

          if (successUrl) {
            window.location.href = successUrl;
          }
        } else {
          let errorUrl = document.getElementById('paypal-button-container').getAttribute('data-error');

          if (errorUrl) {
            window.location.href = errorUrl;
          }
        }
      });
    }
  })
  .render("#paypal-button-container");

// Example function to show a result to the user. Your site's UI library can be used instead.
function resultMessage(message) {
  const container = document.querySelector("#result-message");
  container.innerHTML = message;
}
