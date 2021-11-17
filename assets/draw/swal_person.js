import Swal from "sweetalert2";
export let swal_person = () => {
    fetch('/dr_profile/')
        .then(response => response.json())
        .then((data) => {
            Swal.fire({
                title: 'Submit your Github username',
                html: data.content,
                showCancelButton: true,
                confirmButtonText: 'Look up',
                showLoaderOnConfirm: true,
                // preConfirm: (login) => {
                //     return fetch(`//api.github.com/users/${login}`)
                //         .then(response => {
                //             if (!response.ok) {
                //                 throw new Error(response.statusText)
                //             }
                //             return response.json()
                //         })
                //         .catch(error => {
                //             Swal.showValidationMessage(
                //                 `Request failed: ${error}`
                //             )
                //         })
                // },
                allowOutsideClick: () => !Swal.isLoading()
            })
            //     .then((result) => {
            //     if (result.isConfirmed) {
            //         Swal.fire({
            //             title: `${result.value.login}'s avatar`,
            //             imageUrl: result.value.avatar_url
            //         })
            //     }
            // })
        })
}

