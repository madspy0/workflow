import Overlay from 'ol/Overlay';
import {Select} from "ol/interaction";
import {redirect} from "./redirect";

let infoTooltip;
let infoTooltipElement;
import {click, pointerMove, never} from 'ol/events/condition';
import {map, plants} from "./draw/draw_map";
import {swalArea} from "./draw/swal-area";
import Swal from "sweetalert2";


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

export function clickInfo() {

    createInfoTooltip();

    const selectClick = new Select({
        layers: [plants],
    });

    const selectMove = new Select({
        condition: pointerMove,
        layers: [plants],
    });

    map.getInteractions().extend([selectClick, selectMove]);

    selectClick.on('select', function (e) {
        let selected = e.target.getFeatures().getArray()[0];
        if (selected != null) {
            if (typeof selected.get('nom') !== 'undefined') {
                redirect('/appl/' + selected.get('nom') + '?cc=' + map.getView().getCenter().join() + '&z=' + map.getView().getZoom());
            } else if (typeof selected.get('number') !== 'undefined') {
                swalArea(selectClick, selectMove)
            }
        } else { if(Swal.isVisible()) {Swal.close()}}
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
