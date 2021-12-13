import {Draw, Select} from 'ol/interaction';
import {Fill, Stroke, Style, Circle} from 'ol/style';
import {map, plants} from "./draw_map";

import {pointerMove} from "ol/events/condition";
import {Collection} from "ol";
import {swalArea} from "./swal-area";
import Swal from "sweetalert2";

 function toggleDraw() {

    const type = 'Polygon';
    if(Swal.isVisible()) {Swal.close()}
    let draw = new Draw({
        source: plants.getSource(),
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

    // draw.on('drawstart', function (evt){
    //     evt.preventDefault()
    //     map.getInteractions().forEach(f => {
    //         if(f instanceof Select) {
    //             f.setActive(false)
    //         }
    //     })
    // })

    draw.on('drawend', function (evt) {
        evt.preventDefault();
        let feature = evt.feature;
        feature.set('number', 'new');
        feature.set('status', 'created')
        feature.set('appl','доданий')
        // map.getInteractions().forEach(f => {
        //     if(f instanceof Select) {
        //         f.setActive(false)
        //     }
        // })
        let features = new Collection();
    //    plants.getSource().addFeature(feature);
        features.push(feature)
        const selectClick = new Select({
            features: features,
            layers: [plants],
        });

        const selectMove = new Select({
            condition: pointerMove,
            layers: [plants],
        });

        map.getInteractions().extend([selectClick, selectMove]);
        // draw.finishDrawing()
        // map.removeInteraction(draw)
        swalArea(selectClick, selectMove);

    });

}
