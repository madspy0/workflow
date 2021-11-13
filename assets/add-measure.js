import {Vector as VectorSource} from 'ol/source';
import {Vector as VectorLayer} from 'ol/layer';
import Overlay from 'ol/Overlay';
import {Draw} from 'ol/interaction';
import {Fill, Stroke, Style, Circle} from 'ol/style';
import {unByKey} from 'ol/Observable';
import {sourceClear, formatArea} from "./draw/draw_map";

/**
 * Format area output.
 * @param {Polygon} polygon The polygon.
 * @return {string} Formatted area.
 */


let map;

function addMeasureLayer() {
    let source;
    map.getLayers().forEach(function (el) {
        if (el.get('name') === 'measure_layer') {
            source = el.getSource();
        }
    })
    // if (!source) {
    //     source = new VectorSource();
    //     let layer = new VectorLayer({
    //         name: 'measure_layer',
    //         source: source,
    //         style: new Style({
    //             fill: new Fill({
    //                 color: 'rgba(255, 255, 255, 0.2)',
    //             }),
    //             stroke: new Stroke({
    //                 color: '#ffcc33',
    //                 width: 2,
    //             }),
    //             image: new Circle({
    //                 radius: 7,
    //                 fill: new Fill({
    //                     color: '#ffcc33',
    //                 }),
    //             }),
    //         }),
    //     });
    //     map.addLayer(layer);
    // }
    return source;
}

let measureTooltip;
let measureTooltipElement;

/**
 * Creates a new measure tooltip
 */
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

export function toggleMeasure(smap) {
    map = smap;
    const type = 'Polygon';
    let source = addMeasureLayer();
    let draw = new Draw({
        source: source,
        type: type,
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
    map.addInteraction(draw);
    createMeasureTooltip();
    // createHelpTooltip();
    //
    let sketch;
    let listener;
    draw.on('drawstart', function (evt) {
        // set sketch
        sketch = evt.feature;

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

    draw.on('drawend', function () {
        measureTooltipElement.className = 'ol-tooltip ol-tooltip-static';
        measureTooltip.setOffset([0, -7]);
        // unset sketch
        sketch = null;
        // unset tooltip so that a new one can be created
        measureTooltipElement = null;
        createMeasureTooltip();
        unByKey(listener);
    });
}

