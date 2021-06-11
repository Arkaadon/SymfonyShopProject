import { Controller } from 'stimulus';
import Swal from 'sweetalert2';

export default class extends Controller {
    connect() {
        console.log('in connect')
        if (window.location.href === 'https://127.0.0.1:8000/magasin/dbInitialize') {
          console.log('in condition')
        Swal.fire({
            title: 'La base de donnée est vide',
            showCancelButton: true,
            confirmButtonText: 'Intégrer des données temporaires',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                window.location.replace('https://127.0.0.1:8000/magasin/dbInitialize/true');
            },
            allowOutsideClick: () => !Swal.isLoading()
          })
      }
    }
}