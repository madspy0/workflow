import 'ol/ol.css';
import Map from 'ol/Map';
import View from 'ol/View';
import {Fill, Stroke, Style} from 'ol/style';
import {Draw, Modify, Snap} from 'ol/interaction';
import {OSM, Vector as VectorSource} from 'ol/source';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer';
import {fromLonLat} from "ol/proj";
import {WKT} from "ol/format";

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
    view: new View({
        center: fromLonLat([31.182233, 48.382778]),
        zoom: 5,
    }),
});

const modify = new Modify({source: source});
map.addInteraction(modify);
const draw = new Draw({
    source: source,
    type: "Polygon",
});
map.addInteraction(draw);
const snap = new Snap({source: source});
map.addInteraction(snap);
draw.on('drawend', function (event) {
    let feature = event.feature;
    let geom = new WKT().writeGeometry(feature.getGeometry(feature.getGeometry()));
    document.getElementById('development_application_geom').value = geom;
})
modify.on('modifyend', function (event) {
    let feature = event.feature;
    let geom = new WKT().writeGeometry(feature.getGeometry(feature.getGeometry()));
    document.getElementById('development_application_geom').value = geom;
})

let country = document.getElementById('development_application_country');
country.onchange = function () {
    let Request = new XMLHttpRequest();
    Request.open('get', '/regions?country=' + country.value);
    Request.send();
    Request.onreadystatechange = function () {
        if (Request.readyState == 3) {
            // загрузка
        }
        if (Request.readyState == 4) {
            // запрос завершён
            let arr = Request.response;
            console.log(arr);
            arr.forEach(function(item, i) {
                console.log(item)
            })
            // console.log(Request.response)
            let regions = document.getElementById('development_application_region');

        }
    };
}