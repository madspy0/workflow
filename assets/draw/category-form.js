export function categoryForm() {
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
