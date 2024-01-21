import './bootstrap';
import '../css/app.css';
// import styles
import '/public/assets/css/style.css';
import '/public/assets/css/components.css';
// import js
import '/public/assets/js/stisla.js';
import '/public/assets/js/custom.js';
import '/public/assets/js/scripts.js';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;

Alpine.start();

// mon code

// version jquery

$(document).ready(function () {
    let ajaxForm = $('form.ajax-form');

    ajaxForm.each(function () {
        $(this).on('submit', function (e) {
            e.preventDefault();
            let method = $(this).find('input[name="_method"]').val() || $(this).attr('method');
            let data = $(this).serialize();

            $.ajax({
                type: method,
                url: $(this).attr('action'),
                data: data,
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        let redirect = response.redirect || null;
                        handleSuccess(response.success, redirect);
                    }
                },
                error: function (xhr, status, err) {
                    handleErrors(xhr);
                }
            });
        });
    });
});

function handleSuccess(success, redirect) {
    Swal.fire({
        icon: 'success',
        title: 'Ok',
        html: success,
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value) {
            if (redirect) {
                window.location = redirect;
            }
        }
    })
}

function handleErrors(xhr) {
    switch (xhr.status) {
        case 422: // Erreur de validation
            let errorString = '';
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString += '<p>' + value + '</p>';
            });
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                html: errorString
            });
            break;

        case 404:
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Non trouvée.'
            });
            break;

        case 419:
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Jeton de sécurité invalide. Veuillez recharger la page.'
            }).then((result) => {
                if (result.value) {
                    window.location.reload(true);
                }
            });
            break;

        default:
            Swal.fire({
                icon: 'error',
                title: 'Erreur...',
                text: 'Une erreur s\'est produite. Cliquez pour recharger la page.'
            }).then((result) => {
                if (result.value) {
                    window.location.reload(true);
                }
            });
            break;
    }
}



// version js 
/*
document.addEventListener('DOMContentLoaded', function () {
    let ajaxForms = document.querySelectorAll('form.ajax-form');

    ajaxForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            let method = form.querySelector('input[name="_method"]')?.value || form.getAttribute('method');

            let formData = new FormData(form);
            alert(new URLSearchParams(formData).toString()); // Equivalent to serialise in jQuery
            fetch(form.getAttribute('action'), {
                method: method,
                body: formData
            });
        });
    });
});

*/
