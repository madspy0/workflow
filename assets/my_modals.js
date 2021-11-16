import Litepicker from "litepicker";
import {Modal} from "bootstrap";
import {sourceClear} from "./draw/draw_map";
import {Modify, Select} from "ol/interaction";
import {clickInfo} from "./click-info";
import {Fill, Stroke, Style} from "ol/style";

export function my_modal(content) {
    let clear = document.getElementById('draw_modal');
    if (clear) {
        clear.remove();
    }
    let mod = document.createElement("div");
    //mod.innerHTML(this.response.content);
    mod.id = 'drawmodal';
    mod.className = "modal fade";
    mod.setAttribute("role", "dialog");
    mod.setAttribute("tabindex", "-1");
    mod.setAttribute("data-backdrop", "static");
    mod.insertAdjacentHTML('beforeend', content);
    document.body.appendChild(mod);
    // window.disableLitepickerStyles = true;
    // let picker = new Litepicker({
    //     element: document.getElementById('drawn_area_solutedAt'),
    //     inlineMode: true,
    //     lang: "uk-UA",
    // });
    // if (geom) {
    //     document.getElementById('drawn_area_geom').value = geom;
    // }
    let myModal = Modal.getOrCreateInstance(document.getElementById('drawmodal'));
    myModal.show()
    // let form = document.forms[0];
    // if (form) {
    //     form.addEventListener('submit', function (e) {
    //         e.preventDefault();
    //         let xhr = new XMLHttpRequest();
    //         let formData = new FormData(form);
    //         xhr.open("POST", form.action, true);
    //         //        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    //         xhr.onreadystatechange = function () {
    //             if (this.readyState != 4) return;
    //             //alert( this.responseText );
    //             if (xhr.status === 200) {
    //                 sourceClear(true);
    //                 let myModal = Modal.getInstance(document.getElementById('draw_modal'));
    //                 myModal.hide();
    //                 form.reset();
    //             } else {
    //                 console.log(xhr)
    //             }
    //         }
    //         xhr.send(formData);
    //     })
    // }
}