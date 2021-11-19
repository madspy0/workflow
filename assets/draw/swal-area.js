import Swal from "sweetalert2";

export async function swalArea(content, area) {

    // RIGHT SIDEBAR
    await Swal.fire({
            title: 'Атрибутивна інформація',
            html: content,
            position: 'top-end',
            showClass: {
                popup: `
      animate__animated
      animate__lightSpeedInRight
      animate__fadeInRight
      animate__faster
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
            //  toast: true,
            backdrop: false,

            preConfirm: () => {
                Swal.showLoading();
                let form = document.drawn_area;
                let formData = new FormData(form);
                formData.set('drawn_area[area]', area)
                try {
                    for (let item of formData.entries()) {
                        if (item[0] === "drawn_area[link]") {
                            let url;
                            try {
                                url = new URL(item[1]);
                            } catch (ev) {
                                if (ev instanceof TypeError) {
                                    throw new Error('Введіть посилання на сайт');
                                }
                            }
                        }
                        if (item[1] === "") {
                            throw new Error('Значення поля ' + item[0] + ' не може бути порожнім');
                        }
                    }
                } catch (e) {
                    Swal.showValidationMessage(
                        '<i class="fa fa-info-circle"></i>  ' + e.message
                    )
                    return Promise.resolve(false);
                }
                return fetch(form.action, {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    })
            }
        }
    )
}
