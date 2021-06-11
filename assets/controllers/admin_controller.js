import { Controller } from 'stimulus';
import Swal from 'sweetalert2';

export default class extends Controller {
    
    onEdit(event) {
      event.preventDefault();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: "Mise à jour effectuée",
          showConfirmButton: false,
          timer: 1500
        }).then((result) => {
          if (result) {
              this.element.submit()
          }
      })
    }

    onCreate(event) {
      event.preventDefault();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: "Création terminée",
          showConfirmButton: false,
          timer: 1500
        }).then((result) => {
          if (result) {
              this.element.submit()
          }
      })
    }
}