import {Modify, Select} from "ol/interaction";
import {formatArea, map, plants} from "./draw_map";
import {pointerMove} from "ol/events/condition";
import {redirect} from "../redirect";
import {swalArea} from "./swal-area";
import Swal from "sweetalert2";
import Overlay from "ol/Overlay";
import {WKT} from "ol/format";
import {Collection} from "ol";


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
        // insertVertexCondition: function () {
        //     // prevent new vertices to be added to the polygons
        //     return (selectClick
        //         .getFeatures()
        //         .getArray()[0].get('status') !== 'created')
        //     // &&
        //     // !selectClick
        //     // .getFeatures()
        //     // .getArray()
        //     // .every(function (feature) {
        //     //     return feature
        //     //         .getGeometry()
        //     //         .getType()
        //     //         .match(/Polygon/);
        //     // });
        // },
        // features: featureModify,
        // insertVertexCondition: () => {
        //     return (feature.get('status') === "created")
        // }
        // insertVertexCondition: () => {
        //     // prevent new vertices to be added to the polygons
        //     return !selectClick.getFeatures().getArray().every(function(feature) {
        //         return feature.getGeometry().getType().match(/Polygon/);
        //     });
        // }
    });
    modifyInteraction.setActive(false)
    modifyInteraction.on('modifystart', function (e) {
        selectClick.setActive(false)
        selectMove.setActive(false)
        let sketch = e.features.getArray()[0];
        sketch.getGeometry().on('change', function (evt) {
            document.getElementById('drawn_area_area').value = formatArea(evt.target);
        });
    })
    modifyInteraction.on("modifyend", function (e) {
        selectClick.setActive(true)
        selectMove.setActive(true)
        document.getElementById('drawn_area_geom').value = new WKT().writeGeometry(e.features.getArray()[0].getGeometry());
    })
    map.getInteractions().extend([selectClick, selectMove, modifyInteraction]);

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
