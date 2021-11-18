import Swal from "sweetalert2";

export let swal_person = () => {
    fetch('/dr_profile/')
        .then(response => response.json())
        .then((data) => {
            Swal.fire({
                title: 'Профіль користувача',
                html: data.content,
                showCancelButton: true,
                confirmButtonText: 'Зберегти',
                cancelButtonText: 'Скасувати',
                preConfirm: () => {
                    let form = document.getElementById('form_person_edit');
                    let formData = new FormData(form);
                    try {
                        for (let item of formData.entries()) {
                            if (item[0] === "profile[url]") {
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
                        return false;
                    }
                    return fetch(`/dr_profile`, {
                        method: 'POST',
                        // headers: {
                        //     'Content-Type': 'application/json;charset=utf-8'
                        // },
                        body: formData
                    })
                        .then(response => {
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
                },
            //    allowOutsideClick: () => !Swal.isLoading()
            })
            //     .then((result) => {
            //     if (result.isConfirmed) {
            //         Swal.fire({
            //             title: `${result.value.login}'s avatar`,
            //             imageUrl: result.value.avatar_url
            //         })
            //     }
            // })
        })
}

