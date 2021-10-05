import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';
import 'ol/ol.css';
import 'litepicker/dist/css/litepicker.css'
import Map from 'ol/Map';
import View from 'ol/View';
import {Fill, Stroke, Style} from 'ol/style';
import {OSM, Vector as VectorSource} from 'ol/source';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer';
import {WKT} from "ol/format";
import {Feature} from "ol";
import 'moment';
import 'litepicker-polyfills-ie11';
import Litepicker from 'litepicker';

const source = new VectorSource();
const vector = new VectorLayer({
    source: source,
    style: new Style({
        fill: new Fill({
            color: 'rgb(216,0,254,0.2)',
        }),
        stroke: new Stroke({
            color: 'rgb(216,0,254,0.2)',
            width: 2,
        }),
    }),
});

const map = new Map({
    layers: [
        new TileLayer({
            source: new OSM(),
        }),
        vector
    ],
    target: 'map',
    view: new View(),
});

let temp = document.querySelector('.map');

let feature = new Feature({
    geometry:  new WKT().readGeometry(temp.dataset.geom)
});

vector.getSource().addFeature(feature);
map.getView().fit(feature.getGeometry().getExtent());

let sessionDates = document.getElementById('form_councilSession_isAt');

let highlighted = [];
JSON.parse(document.getElementById('session-dates').dataset.sessionDates).forEach((item)=>{
    highlighted.push(item.startDate)
})

if(sessionDates) {
    window.disableLitepickerStyles = true;
    let picker = new Litepicker({
        element: document.getElementById('form_councilSession_isAt'),
        inlineMode: true,
        lang: "uk-UA",
        highlightedDays: highlighted//document.getElementById('session-dates').dataset.sessionDates.split(','),
    });
    picker.on('preselect', (date) => {
        let formatter = new Intl.DateTimeFormat("ru");
        document.getElementById('date-session').innerHTML = formatter.format(date.dateInstance);
    });
}
