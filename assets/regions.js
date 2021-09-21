let country = document.getElementById('development_application_country');
let region = document.getElementById('development_application_region');
let city = document.getElementById('development_application_city');

country.onchange = function () {
    let Request = new XMLHttpRequest();
    Request.open('get', '/regions?country=' + country.value);
    Request.send();
    Request.onreadystatechange = function () {
        if (Request.readyState == 3) {
            // загрузка
        }
        if (Request.readyState == 4) {
            // запрос завершён
            let options = document.querySelectorAll('#development_application_region option');
            options.forEach(o => o.remove());
            let city_options = document.querySelectorAll('#development_application_city option');
            city_options.forEach(o => o.remove());
            let city_opt = document.createElement('option');
            city_opt.value = null;
            city_opt.innerHTML = "Спочатку виберіть регіон ...";
            city.appendChild(city_opt);
            let opt = document.createElement('option');
            opt.value = null;
            opt.innerHTML = "Виберіть регіон";
            region.appendChild(opt);
            let arr = JSON.parse(Request.responseText);
            arr.forEach(function (item, i) {
                let opt = document.createElement('option');
                opt.value = item.id;
                opt.innerHTML = item.name;
                region.appendChild(opt);
            })
            // console.log(Request.response)


        }
    };
}

region.onchange = function () {
    let Request = new XMLHttpRequest();
    Request.open('get', '/cities?region=' + region.value);
    Request.send();
    Request.onreadystatechange = function () {
        if (Request.readyState == 3) {
            // загрузка
        }
        if (Request.readyState == 4) {
            // запрос завершён
            let city = document.getElementById('development_application_city');
            let options = document.querySelectorAll('#development_application_city option');
            options.forEach(o => o.remove());
            let opt = document.createElement('option');
            opt.value = null;
            opt.innerHTML = "Виберіть місто";
            city.appendChild(opt);
            let arr = JSON.parse(Request.responseText);
            arr.forEach(function (item, i) {
                let opt = document.createElement('option');
                opt.value = item.id;
                opt.innerHTML = item.name;
                city.appendChild(opt);
            })
        }
    };
}
//
// let countries = document.querySelector('.dcountries');
// countries.addEventListener('change',(e) => {
//     console.log(e.target.name)
// });