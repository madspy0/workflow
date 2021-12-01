import Swal from "sweetalert2";

document.getElementsByClassName('account-button').forEach(b=>{
    b.addEventListener('click',  e => {
        e.preventDefault()
        let button = e.currentTarget;
        fetch('/account/enable/'+e.currentTarget.value)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(Promise.reject.bind(Promise));
                }
                return response.json()})
            .then(data => {
                if(data.status == 'enabled') {
                    button.classList.remove('active')
                } else {
                    button.classList.add('active')
                }
            })
            .catch(error => {
               console.log(
                    `Помилка запиту: ${error}`
                )
            })
    })
})


