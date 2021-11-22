import {Draw, Select} from "ol/interaction";
import {formatArea, map, measureLayer, plants} from "./draw_map";
import Overlay from "ol/Overlay";
import {Circle, Fill, Stroke, Style} from "ol/style";
import {unByKey} from "ol/Observable";

export function addInteractionMeasure() {
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
    const type = 'Polygon';
    let source = measureLayer.getSource();
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
    draw.setProperties({name: 'measu'})
    draw.setActive(false);
    map.getInteractions().extend([draw]);

    // createHelpTooltip();
    //
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
