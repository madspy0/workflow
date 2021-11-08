import {Vector as VectorSource} from 'ol/source';
import {Vector as VectorLayer} from 'ol/layer';
import Overlay from 'ol/Overlay';
import {Draw} from 'ol/interaction';
import {Fill, Stroke, Style, Circle} from 'ol/style';
import {Modal} from "bootstrap";
import {WKT} from "ol/format";
import {sourceClear} from "./draw_map";
import Litepicker from 'litepicker';
import {my_toast} from "../my_toasts";

let map;

function addDrawLayer() {
    let source;
    map.getLayers().forEach(function (el) {
        if (el.get('name') === 'drawn') {
            source = el.getSource();
        }
    })
    if (!source) {
        source = new VectorSource();
        let layer = new VectorLayer({
            name: 'drawn',
            source: source,
            style: new Style({
                fill: new Fill({
                    color: 'rgba(255, 255, 255, 0.2)',
                }),
                stroke: new Stroke({
                    color: '#ffcc33',
                    width: 2,
                }),
                image: new Circle({
                    radius: 7,
                    fill: new Fill({
                        color: '#ffcc33',
                    }),
                }),
            }),
        });
        map.addLayer(layer);
    }
    return source;
}

export function toggleDraw(smap, status) {
    map = smap;
    // let areaButton = new Button(document.getElementsByClassName('btn-edits')[1]);
    // areaButton.toggle();
    // areaButton.classList.toggle('active');
    // areaButton.classList.toggle('focus');
    // areaButton.classList.toggle('hover');
    if (!status) {
        map.getInteractions().forEach((interaction) => {
            if (interaction instanceof Draw) {
                map.removeInteraction(interaction);
                // let source = addDrawLayer();
                // source.clear();
                sourceClear();
                map.getOverlays().getArray().slice(0).forEach(function (overlay) {
                    map.removeOverlay(overlay);
                });
            }
        });
    } else {
        const type = 'Polygon';
        let source = addDrawLayer();
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
        draw.on('drawend', function (evt) {
            let feature = evt.feature;
            let geom = new WKT().writeGeometry(feature.getGeometry(feature.getGeometry()));
            //
            //myModal.show();
            let xhr = new XMLHttpRequest();
            xhr.open("POST", '/dr_add', true);
            xhr.responseType = 'json';
            //        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (this.readyState != 4) return;
                //myModal.innerHTML(this.response.content);
                //           myModal.show();
                //           alert(this.response.content);
                my_toast(this.response.content, geom, feature, map, 'add');
            }
            xhr.send();
        });


        // myModal.addEventListener('show.bs.modal', function (event) {
        //     if (!data) {
        //         return event.preventDefault() // stops modal from being shown
        //     }
        // })
    }
}
