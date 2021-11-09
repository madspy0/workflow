import Litepicker from "litepicker";
import {Toast} from "bootstrap";
import {defaultStyle, formatArea, sourceClear} from "./draw/draw_map";
import {Modify, Select} from "ol/interaction";
import {clickInfo} from "./click-info";
import {Fill, Stroke, Style} from "ol/style";
import {WKT} from "ol/format";
import {getCenter} from 'ol/extent';
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
    let myToast = Toast.getOrCreateInstance(document.getElementById('draw_toast'), {delay:500, animation: true});
    myToast.show();
    let form = document.forms[0];

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            let xhr = new XMLHttpRequest();
            let formData = new FormData(form);
            formData.append('drawn_area[area]', document.getElementById('drawn_area_area').value);
            xhr.open("POST", form.action, true);
            //        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (this.readyState != 4) return;
                if (xhr.status === 200) {
                    sourceClear(true);
                    let myToast = Toast.getInstance(document.getElementById('draw_toast'));
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

    mod.addEventListener('hidden.bs.toast', function () {
        map.getInteractions().forEach((interaction) => {
            if ((interaction instanceof Select) || (interaction instanceof Modify)) {
                interaction.setActive(false);
            }
        });
        sourceClear(true);
        // map.getLayers().forEach(layer => {
        //     if (layer.get('name') === 'plants') {
        //         layer.getSource().refresh()
        //     }
        // });
        let edit_buttons = document.getElementsByClassName('btn-edit');
        if(action) {
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
        if(!selected) {return;}
        selected.setStyle(defaultStyle);
        let select = new Select({
            //some options
        });
        map.addInteraction(select);
        let selected_collection = select.getFeatures();
        selected_collection.push(selected);

//         let selected_center = getCenter(selected.getGeometry().getExtent());
//         let resolution = map.getView().getResolution();
// console.log(selected_center[0] - 550*resolution, selected_center[1], resolution)
    //    map.getView().setCenter([selected_center[0] - 550*resolution, selected_center[1]])
        map.getView().fit(selected.getGeometry(),  {padding: [15, 565, 15, 15], duration: 500})
        map.removeInteraction(select);
        const modify = new Modify({
            features: selected_collection,
        });
        map.addInteraction(modify);
        clickInfo(map, false);
        modify.on('modifystart', function(e) {
            let sketch = e.features.getArray()[0];
            let listener = sketch.getGeometry().on('change', function (evt) {
                const geom = evt.target;
                let output;
                document.getElementById('drawn_area_area').value = formatArea(geom);
            });
        })
        modify.on("modifyend", function (e) {
            document.getElementById('drawn_area_area').value = formatArea(e.features.getArray()[0].getGeometry(), false);
            document.getElementById('drawn_area_geom').value = new WKT().writeGeometry(e.features.getArray()[0].getGeometry());
            // console.log(e.target)
            // let toast = document.createElement("div");
            // toast.className = "position-fixed bottom-0 end-0 p-3";
            // toast.style = "z-index: 11";
            // toast.insertAdjacentHTML('beforeend', "<h1>ура</h1>");
            // document.body.appendChild(toast);
        })
    })
    document.getElementById('toast-close').addEventListener('click', () => {
        sourceClear(true);
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
            //    console.log(Request.responseText);
            }
        }
    })
}
