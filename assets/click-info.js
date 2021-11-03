import Overlay from 'ol/Overlay';
import {redirect} from "./redirect";
import {update_draw} from "./draw/update";
let map;
let infoTooltip;
let infoTooltipElement;

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

let tooltip_on_move = (e) => {
    let selected = null;
    map.forEachFeatureAtPixel(e.pixel, function (f) {
        selected = f;
        return true;
    });
    if (selected != null) {
        document.body.style.cursor = 'pointer';
        let name = selected.get('appl');
        let geom = selected.get('geometry');
        let tooltipCoord = geom.getInteriorPoint().getCoordinates();
        let status = selected.get('status');
        infoTooltipElement.innerHTML = name + " " + status;
        infoTooltip.setPosition(tooltipCoord);
    } else {
        document.body.style.cursor = 'default';
        createInfoTooltip();
    }
}

let reload_url = (e) => {
    let toast = document.getElementById('draw_toast');
    if((toast !== null) && toast.classList.contains('show')) { return; }
    let selected = null;
    map.forEachFeatureAtPixel(e.pixel, function (f) {
        selected = f;
        return true;
    });
    if (selected != null) {
        if (typeof selected.get('nom') !== 'undefined') {
            redirect('/appl/' + selected.get('nom') + '?cc=' + map.getView().getCenter().join() + '&z=' + map.getView().getZoom());
        } else if (typeof selected.get('number') !== 'undefined'){
            update_draw(selected, map);
        }

    }
}

export function clickInfo(smap, status) {
    map = smap;
    createInfoTooltip();
    if (status) {
        map.on('pointermove', tooltip_on_move);
        map.on('click', reload_url);
    } else {
        map.un('pointermove', tooltip_on_move);
        map.un('click', reload_url);
    }
}
