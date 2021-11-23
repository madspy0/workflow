import Swal from "sweetalert2";
import {Modify, Select} from "ol/interaction";
import {formatArea, itemStyles, map} from "./draw_map";
import {WKT} from "ol/format";
import {categoryForm} from "./category-form";
import {getArea} from "ol/sphere";
import Litepicker from "litepicker";
import {toggle_form} from "./toggle-form";
import {Collection} from "ol";
import {dropPlane} from "./drop-plane";

function clearBeforeClose(modify) {
    map.getInteractions().forEach(f => {
        if (f instanceof Select) {
            f.getFeatures().clear()
        }
    })
}


export async function swalArea(feature) {
    let modifyInteraction
    map.getInteractions().forEach(f => {
        if (f instanceof Modify) {
            modifyInteraction = f
        }
    })
    if (feature.get('status') === 'created') {
        modifyInteraction.setActive(true)
    } else {
        modifyInteraction.setActive(false)
    }
    // RIGHT SIDEBAR
    let reqUrl;
    if (feature.get('number') === 'new') {
        reqUrl = '/dr_add'
    } else {
        reqUrl = '/dr_upd/' + feature.get('number')
    }

    await fetch(reqUrl)
        .then(response => response.json())
        .then((data) => {
                Swal.fire({
                        title: 'Атрибутивна інформація',
                        html: data.content,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true,
                        showCancelButton: true,
                        willOpen: () => {

                            Swal.getActions().insertAdjacentHTML('afterbegin', data.buttons);

                        //    categoryForm();
                            if (feature.get('status') !== 'created') {
                                toggle_form(document.drawn_area)
                            }
                            document.getElementById('drawn_area_geom').value = new WKT().writeGeometry(feature.getGeometry());
                            document.getElementById('drawn_area_area').value = formatArea(feature.getGeometry());
                            new Litepicker({
                                element: document.getElementById('drawn_area_solutedAt'),
                                autoRefresh: true,
                                lang: "uk-UA",
                                format: "DD-MM-YYYY"
                            });
                            // document.getElementById('dr_close').addEventListener('click', () => {
                            //     Swal.clickCancel()
                            // })
                            document.getElementById('dr_save').addEventListener('click', () => {
                                Swal.clickConfirm()
                            })
                            document.getElementById('dr_drop').addEventListener('click', () => {
                                dropPlane(feature)
                            })
                            document.getElementById('dr_publ').addEventListener('click', (e) => {
                                e.preventDefault()
                                Swal.fire({
                                    title: "Ви впевнені?",
                                    text: "Після публікації ви не зможете змінити дані",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonText: 'Опублікувати',
                                    cancelButtonText: 'Скасувати',
                                    willClose: () => {
                                        clearBeforeClose();
                                    }
                                }).then(willPubl => {
                                    if (willPubl.isConfirmed) {
                                        fetch('/dr_publ/' + feature.get('number'))
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    feature.set('status', 'published');
                                                    feature.setStyle(itemStyles['published']);
                                                    clearBeforeClose();
                                                    Swal.close()
                                                }
                                            })
                                    }
                                })

                            })
                            document.getElementById('dr_arch').addEventListener('click', (e) => {
                                e.preventDefault()
                                fetch('/dr_archground/' + feature.get('number'))
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.content) {
                                            Swal.fire({
                                                title: 'Перенесення в архів ділянки відображеної на ПКК',
                                                grow: 'column',
                                                html: data.content,
                                                showConfirmButton: true,
                                                showCloseButton: true,
                                                showCancelButton: true,
                                                willClose: () => {
                                                    clearBeforeClose();
                                                },
                                                //    buttonsStyling: false,
                                                willOpen: () => {
                                                    new Litepicker({
                                                        element: document.getElementById('archive_ground_gov_registrationAt'),
                                                        autoRefresh: true,
                                                        lang: "uk-UA",
                                                        format: "DD-MM-YYYY"
                                                    });
                                                    new Litepicker({
                                                        element: document.getElementById('archive_ground_documentDate'),
                                                        autoRefresh: true,
                                                        lang: "uk-UA",
                                                        format: "DD-MM-YYYY"
                                                    });
                                                    let groundForm = document.archive_ground;
                                                    let groundGovForm = document.archive_ground_gov;
                                                    let formCheck = document.getElementById('formCheck');
                                                    let formGovCheck = document.getElementById('formGovCheck');
                                                //    toggle_form(groundForm)
                                                    formCheck.addEventListener('change', () => {
                                                        toggle_form(groundForm);
                                                        toggle_form(groundGovForm);
                                                    })
                                                    formGovCheck.addEventListener('change', () => {
                                                        toggle_form(groundForm);
                                                        toggle_form(groundGovForm);
                                                    })
                                                },
                                                preConfirm: () => {
                                                    let checkGov = document.getElementById('formGovCheck')

                                                    return fetch('/dr_archground/' + feature.get('number'), {
                                                        method: "POST",
                                                        body: checkGov.checked ?
                                                            new FormData(document.archive_ground_gov)
                                                            : new FormData(document.archive_ground)
                                                    }).then(response => {
                                                        if (!response.ok) {
                                                            throw new Error(response.statusText)
                                                        }
                                                        return response.json()
                                                    }).then(data => {
                                                        feature.set('status', 'archived');
                                                        feature.setStyle(itemStyles['archived']);
                                                    })
                                                        .catch(error => {
                                                            Swal.showValidationMessage(
                                                                `Request failed: ${error}`
                                                            )
                                                        })
                                                }
                                            });

                                        }
                                    })
                            })
                        },
                        willClose: () => {
                            clearBeforeClose();
                        },
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
                        //  toast: true,
                        backdrop: false,

                        preConfirm: () => {
                            Swal.showLoading();
                            let form = document.drawn_area;
                            let formData = new FormData(form);
                            formData.set('drawn_area[area]', getArea(feature.getGeometry()))
                            try {
                                for (let item of formData.entries()) {
                                    if (item[0] === "drawn_area[link]") {
                                        console.log(form.elements[item[0]].labels[0].textContent)
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
                            }).then(data => {
                                if (data.id) {
                                    feature.set('number', data.id);
                                    feature.set('appl', data.appl);
                                    clearBeforeClose()
                                    Swal.close()
                                }
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
        )
}
