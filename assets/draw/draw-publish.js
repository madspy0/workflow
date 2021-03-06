import Swal from "sweetalert2";
import {itemStyles} from "./draw_map";

export function drawPublish(value) {
    Swal.fire({
        title: "Ви впевнені?",
        text: "Після відображення на ПКК ви не зможете змінити дані",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Выдобразити на ПКК',
        cancelButtonText: 'Вiдмiнити'
    })
        .then((willPublic) => {
            if (willPublic.isConfirmed) {
                let Request = new XMLHttpRequest();
                Request.open('get', '/dr_publ/' + value);
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

//                         selected.set('status', 'published');
// //                            plants.getSource().changed();
//                         selected.setStyle(itemStyles['published'])
//
//                         myToast.hide();
                        Swal.fire({
                            text: "Дані опубліковані",
                            icon: "success",
                        });

                    }
                }
            }
        })
}
