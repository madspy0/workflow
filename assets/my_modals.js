import {Modal} from "bootstrap";

let listener = (e) => {
    e.preventDefault();
    let xhr = new XMLHttpRequest();
    let profileForm = document.getElementById('form_person_edit');
    let formData = new FormData(profileForm);
    xhr.open("POST", '/dr_profile', true);
   // xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
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
export function my_modal(show=false) {
    if (!!document.getElementById('drawmodal')) {
        document.getElementById('drawmodal').parentNode.removeChild(document.getElementById('drawmodal'));
    }
    let xhr = new XMLHttpRequest();
    xhr.open("POST", '/dr_profile', false);
    xhr.onreadystatechange = function () {
        if (this.readyState != 4) return;
        if (xhr.status === 200) {
            let resp = JSON.parse(xhr.response)
            if(resp.success) { return; }
            let mod = document.createElement("div");
            mod.id = 'drawmodal';
            mod.className = "modal fade";
            mod.setAttribute("role", "dialog");
            mod.setAttribute("tabindex", "-1");
            mod.setAttribute("data-backdrop", "static");
            mod.insertAdjacentHTML('beforeend', resp.content);
            document.body.appendChild(mod);
            let profileForm = document.getElementById('form_person_edit');
            profileForm.addEventListener('submit', listener )
            let myModal = Modal.getOrCreateInstance(mod);
            if(show) {
                myModal.show()
            }
        }
    }
    xhr.send();
}
