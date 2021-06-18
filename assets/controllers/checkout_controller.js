import { Controller } from 'stimulus';
import Swal from 'sweetalert2';

export default class extends Controller {

    stripe
    connect() {
      if (window.location.href === 'https://127.0.0.1:8000/checkout/1') {
        this.CodeCorrect();
      }
      else if (window.location.href == 'https://127.0.0.1:8000/checkout/!') {
        this.CodeAlreadyUsed();
      }
      else if (window.location.href == 'https://127.0.0.1:8000/checkout/2') {
        this.CodeIncorrect();
      }
    }
  
    onDiscountSubmit(event) {
        event.preventDefault();
        this.element.submit()
    }

    CodeCorrect() {
      Swal.fire({
        position: 'center',
        icon: 'success',
        title: "Code promotionnel utilisé",
        showConfirmButton: false,
        timer: 1500
      })
    }

    CodeAlreadyUsed() {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: "Vous avez déjà utilisé un code promotionnel",
        showConfirmButton: false,
        timer: 1500
      })
    }

    CodeIncorrect() {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: "Code promotionnel invalide",
        showConfirmButton: false,
        timer: 1500
      })
    }

    Payment(event) {
      var stripe = Stripe("pk_test_51J3GoYLOoq2f2CBESlR32eDqPbM51q8kE4NhX41M6eol943v3DkqJJGKuxmCDrh6ZkOk6DgAmfFoeloVkuWyVW2j00koEV4w0t");
    
        fetch(event.currentTarget.getAttribute('path'), {method: "POST"}).then(function (response) {
          return response.json();
        }).then(function (session) {
          console.log({sessionId: session.id});
          return stripe.redirectToCheckout({sessionId: session.id});
        }).then(function (result) {
          // If redirectToCheckout fails due to a browser or network
          // error, you should display the localized error message to your
          // customer using error.message.
          if (result.error) {
            alert(result.error.message);
          }
        }).catch(function (error) {
          console.error("Error:", error);
        });
    }
}