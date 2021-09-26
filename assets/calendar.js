import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';

import Calendar from 'js-year-calendar';
import 'js-year-calendar/locales/js-year-calendar.ua';
import 'js-year-calendar/dist/js-year-calendar.css';

const currentYear = new Date().getFullYear();
new Calendar('.calendar',
    {
        dataSource: [
            {startDate: new Date(currentYear, 2, 1), endDate: new Date(currentYear, 2, 10)},
            {startDate: new Date(currentYear, 2, 5), endDate: new Date(currentYear, 2, 15)}
        ],
        language: 'ua'
    });

import './portal/app';



