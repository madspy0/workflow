import {map, plants} from "./draw_map";
import {Select} from "ol/interaction";
import Swal from "sweetalert2";
import {toastFire} from "./swal-area";

export async function dropPlane(feature) {
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
        },
        confirmButtonText: 'Видалити',
        cancelButtonText: 'Вiдмiнити'
    }).then((willDelete) => {
        if (willDelete.isConfirmed) {
            fetch('/dr_drop/' + feature.get('number'), {
                headers: new Headers({'content-type': 'application/json'}),
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(Promise.reject.bind(Promise));
                        //   throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .then(data => {
                    if (data.success) {
                        plants.getSource().removeFeature(feature);
                        document.body.style.cursor = "default";
                        map.getInteractions().forEach(function (interaction) {
                            if (interaction instanceof Select) {
                                interaction.getFeatures().clear();
                            }
                        }, this);
                        Swal.close();
                    }
                })
                .catch(error => toastFire(error, false))
        }
    })
}