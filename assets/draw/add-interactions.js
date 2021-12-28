import {Modify, Select, Snap} from "ol/interaction";
import {formatArea, map, plants} from "./draw_map";
import {pointerMove} from "ol/events/condition";
import {redirect} from "../redirect";
import {swalArea} from "./swal-area";
import Swal from "sweetalert2";
import Overlay from "ol/Overlay";
import {WKT} from "ol/format";
import {transform} from "ol/proj";


let infoTooltip;
let infoTooltipElement;

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

export function addInteractions() {
    const selectClick = new Select({
        layers: [plants],
    });
    selectClick.setActive(false);
    const selectMove = new Select({
        condition: pointerMove,
        layers: [plants],
    });
    selectMove.setActive(false)

    // let getSelectedCreatedFeatures = () => {
    //     let ret = new Collection();
    //     selectClick.getFeatures().forEach(f => { if(f.get('status') === 'created') {ret.push(f)} });
    //     return ret;
    // }
    const modifyInteraction = new Modify({
        //    source: plants.getSource(),
        features: selectClick.getFeatures(),
    });
    modifyInteraction.setActive(false)
    modifyInteraction.on('modifystart', function (e) {
        selectClick.setActive(false)
        selectMove.setActive(false)
        let sketch = e.features.getArray()[0];
        sketch.getGeometry().on('change', function (evt) {
            let temp = formatArea(evt.target);
            document.getElementById('drawn_area_area').value = temp;
        });
    })
    modifyInteraction.on("modifyend", function (e) {
        selectClick.setActive(true)
        selectMove.setActive(true)
        e.features.getArray()[0].set('area',document.getElementById('drawn_area_area').value);
        document.getElementById('drawn_area_geom').value = new WKT().writeGeometry(e.features.getArray()[0].getGeometry());
    })

    const snapInteraction = new Snap({
        features: selectClick.getFeatures(),
    });
    snapInteraction.setActive(false)
    map.getInteractions().extend([selectClick, selectMove, modifyInteraction, snapInteraction]);

    createInfoTooltip();
    selectClick.on('select', function (e) {
        let selected = e.target.getFeatures().getArray()[0];
        if (selected != null) {
            if (typeof selected.get('nom') !== 'undefined') {
                redirect('/appl/' + selected.get('nom') + '?cc=' + map.getView().getCenter().join() + '&z=' + map.getView().getZoom());
            } else {
                if (typeof selected.get('number') !== 'undefined') {
                    swalArea(selected)
                }
            }
        } else {
            if (Swal.isVisible()) {
                Swal.close()
            }
        }
    });
    selectMove.on('select', function (e) {
            let selected = selectMove.getFeatures().getArray()[0];
            if (selected != null) {
                document.body.style.cursor = 'pointer';
                let name = selected.get('appl') + selected.get('area');
                let geom = selected.get('geometry');
                if (geom) {
                    let tooltipCoord = geom.getInteriorPoint().getCoordinates();
                    infoTooltipElement.innerHTML = name;
                    infoTooltip.setPosition(tooltipCoord);
                } else {
                    document.body.style.cursor = 'default';
                    createInfoTooltip();
                }
            } else {
                document.body.style.cursor = 'default';
                createInfoTooltip();
            }

    })
}
