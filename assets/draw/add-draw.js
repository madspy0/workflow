import {Vector as VectorSource} from 'ol/source';
import {Vector as VectorLayer} from 'ol/layer';
import Overlay from 'ol/Overlay';
import {Draw} from 'ol/interaction';
import {Fill, Stroke, Style, Circle} from 'ol/style';
import {Modal} from "bootstrap";
import {WKT} from "ol/format";

let map;

function addDrawLayer() {
    let source;
    map.getLayers().forEach(function (el) {
        if (el.get('name') === 'plants') {
            source = el.getSource();
        }
    })
    // if (!source) {
    //     source = new VectorSource();
    //     let layer = new VectorLayer({
    //         name: 'draw_layer',
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

export function toggleDraw(smap, status) {
    map = smap;
    let active = true;
    // let areaButton = new Button(document.getElementsByClassName('btn-edits')[1]);
    // areaButton.toggle();
    // areaButton.classList.toggle('active');
    // areaButton.classList.toggle('focus');
    // areaButton.classList.toggle('hover');
    map.getInteractions().forEach((interaction) => {
        if (interaction instanceof Draw) {
            map.removeInteraction(interaction);
            let source = addDrawLayer();
            source.clear();
            active = false;
        }
    });
    if (active && status) {
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
            document.getElementById('drawn_area_geom').value = geom;
            const myModal = new Modal(document.getElementById('draw_modal'), {
                backdrop: true
            })
            myModal.show();
        });
    }
}
