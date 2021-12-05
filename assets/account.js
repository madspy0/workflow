import Swal from "sweetalert2";
const bootstrap = require('bootstrap');
import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';
import {swal_person} from "./draw/swal_person";

let account_buttons = document.getElementsByClassName('account-button')
Array.from(account_buttons).forEach(b=>{
    b.addEventListener('click',  e => {
        e.preventDefault()
        let button = e.currentTarget;
        fetch('/account/enable/'+e.currentTarget.value)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(Promise.reject.bind(Promise));
                }
                return response.json()})
            .then(data => {
                if(data.status == 'enabled') {
                    button.classList.remove('active')
                } else {
                    button.classList.add('active')
                }
            })
            .catch(error => {
               console.log(
                    `Помилка запиту: ${error}`
                )
            })
    })
})

document.getElementById('profile_button').addEventListener('click', function (e) {
    e.preventDefault()
    //  my_modal(true)
    swal_person()
})

