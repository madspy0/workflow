import {Modal} from "bootstrap";

let listener = (e) => {
    e.preventDefault();
    let xhr = new XMLHttpRequest();
    let profileForm = document.getElementById('form_person_edit');
    let formData = new FormData(profileForm);
    xhr.open("POST", '/dr_profile', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (this.readyState != 4) return;
        if (xhr.status === 200) {
            let myModal = Modal.getOrCreateInstance(document.getElementById('drawmodal'));
            myModal.hide();
            profileForm.reset();
        }
    }
    xhr.send(formData);
}
export function my_modal() {
    let clear = document.getElementById('draw_modal');
    if (clear) {
        clear.remove();
    }
    let xhr = new XMLHttpRequest();
    xhr.open("POST", '/dr_profile', true);
    xhr.onreadystatechange = function () {
        if (this.readyState != 4) return;
        if (xhr.status === 200) {
            let mod = document.createElement("div");
            mod.id = 'drawmodal';
            mod.className = "modal fade";
            mod.setAttribute("role", "dialog");
            mod.setAttribute("tabindex", "-1");
            mod.setAttribute("data-backdrop", "static");
            mod.insertAdjacentHTML('beforeend', xhr.response.content);
            document.body.appendChild(mod);
            let profileForm = document.getElementById('form_person_edit');
            profileForm.addEventListener('submit', listener )
        }
    }
    xhr.send(null);
}
