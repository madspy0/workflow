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
import {getArea} from "ol/sphere";
import * as olControl from 'ol/control';
import {listener} from "../listener";

// import {Modal} from "bootstrap";
//
// let myModal = new Modal(document.getElementById('draw_modal'), {
//     backdrop: true
// });

let itemStyles = {
    'created': new Style({
        fill: new Fill({
            color: 'rgba(255, 255, 0, 0.2)',
        }),
        stroke: new Stroke({
            color: 'rgb(255, 255, 0)',
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
    'archived': new Style({
        fill: new Fill({
            color: 'rgba(173, 102, 213, 0.2)',
        }),
        stroke: new Stroke({
            color: 'rgb(173, 102, 213)',
            width: 2,
        }),
    })
};
// a default style is good practice!
export const defaultStyle = new Style({
    fill: new Fill({
        color: 'rgba(255,255,255,0.4)'
    }),
    stroke: new Stroke({
        color: '#3399CC',
        width: 1.25
    })
});
export const formatArea = function (polygon) {
    const area = getArea(polygon);
    let output;
    if (area > 10000) {
        output = Math.round((area / 1000000) * 100) / 100 + ' ' + 'км \u00B2';
    } else {
        output = Math.round(area * 100) / 100 + ' ' + 'м \u0000B2';
    }
    return output;
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
                        appl: '<div>' + item.lastname + ' ' + item.firstname + '</div>',
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
const allPlants = new TileLayer({
    name: 'allPlants',
    title: 'Дiлянкi с дозволом на розробку ТД',
    visible: false,
    source: new TileWMSSource({
        url: 'http://192.168.33.17:8080/geoserver/dd/wms',
        params: {
            'LAYERS': 'ddraw_parcel_all',
            'VERSION': '1.1.1',
            'TILED': 'true',
            'FORMAT': 'image/png',
            'WIDTH': 683,
            'HEIGHT': 768,
            'CRS': 'EPSG:900913',
            serverType: 'geoserver',
        }
    })
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
    title: 'Кадастровий поділ',
    transitionEffect: 'resize'
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
    title: 'Обмеження у використаннi земель'
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
    title: 'Природно-заповiдний фонд'
})

const osm = new TileLayer({
    title: 'Openstreetmap',
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
    type: 'base',
    transitionEffect: 'resize'
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
        url: 'https://m1.land.gov.ua/map/topo_map/{z}/{x}/{y}.png' //'http://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}'
    }),
    visible: false,
    title: 'Оглядова карта',
    type: 'base',
    maxZoom: 16
});

const baseMaps = new LayerGroup({
    title: 'Базові шари',
    fold: 'open',
    layers: [
        oglydova,
        ortoPhoto,
        osm,
        clearLayer,
    ]
});
const cadastreMaps = new LayerGroup({
    title: 'Шари кадастру',
    fold: 'open',
    layers: [
        cadastre,
        restriction,
        atu,
        pzf,
        vector,
        allPlants,
    ]
});
const myMaps = new LayerGroup({
    title: 'Мої ділянки',
    fold: 'open',
    layers: [
        plants,
    ]
});

const map = new Map({
    layers: [baseMaps, cadastreMaps, myMaps],
    target: 'full-map',
    view: new View({
        center: fromLonLat([31.182233, 48.382778]),
        zoom: 5,
    }),
    controls: olControl.defaults({
        zoom: false
    }).extend([
        new olControl.Zoom({
            className: "draw-zoom"
        })
    ])
});

const layerSwitcher = new LayerSwitcher({
    reverse: false,
    groupSelectStyle: 'group',
    target: document.getElementsByClassName('edit-buttons')[0],

});

map.addControl(new DrawButtonsControl());
//document.getElementsByClassName('edit-buttons')[0].append(layerSwitcher);
map.addControl(layerSwitcher);

listener();

let elem_coords = document.getElementById('coord');
let cc = elem_coords.dataset.cc.split(',');
if (cc.length === 2) {
    map.getView().setCenter(cc);
    map.getView().setZoom(elem_coords.dataset.z);
    plants.setVisible(true);
    let edit_buttons = document.getElementsByClassName('btn-edit');
    edit_buttons[0].dispatchEvent(new Event("click"));
}

export function sourceClear(with_plants = false) {
    map.getLayers().forEach(function (el) {
        if (el instanceof LayerGroup) {
            el.getLayers().forEach(function (groupLayer) {
                if ((groupLayer.get('name') === 'drawn') || (groupLayer.get('name') === 'measure_layer')) {
                    groupLayer.getSource().clear();
                }
                if ((groupLayer.get('name') === 'plants') && with_plants) {
                    groupLayer.getSource().refresh();
                }
            })
        }
    })
}