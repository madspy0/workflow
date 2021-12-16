import Swal from "sweetalert2";
import Inputmask from "inputmask";
//const bootstrap = require('bootstrap');
import 'bootstrap-icons/font/bootstrap-icons.css';

if (!!document.getElementById('isDisable')) {
    Swal.fire('Дякуємо за реєстрацію! Повідомлення про активацію облікового запису буде доставлено на Вашу пошту');
}
let profilePhone = document.getElementById('registration_form_profile_phone');
if (!!profilePhone) {
    Inputmask({mask: "+380 (99) 999-99-99", placeholder: " "}).mask(profilePhone);
    // Inputmask({
    //     mask: "*{1,30}@*{1,30}.*{1,30}[.*{1,30}]{1,30}",
    //     placeholder: " "
    // }).mask(document.getElementById("registration_form_email"));


//    Inputmask({
//        mask: "\\http[s]://*{+}",
    //    autoUnmask : true,
//        placeholder: "_"
//    }).mask(document.getElementById("registration_form_profile_url"));
}

let toggle_pass_span = document.getElementById('toggle-pass');
if (!!toggle_pass_span) {
    toggle_pass_span.addEventListener('click', function (e) {
        e.preventDefault()
        //  my_modal(true)
        let input_pass = document.getElementById('registration_form_plainPassword');
        if (!!input_pass) {
            let toogler = document.getElementById('toggle-pass-icon')
            if (toogler.classList.contains('bi-eye-slash')) {
                toogler.classList.remove('bi-eye-slash')
                toogler.classList.add('bi-eye')
                input_pass.type = "text"
            } else {
                toogler.classList.remove('bi-eye')
                toogler.classList.add('bi-eye-slash')
                input_pass.type = "password"
            }
        }
    })
}
