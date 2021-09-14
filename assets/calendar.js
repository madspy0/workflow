import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';

import './portal/app';
import 'litepicker-polyfills-ie11';
import Litepicker from 'litepicker';
const picker = new Litepicker({
    element: document.getElementById('litepicker'),
    inlineMode: true,
    lang: "uk-UA"
});
