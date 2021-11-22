import {map, plants} from "./draw_map";
import {Select} from "ol/interaction";
import Swal from "sweetalert2";

export async function dropPlane(feature, modify) {
    await Swal.fire({
        title: "Ви впевнені?",
        text: "Після видалення ви не зможете відновити дані на цю ділянку",
        icon: "warning",
        showCancelButton: true,
        willClose: () => {
            map.getInteractions().forEach(f => {
                if (f instanceof Select) {
                    f.getFeatures().clear()
                }
            })
            map.removeInteraction(modify);
        },
        confirmButtonText: 'Видалити',
        cancelButtonText: 'Скасувати'
    }).then((willDelete) => {
            if (willDelete.isConfirmed) {

               fetch('/dr_drop/' + feature.get('number'))
                   .then(response => response.json())
                   .then(data => {
                       if(data.success) {
                           plants.getSource().removeFeature(feature);
                           document.body.style.cursor = "default";
                           map.getInteractions().forEach(function (interaction) {
                               if (interaction instanceof Select) {
                                   interaction.getFeatures().clear();
                               }
                           }, this);

                           map.removeInteraction(modify);
                           Swal.close();
                       }
                   })
            }
        });
}