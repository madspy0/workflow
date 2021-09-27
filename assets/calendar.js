import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';

import Calendar from 'js-year-calendar';
import 'js-year-calendar/locales/js-year-calendar.ua';
import 'js-year-calendar/dist/js-year-calendar.css';

let getSessDates = () => {
    let sessionDates = JSON.parse(document.getElementById('session-dates').dataset.sessionDates);
    if(sessionDates.length) {
        return sessionDates.map(r => ({
            startDate: new Date(r.startDate),
            endDate: new Date(r.startDate),
            color: '#ffeb3b'
            // name: '#' + r.number + ' - ' + r.title,
            // details: r.comments + ' comments'
        }));
    }
}

// const currentYear = new Date().getFullYear();
new Calendar('.calendar',
    {
        dataSource: getSessDates,
        language: 'ua',
        style: 'background'
    });

import './portal/app';



