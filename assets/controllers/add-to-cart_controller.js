import { Controller } from 'stimulus';
import Swal from 'sweetalert2';

export default class extends Controller {
    onSubmit(event) {
        event.preventDefault();
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: "L'article a bien été ajouté au panier",
            showConfirmButton: false,
            timer: 1500
          }).then((result) => {
            if (result) {
                this.element.submit()
            }
        })
    }
}