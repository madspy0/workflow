import {Control} from 'ol/control';
//import {toggleMeasure} from "../add-measure";
//import {clickInfo} from "../click-info";
import {map, measureLayer} from "./draw_map";
import {Draw, Select} from "ol/interaction";
import Swal from "sweetalert2";

function clearLayers() {
    measureLayer.getSource().clear();
    map.getOverlays().getArray().slice(0).forEach(function (overlay) {
        map.removeOverlay(overlay);
    });
}
class DrawButtonsControl extends Control {
    constructor(opt_options) {
        const options = opt_options || {};

        const infoButton = document.createElement('button');
        infoButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">\n' +
            '<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>\n' +
            '<path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>\n' +
            '</svg>';
        infoButton.dataset.bsToggle = 'button';
        infoButton.dataset.bsPlacement = 'left';
        infoButton.title = 'Info';
        infoButton.className = 'btn btn-edit';
        const areaButton = document.createElement('button');
        areaButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bounding-box" viewBox="0 0 16 16">\n' +
            '<path d="M5 2V0H0v5h2v6H0v5h5v-2h6v2h5v-5h-2V5h2V0h-5v2H5zm6 1v2h2v6h-2v2H5v-2H3V5h2V3h6zm1-2h3v3h-3V1zm3 11v3h-3v-3h3zM4 15H1v-3h3v3zM1 4V1h3v3H1z"/>\n' +
            '</svg>';
        areaButton.dataset.bsToggle = 'button';
        areaButton.dataset.bsPlacement = 'left';
        areaButton.className = 'btn btn-edit';
        areaButton.title = 'Площа';

        const drawButton = document.createElement('button');
        drawButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-palette" viewBox="0 0 16 16">\n' +
            '  <path d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>\n' +
            '  <path d="M16 8c0 3.15-1.866 2.585-3.567 2.07C11.42 9.763 10.465 9.473 10 10c-.603.683-.475 1.819-.351 2.92C9.826 14.495 9.996 16 8 16a8 8 0 1 1 8-8zm-8 7c.611 0 .654-.171.655-.176.078-.146.124-.464.07-1.119-.014-.168-.037-.37-.061-.591-.052-.464-.112-1.005-.118-1.462-.01-.707.083-1.61.704-2.314.369-.417.845-.578 1.272-.618.404-.038.812.026 1.16.104.343.077.702.186 1.025.284l.028.008c.346.105.658.199.953.266.653.148.904.083.991.024C14.717 9.38 15 9.161 15 8a7 7 0 1 0-7 7z"/>\n' +
            '</svg>';
        drawButton.dataset.bsToggle = 'button';
        drawButton.dataset.bsPlacement = 'left';
        drawButton.title = 'Draw';
        drawButton.className = 'btn btn-edit';

        const element = document.createElement('div');
        element.className = 'ol-unselectable ol-control edit-buttons';
        element.appendChild(infoButton);
        element.appendChild(drawButton);
        element.appendChild(areaButton);
        super({
            element: element,
            target: options.target,
        });

        infoButton.addEventListener('click', this.handleInfo.bind(this), false);
        areaButton.addEventListener('click', this.handleArea.bind(this), false);
        drawButton.addEventListener('click', this.handleDraw.bind(this), false);


    }

    toggleButtons(elem) {
        let buttons = document.getElementsByClassName("btn-edit");
        for (let b of buttons) {
            if (elem !== b) {
                b.classList.remove("active");
            }
        }
    }

    handleArea(evt) {
        evt.preventDefault();
        clearLayers();
        this.toggleButtons(evt.currentTarget);
        if (evt.currentTarget.classList.contains('active')) {
            if(Swal.isVisible()){Swal.close()}
            map.getInteractions().forEach(f => {
                if (f instanceof Select) {
                    f.setActive(false)
                }
                if (f instanceof Draw) {
                    if(f.getProperties().name=='measu') {
                        f.setActive(true)
                    } else {
                        f.setActive(false)
                    }
                }
            })
        } else {
            clearLayers();
            map.getInteractions().forEach(f => {
                if (f instanceof Select) {
                    f.setActive(false)
                }
                if (f instanceof Draw) {
                    f.setActive(false)
                }
            })
        }
    }

    handleInfo(evt) {
        evt.preventDefault();
        clearLayers();
        this.toggleButtons(evt.currentTarget);
        if (evt.currentTarget.classList.contains('active')) {
            map.getInteractions().forEach(f => {
                if (f instanceof Select) {
                    f.setActive(true)
                }
                if (f instanceof Draw) {
                    f.setActive(false)
                }
            })
        } else {
            map.getInteractions().forEach(f => {
                if (f instanceof Select) {
                    f.setActive(false)
                }
                if (f instanceof Draw) {
                    f.setActive(false)
                }
            })
        }
    }

    handleDraw(evt) {
        evt.preventDefault();
        clearLayers();
        this.toggleButtons(evt.currentTarget);
        if (evt.currentTarget.classList.contains('active')) {
            map.getInteractions().forEach(f => {
                if (f instanceof Select) {
                    f.setActive(false)
                }
                if(f.getProperties().name=='measu') {
                    f.setActive(false)
                } else {
                    f.setActive(true)
                }
            })
        } else {
            map.getInteractions().forEach(f => {
                if (f instanceof Select) {
                    f.setActive(false)
                }
                if (f instanceof Draw) {
                    f.setActive(false)
                }
            })
        }
    }

}

export default DrawButtonsControl;
