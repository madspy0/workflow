import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';

import Calendar from 'js-year-calendar';
import 'js-year-calendar/locales/js-year-calendar.ua';
import 'js-year-calendar/dist/js-year-calendar.css';
new Calendar('.calendar',
    { language: 'ua' });

import './portal/app';



