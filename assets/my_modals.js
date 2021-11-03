import Litepicker from "litepicker";
import {Modal} from "bootstrap";
import {sourceClear} from "./draw/draw_map";
import {Modify, Select} from "ol/interaction";
import {clickInfo} from "./click-info";
import {Fill, Stroke, Style} from "ol/style";

export function my_modal(content, geom, selected = null, map = null) {
    let clear = document.getElementById('draw_modal');
    if (clear) {
        clear.remove();
    }
    let mod = document.createElement("div");
    //mod.innerHTML(this.response.content);
    mod.id = 'draw_modal';
    mod.className = "modal fade";
    mod.setAttribute("role", "dialog");
    mod.setAttribute("tabindex", "-1");
    mod.setAttribute("data-backdrop", "static");
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
    let myModal = Modal.getOrCreateInstance(document.getElementById('draw_modal'));
    myModal.show();
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
                    let myModal = Modal.getInstance(document.getElementById('draw_modal'));
                    myModal.hide();
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
    //         selected.setStyle(new Style({
    //                 stroke: new Stroke({
    //                     color: 'rgb(255,0,0,0.2)',
    //                     width: 2,
    //                 })
    //             })
    //         );
    //         let select = new Select({
    //             //some options
    //         });
    //         map.addInteraction(select);
    //         let selected_collection = select.getFeatures();
    //         selected_collection.push(selected);
    //         map.removeInteraction(select);
    //         const modify = new Modify({
    //             features: selected_collection,
    //         });
    //         map.addInteraction(modify);
    //         clickInfo(map, false);
    //         modify.on("modifyend", function (e) {
    //             console.log(e.target)
    //             let toast = document.createElement("div");
    //             toast.className = "position-fixed bottom-0 end-0 p-3";
    //             toast.style = "z-index: 11";
    //             toast.insertAdjacentHTML('beforeend', "<h1>ура</h1>");
    //             document.body.appendChild(toast);
    //         })
    //     })
    // }
}