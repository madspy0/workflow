import Swal from "sweetalert2";

function archiveGroundForms(data) {
    Swal.fire({
        title: 'Підстави',
        grow: 'column',
        html: data.content
    });
}
