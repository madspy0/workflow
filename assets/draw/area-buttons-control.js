import Swal from "sweetalert2";
import {drawPublish} from "./draw-publish";
function areaButtonsControl(e) {
    e.preventDefault();
    switch (e.target.id) {
        case 'dr_save':
            Swal.clickConfirm();
            break;
        case 'dr_close':
            Swal.clickCancel();
            break;
        case 'dr_publ':
            //console.log(Swal.getHtmlContainer().getElementsByClassName('btn'), e.target.value)
            drawPublish(e.target.value);
            break;
        //    Swal.clickConfirm();
        default:
            break;
    }
}
