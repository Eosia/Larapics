import './bootstrap';
import '../css/app.css';
import '/public/assets/css/style.css';
import '/public/assets/css/components.css';
import '/public/assets/js/stisla.js';
import '/public/assets/js/custom.js';
import '/public/assets/js/scripts.js';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
Alpine.start();

// Fonctions d'assistance
function handleSuccess(success, redirect) {
    Swal.fire({
        icon: 'success',
        title: 'Succès',
        html: success,
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value && redirect) {
            window.location.href = redirect;
        }
    });
}

function handleErrors(xhr) {
    let errorString = '';
    if (xhr.responseJSON && xhr.responseJSON.errors) {
        $.each(xhr.responseJSON.errors, function (key, value) {
            errorString += '<p>' + value + '</p>';
        });
    } else {
        errorString = 'Une erreur s\'est produite. Veuillez réessayer.';
    }

    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        html: errorString
    });
}

$(document).ready(function () {
    // Gestion des formulaires avec fichiers
    $('form.withFile').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let method = form.find('input[name="_method"]').val() || form.attr('method');
        let data = new FormData(this);
        let button = form.find('button');
        button.prop('disabled', true);

        // Configuration pour la requête avec fichier
        axios({
            url: url,
            method: method,
            data: data,
            responseType: 'json',
            processData: false,
            contentType: false,
            onUploadProgress: function (e) {
                // Logique de progression de l'upload
            }
        }).then(function (response) {
            handleSuccess(response.data.success, response.data.redirect);
        }).catch(function (error) {
            handleErrors(error.response);
            button.prop('disabled', false);
        });
    });

    // Gestion des votes
    $('a.vote').on('click', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                handleSuccess(response.success, response.redirect);
            },
            error: function (xhr) {
                handleErrors(xhr);
            }
        });
    });

    // Gestion des formulaires AJAX
    $('form.ajax-form').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let method = form.find('input[name="_method"]').val() || form.attr('method');
        let data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function (response) {
                handleSuccess(response.success, response.redirect);
            },
            error: function (xhr) {
                handleErrors(xhr);
            }
        });
    });

    // Gestion de la suppression avec confirmation SweetAlert2
    $('form.destroy').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let method = form.find('input[name="_method"]').val() || form.attr('method');

        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: 'Veuillez confirmer la suppression',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: method,
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        handleSuccess(response.success, response.redirect);
                    },
                    error: function (xhr) {
                        handleErrors(xhr);
                    }
                });
            }
        });
    });
});
