import { Controller } from 'stimulus';
import Swal from 'sweetalert2';

export default class extends Controller {
    onAdjustSubmit(event) {
        event.preventDefault();
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: "Votre panier va être mis à jour",
            showConfirmButton: false,
            timer: 1500
          }).then((result) => {
            if (result) {
                this.element.submit()
            }
        })
    }

    onRemoveSubmit(event) {
        event.preventDefault();
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success',
              cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
          })
          
          swalWithBootstrapButtons.fire({
            title: 'Êtes vous sûr?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Non, annuler!',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
              swalWithBootstrapButtons.fire(
                'Supprimé!',
                'Votre article a bien été supprimé du panier.',
                'success'
              )
              .then(setTimeout(() => this.element.submit(), 1000))
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
              swalWithBootstrapButtons.fire(
                'Annulé',
                "Votre article n'a pas été supprimé :)",
                'error',
              )
            }
          })
    }
}