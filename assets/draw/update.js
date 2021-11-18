import Swal from "sweetalert2";
import {swalArea} from "./swal-area";

export function update_draw(selected, map=null) {
    let id = selected.get('number');
    const request = new XMLHttpRequest();
    const url = "/dr_upd/" + id;
    request.open('POST', url);
// Указываем заголовки для сервера, говорим что тип данных, - контент который мы хотим получить должен быть не закодирован.
    request.setRequestHeader('Content-Type', 'application/x-www-form-url');

    request.addEventListener("readystatechange", () => {

        if (request.readyState === 4) {
            if(request.status === 200) {
                let obj = JSON.parse(request.responseText);
         //       my_toast(obj.content, selected, map);
                swalArea(obj.content);
            } else {
                Swal.fire({
                    text: "Операцію з об'єктом заблоковано"
                });
            }
        }
    });

// Выполняем запрос
    request.send();

}
