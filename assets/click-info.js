import Overlay from 'ol/Overlay';
import {redirect} from "./redirect";

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
        let name = selected.get('appl');
        let geom = selected.get('geometry');
        let tooltipCoord = geom.getInteriorPoint().getCoordinates();
        infoTooltipElement.innerHTML = 'Заявник ' + name;
        infoTooltip.setPosition(tooltipCoord);
    } else {
        createInfoTooltip();
    }
}

let reload_url = (e) => {
    let selected = null;
    map.forEachFeatureAtPixel(e.pixel, function (f) {
        selected = f;
        return true;
    });
    if (selected != null) {
        redirect('/appl/' + selected.get('nom'));
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