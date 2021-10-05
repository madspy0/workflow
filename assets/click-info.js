import Overlay from 'ol/Overlay';

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
    infoTooltipElement = document.createElement('div');
    infoTooltipElement.className = 'ol-tooltip ol-tooltip-measure';
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
    let selected = null;
    map.on('pointermove', function (e) {
        if (selected !== null) {
            selected.setStyle(undefined);
            selected = null;
        }

        map.forEachFeatureAtPixel(e.pixel, function (f) {
            selected = f;
            //f.setStyle(highlightStyle);
//            console.log(f.get('appl'))
            return true;
        });

        if (selected) {
            let name = selected.get('appl');
            console.log(name)
            infoTooltipElement.className = 'ol-tooltip ol-tooltip-static';
            infoTooltip.setOffset([0, -7]);
            let geom = selected.get('geometry');
            let tooltipCoord = geom.getInteriorPoint().getCoordinates();
            infoTooltip.innerHTML = "selected " + name;
            infoTooltip.setPosition(tooltipCoord);
        } else {
            // infoTooltipElement = null;
            // createInfoTooltip();
        }
    });

}
