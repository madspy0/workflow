import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';

import Calendar from 'js-year-calendar';
import 'js-year-calendar/locales/js-year-calendar.ua';
import 'js-year-calendar/dist/js-year-calendar.css';
import {Popover} from "bootstrap";

function redirect (url) {
    const ua = navigator.userAgent.toLowerCase(),
        isIE = ua.indexOf('msie') !== -1,
        version = parseInt(ua.substr(4, 2), 10);

    // Internet Explorer 8 and lower
    if (isIE && version < 9) {
        const link = document.createElement('a');
        link.href = url;
        document.body.appendChild(link);
        link.click();
    }

    // All other browsers can use the standard window.location.href (they don't lose HTTP_REFERER like Internet Explorer 8 & lower does)
    else {
        window.location.href = url;
    }
}

let getSessDates = () => {
    let sessionDates = JSON.parse(document.getElementById('session-dates').dataset.sessionDates);
    if (sessionDates.length) {
        return sessionDates.map(r => ({
            startDate: new Date(r.startDate),
            endDate: new Date(r.startDate),
            color: '#ffeb3b',
            name: r.id,
            id: r.developmentApplications.length,
            // name: '#' + r.number + ' - ' + r.title,
            // details: r.comments + ' comments'
        }));
    }
}

new Calendar('.calendar',
    {
        dataSource: getSessDates,
        language: 'ua',
        style: 'background',
        clickDay: function (e) {
            if (e.events.length > 0) {
               //alert('Заплановано заявок ' + e.events[0].name);
                redirect('/sess/' + e.events[0].name);
            }
        },
        mouseOnDay: function (e) {
            if (e.events.length > 0) {
                let popover = new Popover(e.element, {content: 'Заплановано заявок ' + e.events[0].id});
                popover.show();
            }
        },
        mouseOutDay: function (e) {
            if (e.events.length > 0) {
                let popover = Popover.getInstance(e.element);
                popover.hide();
            }
        }
    });

import './portal/app';



