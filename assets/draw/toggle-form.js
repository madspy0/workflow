// Disables a form.
export function toggle_form(form) {
    let inputs = form.getElementsByTagName('input'),
        textareas = form.getElementsByTagName('textarea'),
     //   buttons = form.getElementsByTagName('button'),
        selects = form.getElementsByTagName('select');

    toggle_elements(inputs);
    toggle_elements(textareas);
   // toggle_elements(buttons);
    toggle_elements(selects);
}

function toggle_elements(elements) {
    let length = elements.length;
    while(length--) {
        elements[length].disabled = !elements[length].disabled;
    }
}
