
/* global angular */

try {
    var $validator = jQuery("#addForm").validate({
        lang: 'tr',
        highlight: function (formElement, label) {
            jQuery(label).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (label, formElement) {
            jQuery(label).closest('.form-group').removeClass('has-error');
            $(label).remove();
        }
    });
} catch (e) {
    // console.log("Erreur", e);
}


/**
 * Creation ou modification
 *
 * @param string url Lien
 * @param object $formObject
 * @param string formData données serializées à envoyer
 * @param object $ajoutLoader
 * @param object $table L'objet bootstrap-table
 * @param boolean ajout determine si c'est un ajout ou une modification
 * @returns null
 */

function editerAction(methode, url, $formObject, formData, $overlayBlock, $spinnerLg, $table) {
    jQuery.ajax({
        type: methode,
        url: url,
        cache: false,
        data: formData,
        success:function (response, textStatus, xhr){
            if (response.code === 1) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: response.msg,
                    showConfirmButton: false,
                    timer: 2500
                });
                $table.bootstrapTable('refresh');
                document.forms["formAjout"].reset();
            }
            if (response.code === 0) {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: response.msg,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
         },
        error: function (err) {
            var res = eval('('+err.responseText+')');
            Swal.fire({
                position: "center",
                icon: "error",
                title: res.message,
                showConfirmButton: false,
                timer: 3000
            });
        },
        beforeSend: function () {
            $overlayBlock.addClass('overlay');
            $spinnerLg.addClass('spinner');
        },
        complete: function () {
            $overlayBlock.removeClass('overlay');
            $spinnerLg.removeClass('spinner');
        },
    });
}

//Delet action
function deleteAction(url, formData, $overlayBlock, $spinnerLg, $table) {
    jQuery.ajax({
        type: "DELETE",
        url: url,
        cache: false,
        data: formData,
        success: function (response) {
            if (response.code === 1) {
                $(".bs-modal-supprimer").modal("hide");
                $table.bootstrapTable('refresh');
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: response.msg,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
            if (response.code === 0) {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: response.msg,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        },
        error: function (err) {
            var res = eval('('+err.responseText+')');
            Swal.fire({
                position: "center",
                icon: "error",
                title: res.message,
                showConfirmButton: false,
                timer: 3000
            });
        },
        beforeSend: function () {
            $overlayBlock.addClass('overlay');
            $spinnerLg.addClass('spinner');
        },
        complete: function () {
            $overlayBlock.removeClass('overlay');
            $spinnerLg.removeClass('spinner');
        }
    });
}


