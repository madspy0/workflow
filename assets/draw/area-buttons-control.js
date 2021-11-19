import Swal from "sweetalert2";
export function areaButtonsControl(e) {
    e.preventDefault();
    switch(e.target.id) {
        case 'dr_save':
            Swal.clickConfirm();
            break;
        default:
            console.log(e.target.id)
            break;
    }
}