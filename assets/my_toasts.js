import Litepicker from "litepicker";
import {Toast} from "bootstrap";
import {sourceClear} from "./draw/draw_map";
import {Modify, Select} from "ol/interaction";
import {clickInfo} from "./click-info";
import {Fill, Stroke, Style} from "ol/style";
import {WKT} from "ol/format";

export function my_toast(content, geom, selected = null, map = null) {
    let clear = document.getElementById('draw_toast');
    if (clear) {
        clear.remove();
    }
    let mod = document.createElement("div");
    //mod.innerHTML(this.response.content);
    mod.id = 'draw_toast';
    mod.className = "toast position-absolute top-0 end-0 text-white bg-primary";
    mod.style.zIndex = "10000";
    mod.setAttribute("role", "dialog");
    mod.setAttribute("aria-live", "assertive");
    mod.setAttribute("aria-atomic", "true");
    mod.setAttribute("data-bs-autohide", false);
    mod.insertAdjacentHTML('beforeend', content);
    document.body.appendChild(mod);
    window.disableLitepickerStyles = true;
    let picker = new Litepicker({
        element: document.getElementById('drawn_area_solutedAt'),
        inlineMode: true,
        lang: "uk-UA",
    });
    if (geom) {
        document.getElementById('drawn_area_geom').value = geom;
    }
    let myToast = Toast.getOrCreateInstance(document.getElementById('draw_toast'));
    myToast.show();
    let form = document.forms[0];
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            let xhr = new XMLHttpRequest();
            let formData = new FormData(form);
            xhr.open("POST", form.action, true);
            //        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (this.readyState != 4) return;
                //alert( this.responseText );
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
        map.getLayers().forEach(layer => {
            if (layer.get('name') === 'plants') {
                layer.getSource().refresh()
            }
        });
        let edit_buttons = document.getElementsByClassName('btn-edit');
        edit_buttons[0].dispatchEvent(new Event("click"));
        edit_buttons[0].dispatchEvent(new Event("click"));
    })

    mod.addEventListener('shown.bs.toast', function () {
        selected.setStyle(new Style({
                stroke: new Stroke({
                    color: 'rgb(255,0,0,0.7)',
                    width: 2,
                })
            })
        );
        let select = new Select({
            //some options
        });
        map.addInteraction(select);
        let selected_collection = select.getFeatures();
        selected_collection.push(selected);
        map.removeInteraction(select);
        const modify = new Modify({
            features: selected_collection,
        });
        map.addInteraction(modify);
        clickInfo(map, false);
        modify.on("modifyend", function (e) {
            document.getElementById('drawn_area_geom').value = new WKT().writeGeometry(e.features.getArray()[0].getGeometry());
            // console.log(e.target)
            // let toast = document.createElement("div");
            // toast.className = "position-fixed bottom-0 end-0 p-3";
            // toast.style = "z-index: 11";
            // toast.insertAdjacentHTML('beforeend', "<h1>ура</h1>");
            // document.body.appendChild(toast);
        })
    })
    document.getElementById('drawn_area_useCategory').addEventListener('change', event => {
        console.log(document.getElementById(event.target.id).value)
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