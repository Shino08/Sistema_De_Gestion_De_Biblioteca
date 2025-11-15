const form = document.querySelectorAll('.Form');

form.forEach((form) => {
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        Swal.fire({
            title: "¿Estas seguro de enviar el formulario?",
            text: "No podrás revertir esto!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, enviarlo!",
            cancelButtonText: "No, cancelar!"
        }).then((result) => {
            if (result.isConfirmed) {
                let data = new FormData(form);
                let method = form.getAttribute('method');
                let action = form.getAttribute('action');

                let encabezados = new Headers();

                let config = {
                    method: method,
                    headers: encabezados,
                    body: data,
                    cache: 'no-cache',
                    mode: 'cors'
                };

                fetch(action, config)
                    .then(response => response.json())
                    .then(response => {
                        return alerts(response);
                    });
            }
        });
    });
});

function alerts(alert) {
    if (alert.type == "simple") {
        Swal.fire({
            icon: alert.icon,
            title: alert.title,
            text: alert.text,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar"
        });
    } else if (alert.type == "recharge") {
        Swal.fire({
            icon: alert.icon,
            title: alert.title,
            text: alert.text,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar"
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        });
    } else if (alert.type == "clean") {
        Swal.fire({
            icon: alert.icon,
            title: alert.title,
            text: alert.text,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar"
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('.Form').reset();
            }
        });
    } else if (alert.type == "redirect") {
        window.location.href = alert.url;
    }
}
