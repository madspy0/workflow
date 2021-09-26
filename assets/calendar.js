import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';

import Calendar from 'js-year-calendar';
import 'js-year-calendar/locales/js-year-calendar.ua';
import 'js-year-calendar/dist/js-year-calendar.css';

let sessionDates = JSON.parse(document.getElementById('session-dates').dataset.sessionDates);
let getSessDates = () => {
    let res = [];
    sessionDates.forEach(function (item) {
        res.push({startDate: new Date(item['isAt']), endDate: new Date(item['isAt'])});
    })
    return res;
}

const currentYear = new Date().getFullYear();
new Calendar('.calendar',
    {
        dataSource: getSessDates,
        language: 'ua'
    });

import './portal/app';



