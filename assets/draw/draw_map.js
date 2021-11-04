import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import '../scss/portal.scss';

import 'ol/ol.css';
import 'ol-layerswitcher/dist/ol-layerswitcher.css';
import '../scss/app/draw_map.scss';

import Map from 'ol/Map';
import View from 'ol/View';
import {Fill, Stroke, Style} from 'ol/style';

import {OSM, Vector as VectorSource, TileWMS as TileWMSSource, XYZ} from 'ol/source';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer';
import {fromLonLat} from "ol/proj";

import LayerSwitcher from 'ol-layerswitcher';
import DrawButtonsControl from './draw_buttons_control';

import '../portal/app';
import LayerGroup from "ol/layer/Group";
import {Feature} from "ol";
import {WKT} from "ol/format";

// import {Modal} from "bootstrap";
//
// let myModal = new Modal(document.getElementById('draw_modal'), {
//     backdrop: true
// });

let itemStyles = {
    'draft': new Style({
        fill: new Fill({
            color: 'rgba(255, 255, 0, 0.2)',
        }),
        stroke: new Stroke({
            color: 'rgb(255, 255, 0)',
            width: 2,
        }),
    }),
    'numbered': new Style({
        fill: new Fill({
            color: 'rgba(201, 247, 111, 0.2)',
        }),
        stroke: new Stroke({
            color: 'rgb(201, 247, 111)',
            width: 2,
        }),
    }),
    'published': new Style({
        fill: new Fill({
            color: 'rgba(230, 103, 175, 0.2)',
        }),
        stroke: new Stroke({
            color: 'rgb(230, 103, 175)',
            width: 2,
        }),
    }),
    'rejected': new Style({
        fill: new Fill({
            color: 'rgba(173, 102, 213, 0.2)',
        }),
        stroke: new Stroke({
            color: 'rgb(173, 102, 213)',
            width: 2,
        }),
    })
};
const source = new VectorSource({
    //format: new GeoJSON(),
    loader: function (extent, resolution, projection, success, failure) {
        // var proj = projection.getCode();
        // var url = 'https://ahocevar.com/geoserver/wfs?service=WFS&' +
        //     'version=1.1.0&request=GetFeature&typename=osm:water_areas&' +
        //     'outputFormat=application/json&srsname=' + proj + '&' +
        //     'bbox=' + extent.join(',') + ',' + proj;
        let xhr = new XMLHttpRequest();
        xhr.open('GET', '/drawen_geoms');
        let onError = function () {
            source.removeLoadedExtent(extent);
            failure();
        }
        xhr.onerror = onError;
        xhr.onload = function () {
            if (xhr.status === 200) {
                let geoms = JSON.parse(xhr.response);
                geoms.forEach(function (item, index) {
                    let feature = new Feature({
                        geometry: new WKT().readGeometry(item.geom),
                        appl: '<div>' + item.lastname + ' ' + item.firstname  + '</div>',
                        number: item.id,
                        status: item.status,
                    });
                    feature.setStyle(itemStyles[item.status]);
                    source.addFeature(feature);
                });
                // let features = source.getFormat().readFeatures(xhr.responseText);
                // source.addFeatures(features);
                success();
            } else {
                onError();
            }
        }
        xhr.send();
    },
//    strategy: bbox
});

const plants = new VectorLayer({
    source: source,
    name: 'plants',
    title: 'Ділянки',
    visible: true,
    // style: new Style({
    //     fill: new Fill({
    //         color: 'rgb(216,0,254,0.2)',
    //     }),
    //     stroke: new Stroke({
    //         color: 'rgb(216,0,254,0.2)',
    //         width: 2,
    //     }),
    // }),
});

const vector = new VectorLayer({
    source: new VectorSource(),
    name: 'measure_layer',
    style: new Style({
        fill: new Fill({
            color: 'rgba(255, 255, 255, 0.2)',
        }),
        stroke: new Stroke({
            color: '#ffcc33',
            width: 2,
        }),
    }),
});

let cadastreSource = new TileWMSSource({
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
        'CRS': 'EPSG:900913',
        serverType: 'geoserver',
    }
});

let cadastre = new TileLayer({
    source: cadastreSource,
    visible: 0,
    title: 'Кадатр'
});

let restriction = new TileLayer({
    source: new TileWMSSource({
        url: 'https://m1.land.gov.ua/geowebcache/service/wms',
        params: {
            'LAYERS': 'restriction',
            'VERSION': '1.3.0',
            'TILED': 'true',
            'FORMAT': 'image/png',
            'WIDTH': 256,
            'HEIGHT': 256,
            'CRS': 'EPSG:900913',
            'SRS': 'EPSG:900913',
            serverType: 'geoserver',
        }
    }),
    visible: 0,
    title: 'Обмеження'
})

let atu = new TileLayer({
    source: new TileWMSSource({
        url: 'https://m1.land.gov.ua/geowebcache/service/wms',
        params: {
            'LAYERS': 'atu',
            'VERSION': '1.3.0',
            'TILED': 'true',
            'FORMAT': 'image/png',
            'WIDTH': 256,
            'HEIGHT': 256,
            'CRS': 'EPSG:900913',
            'SRS': 'EPSG:900913',
            serverType: 'geoserver',
        }
    }),
    visible: 0,
    title: 'АТУ'
})

let pzf = new TileLayer({
    source: new TileWMSSource({
        url: 'https://m1.land.gov.ua/geowebcache/service/wms',
        params: {
            'LAYERS': 'pcm_pzf',
            'VERSION': '1.3.0',
            'TILED': 'true',
            'FORMAT': 'image/png',
            'WIDTH': 256,
            'HEIGHT': 256,
            'CRS': 'EPSG:900913',
            'SRS': 'EPSG:900913',
            serverType: 'geoserver',
        }
    }),
    visible: 0,
    title: 'ПЗФ'
})

const osm = new TileLayer({
    title: 'OSM',
    type: 'base',
    visible: true,
    source: new OSM()
});

let ortoPhoto = new TileLayer({
    'opacity': 1.000000,
    source: new XYZ({
        url: 'https://m2.land.gov.ua/map/ortho10k_all/{z}/{x}/{-y}.jpg' //'http://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}'
    }),
    visible: false,
    title: 'Ортофотоплани',
    type: 'base'
});

let clearLayer = new VectorLayer({
    source: null,
    visible: false,
    title: 'Без підложки',
    type: 'base'
});

let oglydova = new TileLayer({
    'opacity': 1.000000,
    source: new XYZ({
        url: 'https://m1.land.gov.ua/map/dzk_overview/{z}/{x}/{-y}.png' //'http://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}'
    }),
    visible: false,
    title: 'Оглядова карта',
    type: 'base'
});

const baseMaps = new LayerGroup({
    title: 'Base maps',
    layers: [
        clearLayer,
        oglydova,
        ortoPhoto,
        osm,
        pzf,
        atu,
        restriction,
        cadastre,
        plants,
        vector,
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
    groupSelectStyle: 'group',
    target: document.getElementsByClassName('edit-buttons')[0]
});

map.addControl(new DrawButtonsControl());
//document.getElementsByClassName('edit-buttons')[0].append(layerSwitcher);
map.addControl(layerSwitcher);

let elem_coords = document.getElementById('coord');
let cc = elem_coords.dataset.cc.split(',');
if (cc.length === 2) {
    map.getView().setCenter(cc);
    map.getView().setZoom(elem_coords.dataset.z);
    plants.setVisible(true);
    let edit_buttons = document.getElementsByClassName('btn-edit');
    edit_buttons[0].dispatchEvent(new Event("click"));
}

export function sourceClear(with_plants=false) {
    map.getLayers().forEach(function (el) {
        if ((el.get('name') === 'drawn') || (el.get('name') === 'measure_layer')) {
            el.getSource().clear();
        }
        if((el.get('name')==='plants') && with_plants) {
            el.getSource().refresh();
        }
    })
}
