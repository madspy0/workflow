import {Control} from 'ol/control';
import './add-measure';
import {toggleMeasure} from "./add-measure";
import {clickInfo} from "./click-info";

class EditButtonsControl extends Control {
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

        const element = document.createElement('div');
        element.className = 'ol-unselectable ol-control edit-buttons';
        element.appendChild(infoButton);
        element.appendChild(areaButton);
        super({
            element: element,
            target: options.target,
        });

        infoButton.addEventListener('click', this.handleInfo.bind(this), false);
        areaButton.addEventListener('click', this.handleArea.bind(this), false);
    }

    handleArea(evt) {
        evt.preventDefault();
 //       let areaButton = new Button(evt.currentTarget);
 //       areaButton.toggle();
 //       console.log(areaButton)
        toggleMeasure(this.getMap());
    }

    handleInfo(evt) {
        evt.preventDefault();
        clickInfo(this.getMap())
    }

}

export default EditButtonsControl;
