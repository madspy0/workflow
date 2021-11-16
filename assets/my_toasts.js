import Litepicker from "litepicker";
import {Toast} from "bootstrap";
import {defaultStyle, formatArea, plants, sourceClear, itemStyles, drawLayer} from "./draw/draw_map";
import {Draw, Modify, Select} from "ol/interaction";
import {clickInfo} from "./click-info";
import {WKT} from "ol/format";
import Swal from "sweetalert2";
import {getArea} from 'ol/sphere';
import {Fill, Stroke, Style} from "ol/style";

export function my_toast(content, selected = null, map = null, action = null) {
    let clear = document.getElementById('draw_toast');
    if (clear) {
        clear.remove();
    }
    let mod = document.createElement("div");
    //mod.innerHTML(this.response.content);
    mod.id = 'draw_toast';
    mod.className = "toast position-absolute top-0 end-0 side-in";
    mod.style.zIndex = "1050";
//    mod.style.display="flex";
//    mod.style.flexWrap='wrap';
//    mod.style.flexDirection = "column";
    mod.setAttribute("role", "dialog");
    mod.setAttribute("aria-live", "assertive");
    mod.setAttribute("aria-atomic", "true");
    mod.setAttribute("data-bs-autohide", false);
    mod.insertAdjacentHTML('beforeend', content);
    document.body.appendChild(mod);
    window.disableLitepickerStyles = true;
    const picker = new Litepicker({
        element: document.getElementById('drawn_area_solutedAt'),
        autoRefresh: true,
        lang: "uk-UA",
        format: "DD-MM-YYYY"
    });
    if (selected && selected.getGeometry()) {
        document.getElementById('drawn_area_geom').value = new WKT().writeGeometry(selected.getGeometry());
    }
    let myToast = Toast.getOrCreateInstance(document.getElementById('draw_toast'), {delay: 500, animation: true});
    myToast.show();
    let form = document.drawn_area;

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            let xhr = new XMLHttpRequest();
            let formData = new FormData(form);
            formData.set('drawn_area[area]', getArea(selected.getGeometry()));
            xhr.open("POST", form.action, true);
            //        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (this.readyState != 4) return;
                if (xhr.status === 200) {
                    if(action) {
                        // let rr = JSON.parse(xhr.response);
                        // selected.set('status','created');
                        // selected.set('number', rr['id'])
                        // selected.set('appl', document.getElementById('drawn_area_lastname').value +
                        // ' ' + document.getElementById('drawn_area_firstname').value);
                        // selected.setStyle(itemStyles[selected.get('status')]);
                        // plants.getSource().addFeature(selected);
                        // // plants.changed();
                        // // selected.changed()
                        plants.getSource().refresh();

                    }
                    //                   sourceClear(true);
                    //                   let myToast = Toast.getInstance(document.getElementById('draw_toast'));
                    //plants.getSource().refresh();
                    myToast.hide();
                    form.reset();
                } else {
                    console.log(xhr)
                }
            }
            xhr.send(formData);
        })
    }
    // let geom_button = document.getElementById('change_geom');
    // if (geom_button) {
    //     geom_button.addEventListener('click', function (e) {

    // let closeButtons = document.getElementsByClassName('mytoast-hide');
    // for (let element of closeButtons) {
    //     element.addEventListener('click', function (e) {
    //         e.preventDefault();
    //         myToast.hide();
    //         plants.getSource().refresh();
    //     })
    // }


    mod.addEventListener('hidden.bs.toast', function () {
        //sourceClear(true);
        // map.getLayers().forEach(layer => {
        //     if (layer.get('name') === 'plants') {
        //         layer.getSource().refresh()
        //     }
        // });
        //plants.getSource().refresh();
        map.getInteractions().forEach(function (interaction) {
            if (interaction instanceof Select) {
                interaction.getFeatures().clear();
            }
            if (interaction instanceof Draw) {
                drawLayer.getSource().clear();
            }
        }, this);
        let edit_buttons = document.getElementsByClassName('btn-edit');
        for (let element of edit_buttons) {
            element.removeAttribute('disabled')
        }
        if (action) {
            // если добавление
            edit_buttons[1].dispatchEvent(new Event("click"));
            edit_buttons[1].dispatchEvent(new Event("click"));
            // edit_buttons[1].classList.add('active')
        } else {
            //    edit_buttons[0].classList.add('active')
            edit_buttons[0].dispatchEvent(new Event("click"));
            edit_buttons[0].dispatchEvent(new Event("click"));
        }
    })

    mod.addEventListener('shown.bs.toast', function () {
        if (!selected) {
            return;
        }
        let edit_buttons = document.getElementsByClassName('btn-edit');
        for (let element of edit_buttons) {
            element.disabled = true
        }
        selected.setStyle(defaultStyle);
        let select = new Select({
            //some options
        });
        map.addInteraction(select);
        let selected_collection = select.getFeatures();
        if ((selected.get('status') !== 'published') && (selected.get('status') !== 'archived')) {
            selected_collection.push(selected);
        }

//         let selected_center = getCenter(selected.getGeometry().getExtent());
//         let resolution = map.getView().getResolution();
// console.log(selected_center[0] - 550*resolution, selected_center[1], resolution)
        //    map.getView().setCenter([selected_center[0] - 550*resolution, selected_center[1]])
        map.getView().fit(selected.getGeometry(), {padding: [15, 565, 15, 15], duration: 500})
        map.removeInteraction(select);
        const modify = new Modify({
            features: selected_collection,

            //   source: plants.getSource()
        });
        map.addInteraction(modify);
        clickInfo(map);
        modify.on('modifystart', function (e) {
            let sketch = e.features.getArray()[0];
            sketch.getGeometry().on('change', function (evt) {
                document.getElementById('drawn_area_area').value = formatArea(evt.target);
            });
        })
        modify.on("modifyend", function (e) {
            document.getElementById('drawn_area_area').value = formatArea(e.features.getArray()[0].getGeometry(), false);
            document.getElementById('drawn_area_geom').value = new WKT().writeGeometry(e.features.getArray()[0].getGeometry());
        })
    })
    // document.getElementById('toast-close').addEventListener('click', (e) => {
    //     e.preventDefault();
    //     sourceClear();
    // })
    document.getElementById('dr_publ').addEventListener('click', (e) => {
        e.preventDefault();

        let myToast = Toast.getOrCreateInstance(document.getElementById('draw_toast'), {
            delay: 500,
            animation: true
        });
        // myToast.hide();
        Swal.fire({
            title: "Ви впевнені?",
            text: "Після публікації ви не зможете змінити дані",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Опублікувати',
            cancelButtonText: 'Скасувати'
        })
            .then((willPublic) => {
                if (willPublic.isConfirmed) {
                    let Request = new XMLHttpRequest();
                    Request.open('get', '/dr_publ/' + e.target.value);
                    Request.send();
                    Request.onreadystatechange = function () {
                        document.body.style.cursor = "progress";
                        if (Request.readyState == 3) {
                            // загрузка
                        }
                        if (Request.readyState == 4) {
                            // запрос завершён
                            document.body.style.cursor = "default";
                            //   sourceClear(true);

                            selected.set('status','published');
//                            plants.getSource().changed();
                            selected.setStyle(itemStyles['published'])

                            myToast.hide();
                            Swal.fire({
                                text: "Дані опубліковані",
                                icon: "success",
                            });

                        }
                    }
                }
            })
    })
    document.getElementById('dr_arch').addEventListener('click', (e) => {
        e.preventDefault();

        let myToast = Toast.getOrCreateInstance(document.getElementById('draw_toast'), {
            delay: 500,
            animation: true
        });
        // myToast.hide();
        Swal.fire({
            title: "Ви впевнені?",
            text: "Після внесення до архіву ви не зможете змінити дані",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Архівувати',
            cancelButtonText: 'Скасувати'
        })
            .then((willPublic) => {
                if (willPublic.isConfirmed) {
                    let Request = new XMLHttpRequest();
                    Request.open('get', '/dr_arch/' + e.target.value);
                    Request.send();
                    Request.onreadystatechange = function () {
                        document.body.style.cursor = "progress";
                        if (Request.readyState == 3) {
                            // загрузка
                        }
                        if (Request.readyState == 4) {
                            // запрос завершён
                            document.body.style.cursor = "default";
                            //   sourceClear(true);

                            selected.set('status','archived');
//                            plants.getSource().changed();
                            selected.setStyle(itemStyles['archived'])

                            myToast.hide();
                            Swal.fire({
                                text: "Дані архівовані",
                                icon: "success",
                            });

                        }
                    }
                }
            })
    })
    document.getElementById('dr_drop').addEventListener('click', (e) => {
        e.preventDefault();
        let myToast = Toast.getOrCreateInstance(document.getElementById('draw_toast'), {
            delay: 500,
            animation: true
        });
        myToast.hide();
        Swal.fire({
            title: "Ви впевнені?",
            text: "Після видалення ви не зможете відновити дані на цю ділянку",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Видалити',
            cancelButtonText: 'Скасувати'
        })
            .then((willDelete) => {
                if (willDelete.isConfirmed) {
                    let Request = new XMLHttpRequest();
                    Request.open('get', '/dr_drop/' + e.target.value);
                    Request.send();
                    Request.onreadystatechange = function () {
                        document.body.style.cursor = "progress";
                        if (Request.readyState == 3) {
                            plants.getSource().removeFeature(selected);
                        }
                        if (Request.readyState == 4) {
                            // запрос завершён
                            document.body.style.cursor = "default";
                            Swal.fire({
                                text: "Дані видалені",
                                icon: "success",
                            });
                            map.getInteractions().forEach(function (interaction) {
                                if (interaction instanceof Select) {
                                    interaction.getFeatures().clear();
                                }
                            }, this);
                        }
                    }
                }
            });
    })

    document.getElementById('drawn_area_useCategory').addEventListener('change', event => {
        let Request = new XMLHttpRequest();
        Request.open('get', '/sub?category=' + document.getElementById(event.target.id).value);
        Request.send();
        Request.onreadystatechange = function () {
            document.body.style.cursor = "progress";
            if (Request.readyState == 3) {
                // загрузка
            }
            if (Request.readyState == 4) {
                // запрос завершён
                document.body.style.cursor = "default";
                let options = document.querySelectorAll('#drawn_area_useSubCategory option');
                options.forEach(o => o.remove());
                let opt = document.createElement('option');
                opt.value = null;
                opt.innerHTML = "Оберіть субкатегорію";
                document.getElementById('drawn_area_useSubCategory').appendChild(opt);
                let arr = JSON.parse(Request.responseText);
                arr.forEach(function (item, i) {
                    let opt = document.createElement('option');
                    opt.value = item.id;
                    opt.innerHTML = item.name;
                    document.getElementById('drawn_area_useSubCategory').appendChild(opt);
                })
                //    console.log(Request.responseText);;
            }
        }
    })
}
