import Swal from "sweetalert2";
import {toastFire} from "./swal-area";

export let swal_person = () => {
    document.body.style.cursor = "progress";
    fetch('/dr_profile/')
        .then(response => {
            document.body.style.cursor = "default";
            if (!response.ok) {
                return response.json().then(Promise.reject.bind(Promise));
                //   throw new Error(response.statusText)
            }
            return (response.json())
        })
        .then((data) => {
            Swal.fire({
                title: 'Профіль користувача',
                html: data.content,
                showCancelButton: true,
                confirmButtonText: 'Зберегти',
                cancelButtonText: 'Вихід',
                customClass: {
                    htmlContainer: 'swalarea_html',
                    title: 'swalarea_title',
                    // container: 'swalarea_container'
                },
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
                                `Помилка запиту: ${error.error}`
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
        }).catch(error => toastFire(error))
}

