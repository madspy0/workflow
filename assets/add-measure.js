import {Vector as VectorSource} from 'ol/source';
import {Vector as VectorLayer} from 'ol/layer';
import Overlay from 'ol/Overlay';
import {Draw} from 'ol/interaction';
import {Fill, Stroke, Style, Circle} from 'ol/style';
import {getArea} from 'ol/sphere';
import {unByKey} from 'ol/Observable';
import {sourceClear} from "./draw/draw_map";
/**
 * Format area output.
 * @param {Polygon} polygon The polygon.
 * @return {string} Formatted area.
 */
const formatArea = function (polygon) {
    const area = getArea(polygon);
    let output;
    if (area > 10000) {
        output = Math.round((area / 1000000) * 100) / 100 + ' ' + 'км<sup>2</sup>';
    } else {
        output = Math.round(area * 100) / 100 + ' ' + 'м<sup>2</sup>';
    }
    return output;
};

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

export function toggleMeasure(smap, status) {
    map = smap;
    // let areaButton = new Button(document.getElementsByClassName('btn-edits')[1]);
    // areaButton.toggle();
    // areaButton.classList.toggle('active');
    // areaButton.classList.toggle('focus');
    // areaButton.classList.toggle('hover');
    if(!status) {
        map.getInteractions().forEach((interaction) => {
            if (interaction instanceof Draw) {
                map.removeInteraction(interaction);
                // let source = addMeasureLayer();
                // source.clear();
                sourceClear();
                map.getOverlays().getArray().slice(0).forEach(function (overlay) {
                    map.removeOverlay(overlay);
                });
            }
        });
    } else {
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
}
