const EasyshopOrderForm = {
  init: function () {
    this.previousStepButton();
  },

  previousStepButton: function() {
    let link = document.getElementById("easyshopOrderFormPreviousLink");

    if (link) {
      link.addEventListener("click", (event) => {
        event.preventDefault();
        let formPrevious = event.target.closest("form").parentElement.querySelector(".form-previous");

        if (formPrevious) {
          formPrevious.submit();
        }
      });
    }
  }
};

document.addEventListener("DOMContentLoaded", () => {
  EasyshopOrderForm.init();
});

