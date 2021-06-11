import { Controller } from 'stimulus';
import Swal from 'sweetalert2';

export default class extends Controller {
    static values = {
      url: String,
    }

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
}