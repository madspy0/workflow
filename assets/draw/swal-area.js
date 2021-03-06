import Swal from "sweetalert2";
import {Modify, Select, Snap} from "ol/interaction";
import {formatArea, formatLoadArea, itemStyles, map} from "./draw_map";
import {WKT} from "ol/format";
import {categoryForm} from "./category-form";
import {getArea} from "ol/sphere";
import Litepicker from "litepicker";
import {toggle_form} from "./toggle-form";
import {dropPlane} from "./drop-plane";
import {getCenter} from "ol/extent";
import Inputmask from "inputmask";

function clearBeforeClose(modify) {
    map.getInteractions().forEach(f => {
        if (f instanceof Select) {
            f.getFeatures().clear()
        }
    })
}

export function toastFire(error, rel = true) {
    Swal.fire({
        toast: true,
        position: 'top-right',
        iconColor: 'red',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        icon: 'error',
        title: error.error
    }).then(() => {
            if(rel) {
                setTimeout(() => {
                    window.location.reload()
                }, 2000)
            }
        }
    )
}

export async function swalArea(feature) {
    let modifyInteraction;
    let snapInteraction;
    map.getInteractions().forEach(f => {
        if (f instanceof Modify) {
            modifyInteraction = f
        }
        if (f instanceof Snap) {
            snapInteraction = f
        }
    })
    //let selected_center = getCenter(feature.getGeometry().getExtent());
    //let resolution = map.getView().getResolution();
    //console.log(selected_center[0] - 550 * resolution, selected_center[1], resolution)
    //map.getView().setCenter([selected_center[0] - 550 * resolution, selected_center[1]])
    map.getView().fit(feature.getGeometry(), {padding: [15, 565, 15, 15], duration: 500})
    if (feature.get('status') === 'created') {
        modifyInteraction.setActive(true)
        snapInteraction.setActive(true)
    } else {
        modifyInteraction.setActive(false)
        snapInteraction.set(false)
    }
    // RIGHT SIDEBAR
    let reqUrl;
    if (feature.get('number') === 'new') {
        reqUrl = '/dr_add'
    } else {
        reqUrl = '/dr_upd/' + feature.get('number')
    }
    document.body.style.cursor = 'progress'
    await fetch(reqUrl, {
        headers: new Headers({'content-type': 'application/json'}),
    })
        .then(response => {
            document.body.style.cursor = "default";
            if (!response.ok) {
                return response.json().then(Promise.reject.bind(Promise));
                //   throw new Error(response.statusText)
            }
            return (response.json())
        })
        .then(data => {
                Swal.fire({
                        title: '?????????????????????? ????????????????????',
                        html: data.content,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true,
                        customClass: {
                            htmlContainer: 'swalarea_html',
                            title: 'swalarea_title',
                            container: 'swalarea_container'
                        },
                        showCancelButton: true,
                        cancelButtonText: '??????????',
                        willOpen: () => {

                            Swal.getActions().insertAdjacentHTML('afterbegin', data.buttons);


                            if (feature.get('status') === 'created') {
                                //    toggle_form(document.drawn_area)
                                categoryForm();

                                document.getElementById('drawn_area_geom').value = new WKT().writeGeometry(feature.getGeometry());
                                document.getElementById('drawn_area_area').value = formatArea(feature.getGeometry());

                                new Litepicker({
                                    element: document.getElementById('drawn_area_solutedAt'),
                                    autoRefresh: true,
                                    lang: "uk-UA",
                                    format: "DD-MM-YYYY",
                                    maxDate: Date.now()
                                });

                            }
                            // document.getElementById('dr_close').addEventListener('click', () => {
                            //     Swal.clickCancel()
                            // })
                            Swal.getActions().querySelector('#dr_save').addEventListener('click', () => {
                                Swal.clickConfirm()
                            })
                            document.getElementById('dr_drop').addEventListener('click', (e) => {
                                e.preventDefault()
                                dropPlane(feature)
                            })
                            document.getElementById('dr_publ').addEventListener('click', (e) => {
                                e.preventDefault()
                                Swal.fire({
                                    title: "???? ?????????????????",
                                    text: "?????????? ???????????????????????? ???? ?????? ???? ???? ?????????????? ?????????????? ????????",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonText: '??????????????????????',
                                    cancelButtonText: '??????????',
                                    willClose: () => {
                                        clearBeforeClose();
                                    }
                                }).then(willPubl => {
                                    if (willPubl.isConfirmed) {
                                        document.body.style.cursor = "progress";
                                        fetch('/dr_publ/' + feature.get('number'), {
                                            headers: new Headers({'content-type': 'application/json'}),
                                        })
                                            .then(response => {
                                                document.body.style.cursor = "default";
                                                if (!response.ok) {
                                                    return response.json().then(Promise.reject.bind(Promise));
                                                    //   throw new Error(response.statusText)
                                                }
                                                return response.json()
                                            })
                                            .then(data => {
                                                if (data.success) {
                                                    feature.set('status', 'published');
                                                    feature.setStyle(itemStyles['published']);
                                                    feature.set('published', Date.now())
                                                    clearBeforeClose();
                                                    Swal.close()
                                                }
                                            })
                                            .catch(error => toastFire(error, false))
                                    }
                                })

                            })
                            document.getElementById('dr_arch').addEventListener('click', (e) => {
                                e.preventDefault()
                                let publdate = new Date(feature.get('published'));
                                if (Date.now() - publdate.getTime() < 24 * 3600 * 1000) {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-right',
                                        iconColor: 'red',
                                        showConfirmButton: false,
                                        timer: 3500,
                                        timerProgressBar: true,
                                        icon: 'error',
                                        title: '?????????????????????? ???? ???????????? ???????????????????????? ???? ???????????? ?????? ?????????? 24 ???????????? ?????????? ???????????????????????? ???? ?????????????????????? ??????????'
                                    })
                                    return;
                                }
                                document.body.style.cursor = "progress";
                                fetch('/dr_archground/' + feature.get('number'), {
                                    headers: new Headers({'content-type': 'application/json'}),
                                })
                                    .then(response => {
                                        document.body.style.cursor = "default";
                                        if (!response.ok) {
                                            return response.json().then(Promise.reject.bind(Promise));
                                            //   throw new Error(response.statusText)
                                        }
                                        return response.json()
                                    })
                                    .then(data => {
                                        if (data.content) {
                                            Swal.fire({
                                                title: '?????????????????????? ?? ?????????? ?????????????? ???????????????????????? ???? ??????',
                                                grow: 'column',
                                                html: data.content,
                                                showConfirmButton: true,
                                                showCloseButton: true,
                                                showCancelButton: true,
                                                confirmButtonText: '????????????????????',
                                                cancelButtonText: '??????????',
                                                willClose: () => {
                                                    clearBeforeClose();
                                                },
                                                //    buttonsStyling: false,
                                                willOpen: () => {
                                                    let cadnum = document.getElementById("archive_ground_gov_cadnum");
                                                    new Inputmask({
                                                        mask: "9{10}:9{2}:9{3}:9{4}",
                                                        placeholder: " "
                                                    }).mask(cadnum);
                                                    new Litepicker({
                                                        element: document.getElementById('archive_ground_gov_registrationAt'),
                                                        autoRefresh: true,
                                                        lang: "uk-UA",
                                                        format: "DD-MM-YYYY",
                                                        maxDate: Date.now()
                                                    });
                                                    new Litepicker({
                                                        element: document.getElementById('archive_ground_documentDate'),
                                                        autoRefresh: true,
                                                        lang: "uk-UA",
                                                        format: "DD-MM-YYYY",
                                                        maxDate: Date.now()
                                                    });
                                                    let groundForm = document.archive_ground;
                                                    let groundGovForm = document.archive_ground_gov;
                                                    let formCheck = document.getElementById('formCheck');
                                                    let formGovCheck = document.getElementById('formGovCheck');
                                                    toggle_form(groundForm)
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
                                                    document.body.style.cursor = "progress";
                                                    return fetch('/dr_archground/' + feature.get('number'), {
                                                        method: "POST",
                                                        body: checkGov.checked ?
                                                            new FormData(document.archive_ground_gov)
                                                            : new FormData(document.archive_ground)
                                                    }).then(response => {
                                                        document.body.style.cursor = "default";
                                                        if (!response.ok) {
                                                            return response.json().then(Promise.reject.bind(Promise));
                                                            //   throw new Error(response.statusText)
                                                        }
                                                        return response.json()
                                                    }).then((data) => {
                                                        if (data.success) {
                                                            feature.set('status', 'archived');
                                                            feature.setStyle(itemStyles['archived']);
                                                            clearBeforeClose();
                                                            Swal.close()
                                                        }
                                                    })
                                                        .catch(error => {
                                                            Swal.showValidationMessage(
                                                                `?????????????? ????????????: ${error.error}`
                                                            )
                                                        })
                                                }
                                            });

                                        }
                                    })
                                    .catch(error => toastFire(error))
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
                            let geom = feature.getGeometry();
                            formData.set('drawn_area[area]', getArea(geom))
                            formData.set('drawn_area[geom]', new WKT().writeGeometry(geom));
                            try {
                                for (let item of formData.entries()) {
                                    if (item[0] === "drawn_area[link]") {
                                        let url;
                                        try {
                                            url = new URL(item[1]);
                                        } catch (ev) {
                                            if (ev instanceof TypeError) {
                                                throw new Error('?????????????? ?????????????????? ???? ????????');
                                            }
                                        }
                                    }
                                    if (item[1] === "") {
                                        let msg;
                                        if (form.elements[item[0]].tagName.toLowerCase() === 'select') {
                                            msg = '???????????????? ?????????????? ??????????????????????'
                                        } else {
                                            msg = form.elements[item[0]].getAttribute('placeholder') ? form.elements[item[0]].getAttribute('placeholder')
                                                : form.elements[item[0]].labels[0].textContent;
                                        }
                                        throw new Error('???????? \'' + msg + '\' ???? ???????? ???????? ????????????????');
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
                                document.body.style.cursor = "default";
                                if (!response.ok) {
                                    return response.json().then(Promise.reject.bind(Promise));
                                    //   throw new Error(response.statusText)
                                }
                                return response.json()
                            }).then(data => {
                                if (data.area) {
                                    feature.set('area', formatLoadArea(data.area))
                                }
                                if (data.id) {
                                    feature.set('number', data.id);
                                    feature.set('appl', data.appl);
                                }

                                // let notificationToggler = document.getElementById('notifications-dropdown-toggle');
                                // let badge = notificationToggler.querySelectorAll('.icon-badge');
                                // if(!badge) {
                                //     badge = document.createElement('span');
                                //     badge.className = 'icon-badge';
                                // }
                                // badge.innerText = parseInt(badge.innerText) + 1;
                                // console.log(data, notificationToggler, badge)

                                clearBeforeClose()
                                Swal.close()
                            }).catch(error => {
                                Swal.showValidationMessage(
                                    `?????????????? ????????????: ${error.error}`
                                )
                            })
                        }
                    }
                )
            }
        ).catch(error => toastFire(error))
}
