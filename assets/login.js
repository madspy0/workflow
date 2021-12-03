import Swal from "sweetalert2";
import Inputmask from "inputmask";

if(!!document.getElementById('isDisable')){
    Swal.fire('Дякуємо за реєстрацію! Повідомлення про активацію облікового запису буде доставлено на Вашу пошту');
}
let profilePhone = document.getElementById('registration_form_profile_phone');
if(!!profilePhone) {
    Inputmask({mask: "+380 (99) 999-99-99", placeholder: "_"}).mask(profilePhone);
    Inputmask({mask: "*{1,30}@*{1,30}.*{1,30}[.*]{1,30}", placeholder: "_"}).mask(document.getElementById("registration_form_email"));
    Inputmask({mask: "http[s]://*{1,50}.*{1,30}[.*{1,30}]{1,10}", placeholder: "_"}).mask(document.getElementById("registration_form_profile_url"));
}

