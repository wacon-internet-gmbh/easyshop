window.paypal
  .Buttons({
    style: {
      label: 'pay'
    },
    async createOrder() {
      let productData = JSON.parse(document.getElementById('paypal-button-container').getAttribute('data-product'));
      productData.amount = 1;
      let checkoutUrl = document.getElementById('paypal-button-container').getAttribute('data-checkout');
      const response = await fetch(checkoutUrl, {
          method: "POST",
          headers: {
              "Content-Type": "application/json",
          },
          body: JSON.stringify({
              cart: [productData]
          })
      });

      const order = await response.json();
      console.debug(order);
      return order.id;
  }
  })
  .render("#paypal-button-container");

// Example function to show a result to the user. Your site's UI library can be used instead.
function resultMessage(message) {
  const container = document.querySelector("#result-message");
  container.innerHTML = message;
}
