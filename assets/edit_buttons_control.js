import {Control} from 'ol/control';

class EditButtonsControl extends Control {
    constructor(opt_options) {
        const options = opt_options || {};

        const button = document.createElement('button');
        button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">\n' +
            '<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>\n' +
            '<path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>\n' +
            '</svg>';

        const drawButton = document.createElement('button');

        drawButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-palette" viewBox="0 0 16 16">\n' +
            '  <path d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>\n' +
            '  <path d="M16 8c0 3.15-1.866 2.585-3.567 2.07C11.42 9.763 10.465 9.473 10 10c-.603.683-.475 1.819-.351 2.92C9.826 14.495 9.996 16 8 16a8 8 0 1 1 8-8zm-8 7c.611 0 .654-.171.655-.176.078-.146.124-.464.07-1.119-.014-.168-.037-.37-.061-.591-.052-.464-.112-1.005-.118-1.462-.01-.707.083-1.61.704-2.314.369-.417.845-.578 1.272-.618.404-.038.812.026 1.16.104.343.077.702.186 1.025.284l.028.008c.346.105.658.199.953.266.653.148.904.083.991.024C14.717 9.38 15 9.161 15 8a7 7 0 1 0-7 7z"/>\n' +
            '</svg>';

        const element = document.createElement('div');
        element.className = 'ol-unselectable ol-control edit-buttons';
        element.appendChild(button);
        element.appendChild(drawButton);

        super({
            element: element,
            target: options.target,
        });

        button.addEventListener('click', this.handleRotateNorth.bind(this), false);
    }

    handleRotateNorth() {
        console.log('ok')
     //   this.getMap().getView().setRotation(0);
    }
}
export default EditButtonsControl;