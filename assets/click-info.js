import Overlay from 'ol/Overlay';
import {Select} from "ol/interaction";
import {redirect} from "./redirect";
import {update_draw} from "./draw/update";

let map;
let infoTooltip;
let infoTooltipElement;
import {click, pointerMove} from 'ol/events/condition';

/**
 * Creates a new info tooltip
 */
function createInfoTooltip() {
    if (infoTooltipElement) {
        infoTooltipElement.parentNode.removeChild(infoTooltipElement);
    }
    map.getOverlays().getArray().slice(0).forEach(function (overlay) {
        map.removeOverlay(overlay);
    });
    infoTooltipElement = document.createElement('div');
    infoTooltipElement.className = 'ol-tooltip';
    infoTooltip = new Overlay({
        element: infoTooltipElement,
        offset: [0, -15],
        positioning: 'bottom-center',
        stopEvent: false,
        insertFirst: false,
    });
    map.addOverlay(infoTooltip);
}

export function clickInfo(smap) {
    map = smap;
    createInfoTooltip();
    const selectClick = new Select({
        condition: click,
        // filter: function (feature, layer) {
        //     if (layer.get('name') === 'plants') {
        //         return true;
        //     }
        // }
    });

    const selectMove = new Select({
        condition: pointerMove,
        // filter: function (feature, layer) {
        //     if (layer.get('name') && (layer.get('name') === 'plants')) {
        //         return true;
        //     }
        //},
    });

    map.getInteractions().extend([selectClick, selectMove]);

    selectClick.on('select', function (e) {
        let toast = document.getElementById('draw_toast');
        if ((toast !== null) && toast.classList.contains('show')) {
            return;
        }

        let selected = selectClick.getFeatures().getArray()[0];
        if (selected != null) {
            if (typeof selected.get('nom') !== 'undefined') {
                redirect('/appl/' + selected.get('nom') + '?cc=' + map.getView().getCenter().join() + '&z=' + map.getView().getZoom());
            } else if (typeof selected.get('number') !== 'undefined') {
                update_draw(selected, map);
            }
        }
    });
    selectMove.on('select', function (e) {
        let toast = document.getElementById('draw_toast');
        if (!((toast !== null) && toast.classList.contains('show'))) {

            let selected = selectMove.getFeatures().getArray()[0];
            if (selected != null) {
                document.body.style.cursor = 'pointer';
                let name = selected.get('appl');
                let geom = selected.get('geometry');
                if (geom) {
                    let tooltipCoord = geom.getInteriorPoint().getCoordinates();
                    let status = selected.get('status');
                    infoTooltipElement.innerHTML = name + " " + status;
                    infoTooltip.setPosition(tooltipCoord);
                } else {
                    document.body.style.cursor = 'default';
                    createInfoTooltip();
                }
            } else {
                document.body.style.cursor = 'default';
                createInfoTooltip();
            }

        }

    })
}
