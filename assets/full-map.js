import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import './scss/portal.scss';

import 'ol/ol.css';
import 'ol-layerswitcher/dist/ol-layerswitcher.css';
import './../assets/scss/app/full-map.scss';

import Map from 'ol/Map';
import View from 'ol/View';
import {Fill, Stroke, Style} from 'ol/style';
import {Draw, Modify, Snap} from 'ol/interaction';
import {OSM, Vector as VectorSource, TileWMS as TileWMSSource} from 'ol/source';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer';
import {fromLonLat} from "ol/proj";

import LayerSwitcher from 'ol-layerswitcher';

import './portal/app';
import LayerGroup from "ol/layer/Group";

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

let parcelSource = new TileWMSSource({
    url: 'http://map.land.gov.ua/geowebcache/service/wms',
    params: {
        'LAYERS': 'kadastr',
        'ALIAS': 'Кадастровий поділ',
        'ALIAS_E': 'Cadastral Division',
        'VERSION': '1.1.1',
        'TILED': 'true',
        'FORMAT': 'image/png',
        'WIDTH': 256,
        'HEIGHT': 256,
        'CRS': 'EPSG:900913', //, CQL_FILTER:'koatuu=3520386800'
        serverType: 'geoserver',
    }
});

let parcels = new TileLayer({
    source: parcelSource,
    visible: 0,
    title: 'Kadastre'
});

const osm = new TileLayer({
    title: 'OSM',
    type: 'base',
    visible: true,
    source: new OSM()
});

const baseMaps = new LayerGroup({
    title: 'Base maps',
    layers: [
        osm,
        vector,
        parcels
    ]
});

const map = new Map({
    layers: baseMaps,
    target: 'full-map',
    view: new View({
        center: fromLonLat([31.182233, 48.382778]),
        zoom: 5,
    }),
});

const layerSwitcher = new LayerSwitcher({
    reverse: true,
    groupSelectStyle: 'group'
});
map.addControl(layerSwitcher);
