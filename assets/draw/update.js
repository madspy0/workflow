import {Modal} from "bootstrap";
import {sourceClear} from "./draw_map";
import {my_modal} from "../my_modals";

export function update_draw(selected, map=null) {
    let id = selected.get('number');
    const request = new XMLHttpRequest();

    /*  Составляем строку запроса и кладем данные, строка состоит из:
    пути до файла обработчика ? имя в GET запросе где будет лежать значение запроса id_product и
    через & мы передаем количество qty_product. */
    const url = "/dr_upd/" + id;

    /* Здесь мы указываем параметры соединения с сервером, т.е. мы указываем метод соединения GET,
    а после запятой мы указываем путь к файлу на сервере который будет обрабатывать наш запрос. */
    request.open('POST', url);

// Указываем заголовки для сервера, говорим что тип данных, - контент который мы хотим получить должен быть не закодирован.
    request.setRequestHeader('Content-Type', 'application/x-www-form-url');

// Здесь мы получаем ответ от сервера на запрос, лучше сказать ждем ответ от сервера
    request.addEventListener("readystatechange", () => {

        /*   request.readyState - возвращает текущее состояние объекта XHR(XMLHttpRequest) объекта,
        бывает 4 состояния 4-е состояние запроса - операция полностью завершена, пришел ответ от сервера,
        вот то что нам нужно request.status это статус ответа,
        нам нужен код 200 это нормальный ответ сервера, 401 файл не найден, 500 сервер дал ошибку и прочее...   */
        if (request.readyState === 4 && request.status === 200) {
                if(request.responseURL.includes('/login')) {
                    window.location.href=request.responseURL;
                }
                let obj = JSON.parse(request.responseText);
                my_modal(obj.content, null, selected, map)
        }
    });

// Выполняем запрос
    request.send();

}
