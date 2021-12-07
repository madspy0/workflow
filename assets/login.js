import Swal from "sweetalert2";
import Inputmask from "inputmask";
import '@algolia/autocomplete-theme-classic';
import {autocomplete} from '@algolia/autocomplete-js';

if(!!document.getElementById('isDisable')){
    Swal.fire('Дякуємо за реєстрацію! Повідомлення про активацію облікового запису буде доставлено на Вашу пошту');
}
let profilePhone = document.getElementById('registration_form_profile_phone');
if(!!profilePhone) {
    Inputmask({mask: "+380 (99) 999-99-99", placeholder: "_"}).mask(profilePhone);
    Inputmask({mask: "*{1,30}@*{1,30}.*{1,30}[.*{1,30}]{1,30}", placeholder: "_"}).mask(document.getElementById("registration_form_email"));
    Inputmask({mask: "http[s]://*{1,50}.*{1,30}[.*{1,30}]{1,10}", placeholder: "_"}).mask(document.getElementById("registration_form_profile_url"));
}

let otgAutocomplete = autocomplete({
    container: '#otg-registration',
    placeholder: 'Назва органу влади',
    getSources({query}) {
        return [
            {
                sourceId: 'otg',
                getItems() {
                    return fetch('/dr_otg/?q=' + query)
                        .then(response => response.json())
                        .then(data => {
                            return data;
                        });
                },
                onSelect({item}) {
                    otgAutocomplete.setQuery(item.name_otg)
                    console.log(item)
                    document.getElementById('registration_form_profile_otg').value = item.id;
                },

                templates: {
                    item({item}) {
                        return item.name_otg;
                    },
                    // noResults() {
                    //     return 'No results.';
                    // }
                },
            }]
    }
});