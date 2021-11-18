import Swal from "sweetalert2";
export async function swalArea(content) {
    // RIGHT SIDEBAR
    await Swal.fire({
        title: 'Атрибутивна інформація',
        html: content,
        position: 'top-end',
        showClass: {
            popup: `
      animate__animated
      animate__lightSpeedInRight
      // animate__fadeInRight
      // animate__faster
    `
        },
        hideClass: {
            popup: `
      animate__animated
      animate__fadeOutRight
      animate__faster
    `
        },
        grow: 'column',
        width: 550,
        showConfirmButton: false,
        showCloseButton: true,
        toast: true
    })
}
