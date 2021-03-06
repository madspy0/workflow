const bootstrap = require('bootstrap');
import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';
import 'tablesort/tablesort.css';

const Tablesort = require('tablesort')
window.Tablesort = Tablesort
require('tablesort/src/sorts/tablesort.date.js')
require('tablesort/src/sorts/tablesort.dotsep.js')
require('tablesort/src/sorts/tablesort.filesize.js')
require('tablesort/src/sorts/tablesort.monthname.js')
require('tablesort/src/sorts/tablesort.number.js')

let account_buttons = document.getElementsByClassName('account-button')
Array.from(account_buttons).forEach(b => {
    b.addEventListener('click', e => {
        e.preventDefault()
        let button = e.currentTarget;
        fetch('/account/enable/' + e.currentTarget.value)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(Promise.reject.bind(Promise));
                }
                return response.json()
            })
            .then(data => {
                if (data.status == 'enabled') {
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

let auth_buttons = document.getElementsByClassName('auth-button')
Array.from(auth_buttons).forEach(b => {
    b.addEventListener('click', e => {
        e.preventDefault()
        let button = e.currentTarget;
        fetch('/account/authorize/' + e.currentTarget.value)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(Promise.reject.bind(Promise));
                }
                return response.json()
            })
            .then(data => {
                if (data.status == 'ok') {
                    button.classList.remove('active')
                    let parent = button.parentNode;
                    let new_span = document.createElement("span")
                    new_span.setAttribute("class", "badge bg-success")
                    let new_span_context = document.createTextNode("Так");
                    new_span.appendChild(new_span_context)
                    while (parent.firstChild) {
                        parent.removeChild(parent.lastChild);
                    }
                    setTimeout(function () {
                        parent.appendChild(new_span)
                    }, 15)
                }
            })
            .catch(error => {
                console.log(
                    `Помилка запиту: ${error}`
                )
            })
    })
})

let deny_buttons = document.getElementsByClassName('deny-button')
Array.from(deny_buttons).forEach(b => {
    b.addEventListener('click', e => {
        e.preventDefault()
        let button = e.currentTarget;
        fetch('/account/deny/' + e.currentTarget.value)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(Promise.reject.bind(Promise));
                }
                return response.json()
            })
            .then(data => {
                if (data.status == 'ok') {
                    let i = button.parentNode.parentNode.rowIndex;
                    setTimeout(function () {
                        document.getElementById("account_table").deleteRow(i);
                    }, 15)
                }
            }).catch(error => {
            console.log(
                `Помилка запиту: ${error}`
            )
        })
    })
});

let account_table = document.getElementById('account_table');
if (!!account_table) {
    new Tablesort(account_table);
}

