import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';

import 'ol/ol.css';
import Map from 'ol/Map';
import View from 'ol/View';
import {Fill, Stroke, Style} from 'ol/style';
import {Draw, Modify, Snap} from 'ol/interaction';
import {OSM, Vector as VectorSource} from 'ol/source';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer';
import {fromLonLat} from "ol/proj";
import {WKT} from "ol/format";
import {Feature} from "ol";

import './portal/app';

import './regions';

const source = new VectorSource();
const vector = new VectorLayer({
    source: source,
    name: 'vector',
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
draw.on('drawstart', function() {
    source.clear();
})
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

window.onload = function () {
    let geom = document.getElementById('development_application_geom').value;
    if (geom) {
        let feature = new Feature({
            geometry: new WKT().readGeometry(geom)
        });
        vector.getSource().addFeature(feature);
        map.getView().fit(feature.getGeometry().getExtent());
    }
}
