import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import '../scss/portal.scss';

import 'ol/ol.css';
import 'ol-layerswitcher/dist/ol-layerswitcher.css';

import '@algolia/autocomplete-theme-classic';
import '../scss/app/draw_map.scss';

import Map from 'ol/Map';
import View from 'ol/View';
import {Circle, Fill, Icon, Stroke, Style} from 'ol/style';
import {OSM, Vector as VectorSource, TileWMS as TileWMSSource, XYZ} from 'ol/source';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer';
import {fromLonLat} from "ol/proj";
import LayerSwitcher from 'ol-layerswitcher';
import '../portal/app';
import LayerGroup from "ol/layer/Group";
import {Feature} from "ol";
import {WKT} from "ol/format";
import {getArea} from "ol/sphere";
import * as olControl from 'ol/control';
import {Draw, Modify, Snap,  Select} from "ol/interaction";

import {swal_person} from "./swal_person";
import {addInteractions} from "./add-interactions";
import DrawButtonsControl from './draw_buttons_control';
import {swalArea, toastFire} from "./swal-area";
import {addInteractionMeasure} from "./add-interaction-measure";
import {autocomplete} from '@algolia/autocomplete-js';
import {MousePosition, OverviewMap, ScaleLine} from "ol/control";
import {createStringXY} from "ol/coordinate";
import {Point} from "ol/geom";
import Overlay from "ol/Overlay";
import {unByKey} from "ol/Observable";
import Swal from "sweetalert2";

export const itemStyles = {
    'created': new Style({
        fill: new Fill({
            color: 'rgba(255, 255, 102, .2)',
        }),
        stroke: new Stroke({
            color: 'rgb(255, 255, 0)',
            width: 2,
        }),
    }),
    'published': new Style({
        fill: new Fill({
            color: 'rgba(51, 51, 255, .2)',
        }),
        stroke: new Stroke({
            color: 'rgb(51, 51, 255)',
            width: 2,
        }),
    }),
    'archived': new Style({
        fill: new Fill({
            color: 'rgba(255, 102, 102, .2)',
        }),
        stroke: new Stroke({
            color: 'rgb(204, 0, 0)',
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
  //  if (area > 10000) {
 //       output = Math.round((area / 1000000) * 100) / 100 + ' ' + 'км \u00B2';
        output = (area / 10000).toFixed(4) + ' ' + 'Га';
    // } else {
    //     output = Math.round(area * 100) / 100 + ' ' + 'м \u00B2';
    // }
    return output;
};
const formatLoadArea = function (area) {
    let output;
    output = (area / 10000).toFixed(4) + ' ' + 'Га';
    return output;
}
const source = new VectorSource({
    //format: new GeoJSON(),
    loader: function (extent, resolution, projection, success, failure) {
        // var proj = projection.getCode();
        // var url = 'https://ahocevar.com/geoserver/wfs?service=WFS&' +
        //     'version=1.1.0&request=GetFeature&typename=osm:water_areas&' +
        //     'outputFormat=application/json&srsname=' + proj + '&' +
        //     'bbox=' + extent.join(',') + ',' + proj;
        let xhr = new XMLHttpRequest();
        xhr.open("GET", '/drawen_geoms');
        let onError = function () {
            source.removeLoadedExtent(extent);
            failure();
        }
        xhr.onerror = onError;
        xhr.onload = function () {
            if (xhr.status === 200) {
                let geoms = JSON.parse(xhr.response);
                let status_dict = {'created': 'створений', 'published': 'відображений', 'archived': 'архівований'}
                let formatedDate = (date) => {
                    let current_datetime = new Date(date)
                    return current_datetime.getDate() + "-" + (current_datetime.getMonth() + 1) + "-" + current_datetime.getFullYear() + " " + current_datetime.getHours() + ":" + current_datetime.getMinutes() + ":" + current_datetime.getSeconds()
                }
                geoms.forEach(function (item, index) {
                    let feature = new Feature({
                        geometry: new WKT().readGeometry(item.geom),
                        appl: '<div>' + item.numberSolution +
                            '</div><div> ' + formatedDate(item.createdAt) +
                            '</div><div> ' + formatLoadArea(item.area) + '</div>',
                        // <div>' + item.status + '</div>',
                        number: item.id,
                        status: item.status,
                        published: item.publishedAt
                    });
                    //      feature.setStyle(itemStyles[item.status]);
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

export const plants = new VectorLayer({
    source: source,
    name: 'plants',
    title: 'Ділянки',
    maxZoom: 17,
    transitionEffect: 'resize',
    visible: true,
    style: function (feature, resolution) {
        return ([itemStyles[feature.get('status')]])
    }
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
    title: 'Дiлянки з дозволом на розробку ТД',
    visible: false,
    maxZoom: 17,
    transitionEffect: 'resize',
    source: new TileWMSSource({
        url: '/ddsource',
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


export const measureLayer = new VectorLayer({
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
        image: new Icon({
            // anchor: [0.5, 46],
            // anchorXUnits: 'fraction',
            // anchorYUnits: 'pixels',
            src: 'https://openlayers.org/en/latest/examples/data/icon.png'
        })
    }),
});
export const drawLayer = new VectorLayer({
    name: 'drawn',
    source: new VectorSource(),
    style: defaultStyle,
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
    visible: 1,
    title: 'Кадастровий поділ',
    maxZoom: 17,
    transition: 300,
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
    maxZoom: 17,
    transitionEffect: 'resize',
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
    maxZoom: 17,
    transitionEffect: 'resize',
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
    maxZoom: 17,
    transitionEffect: 'resize',
    title: 'Природно-заповiдний фонд'
})

const osm = new TileLayer({
    title: 'Openstreetmap',
    type: 'base',
    visible: false,
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
    maxZoom: 17,
    transition: 300,
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
    visible: true,
    title: 'Оглядова карта',
    type: 'base',
    maxZoom: 17,
    transition: 300
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

const mousePositionControl = new MousePosition({
    coordinateFormat: createStringXY(4),
    projection: 'EPSG:4326',
    placeholder: false, //'Довгота Широта'
    //   className: 'draw-mouse-position',
//    target: document.getElementById('mouse-position'),
});

const overviewMap = new OverviewMap({
    layers: [
        new TileLayer({
            title: 'Openstreetmap',
            type: 'base',
            visible: true,
            source: new OSM()
        })
    ],
    className: 'ol-overviewmap ol-custom-overviewmap',
    collapsed: false,
    label: '«',
    collapseLabel: '»',
    tipLabel: 'Екстент та переміщення'
})

const scaleLine = new ScaleLine(
    {
        // 'Лінійний масштаб'
        //  className: 'ol-scale-line ol-custom-scale-line',
        //  target: document.getElementById('scale-line')
    }
);

export const map = new Map({
    layers: [
        baseMaps,
        cadastreMaps,
        myMaps,
        measureLayer,
        //       drawLayer
    ],
    target: 'full-map',
    view: new View({
        center: fromLonLat([31.182233, 48.382778]),
        zoom: 7,
        maxZoom: 17,
    }),
    controls: olControl.defaults({
        zoom: false,
    }).extend([
        new olControl.Zoom({
            className: "draw-zoom",
            zoomOutTipLabel: 'Збільшити масштаб',
            zoomInTipLabel: 'Зменшити масштаб'
        }),
        mousePositionControl,
        overviewMap,
        scaleLine
    ])
});

let draw = new Draw({
    source: plants.getSource(),
    type: 'Polygon',
    style: new Style({
        fill: new Fill({
            color: 'rgba(255, 255, 255, 0.2)',
        }),
        stroke: new Stroke({
            color: 'rgba(0, 0, 0, 0.5)',
            lineDash: [10, 10],
            width: 2,
        }),
        image: new Circle({
            radius: 5,
            stroke: new Stroke({
                color: 'rgba(0, 0, 0, 0.7)',
            }),
            fill: new Fill({
                color: 'rgba(255, 255, 255, 0.2)',
            }),
        }),
    }),
});
let measureTooltip;
let measureTooltipElement;
function createMeasureTooltip() {
    if (measureTooltipElement) {
        measureTooltipElement.parentNode.removeChild(measureTooltipElement);
    }
    measureTooltipElement = document.createElement('div');
    measureTooltipElement.className = 'ol-tooltip ol-tooltip-measure';
    measureTooltip = new Overlay({
        element: measureTooltipElement,
        offset: [0, -15],
        positioning: 'bottom-center',
        stopEvent: false,
        insertFirst: false,
    });
    map.addOverlay(measureTooltip);
}
let sketch;
let listener;
draw.on('drawstart', function (evt) {
    // set sketch
    sketch = evt.feature;
    createMeasureTooltip();
    /** @type {import("../src/ol/coordinate.js").Coordinate|undefined} */
    let tooltipCoord = evt.coordinate;

    listener = sketch.getGeometry().on('change', function (evt) {
        const geom = evt.target;
        let output;
        output = formatArea(geom);
        tooltipCoord = geom.getInteriorPoint().getCoordinates();
        measureTooltipElement.innerHTML = output;
        measureTooltip.setPosition(tooltipCoord);
    });
});
draw.on('drawend', function (evt) {
    evt.preventDefault();
    let feature = evt.feature;
    feature.set('number', 'new');
    feature.set('status', 'created');
    feature.set('appl','доданий')
    // measureTooltipElement.className = 'ol-tooltip ol-tooltip-static';
    // measureTooltip.setOffset([0, -7]);
    // unset sketch
    sketch = null;
    // unset tooltip so that a new one can be created
    measureTooltipElement = null;
    map.removeOverlay(measureTooltip)
    createMeasureTooltip();
    unByKey(listener);
    swalArea(feature)
})
draw.setProperties({name: 'drawer'})
draw.setActive(false);
map.getInteractions().extend([draw]);
const layerSwitcher = new LayerSwitcher({
    reverse: false,
    groupSelectStyle: 'group',
    target: document.getElementsByClassName('edit-buttons')[0],

});
addInteractions();
addInteractionMeasure();


map.addControl(new DrawButtonsControl());
//document.getElementsByClassName('edit-buttons')[0].append(layerSwitcher);
map.addControl(layerSwitcher);

let elem_coords = document.getElementById('coord');
if(!!elem_coords) {
    let cc = elem_coords.dataset.cc.split(',');
    if (cc.length === 2) {
        map.getView().setCenter(cc);
        map.getView().setZoom(elem_coords.dataset.z);
        plants.setVisible(true);
        let edit_buttons = document.getElementsByClassName('btn-edit');
        edit_buttons[0].dispatchEvent(new Event("click"));
    }
}
let rmInteractionsOverlays = () => {
    map.getOverlays().getArray().slice(0).forEach(function (overlay) {
        map.removeOverlay(overlay);
    });
    map.getInteractions().forEach(function (interaction) {
        if ((interaction instanceof Draw) || (interaction instanceof Select) || (interaction instanceof Modify) || (interaction instanceof Snap)) {
            interaction.setActive(false);
        }
    }, this);
}

export function sourceClear(plants = false) {
    rmInteractionsOverlays();
    map.getLayers().forEach(function (el) {
        if (el instanceof LayerGroup) {
            el.getLayers().forEach(function (i) {
                //  clearVectorLayer(i, plants)
            });
        } else {
            // clearVectorLayer(el, plants)
        }
    })
}

if (!!document.getElementById('profile_flag')) {
    // my_modal(true);
    setTimeout(() => {
        swal_person()
    }, 1000)
}

setTimeout(() => {Swal.fire({
    title: 'Програмний модуль працює в режимі дослідної експлуатації.',
    html: '<a href="/docs/ІнструкціяКористувач_1.01_15.12.21.docx">Інструкція користувача</a>',
    showCancelButton: true,
    showConfirmButton: false,
    cancelButtonText: 'Зрозуміло',
})},1500)

if(document.getElementById('profile_button')) {
    document.getElementById('profile_button').addEventListener('click', function (e) {
        e.preventDefault()
        //  my_modal(true)
        swal_person()
    })
}
let dAutocomplete = autocomplete({
    container: '#searchbox',
    translations: {
        clearButtonTitle: 'скинути',
        submitButtonTitle: 'пошук'
    },
    onReset() {
        let measureSource = measureLayer.getSource();
        measureSource.getFeatures().forEach(f => {
                if (f.getGeometry() instanceof Point) {
                    measureSource.removeFeature(f)
                }
            }
        )
    },
    // onSubmit(state, e) {
    //     console.log('submit',state)
    //   //  activeItemId(3)
    // },
    placeholder: 'Пошук по населеному пункту',
    getSources({query}) {
        return [
            {
                sourceId: 'towns',
                getItems() {
                    return fetch('/dr_search/?q=' + query)
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(Promise.reject.bind(Promise));
                                //   throw new Error(response.statusText)
                            }
                            return (response.json())
                        })
                        .then(data => {
                            return data;
                        }).catch(error => toastFire(error));
                },
                onSelect({item}) {
                    dAutocomplete.setQuery(item.nameUa)
                    let measureSource = measureLayer.getSource();
                    measureSource.getFeatures().forEach(f => {
                            if (f.getGeometry() instanceof Point) {
                                measureSource.removeFeature(f)
                            }
                        }
                    )

                    let marker = new Feature({
                        geometry: new WKT().readGeometry(item.geom3857)
                    });
                    measureLayer.getSource().addFeature(marker)
                    map.getView().fit(new WKT().readGeometry(item.bboxgeom), {
                        padding: [15, 565, 15, 15],
                        duration: 500
                    });
                },

                templates: {
                    item({item}) {
                        return item.nameUa + ' ' + item.district + ' р-н ' + item.nameObl + ' обл.';
                    },
                    // noResults() {
                    //     return 'No results.';
                    // }
                },
            }]
    }
});
const copyright = document.createElement('div');
copyright.style = 'position:absolute; bottom:0;width:100%;'
copyright.innerHTML = '<div class="container text-center py-2"><small class="copyright" style="background-color: #f8f8f888">Держгеокадастр &copy;2021 версія 1.01</small></div>'
document.body.append(copyright)
const setActive = (el, active) => {
    const formField = el.parentNode.parentNode
    if (active) {
        formField.classList.add('form-field--is-active')
    } else {
        formField.classList.remove('form-field--is-active')
        el.value === '' ?
            formField.classList.remove('form-field--is-filled') :
            formField.classList.add('form-field--is-filled')
    }
}

[].forEach.call(
    document.querySelectorAll('.form-field__input, .form-field__textarea'),
    (el) => {
        el.onblur = () => {
            setActive(el, false)
        }
        el.onfocus = () => {
            setActive(el, true)
        }
    }
)

document.getElementById('profile_button').addEventListener('click', function (e) {
    e.preventDefault()
    //  my_modal(true)
    swal_person()
})
