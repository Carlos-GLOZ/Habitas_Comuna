

// Formularios
const formDelComunidad = document.getElementById('form-del-comunidad');
const formAbandonarComunidad = document.getElementById('form-abandonar-comunidad');
const formDetallesComunidad = document.getElementById('form-detalles-comunidad');

// Botones
const botonesDelComunidad = document.getElementsByClassName('boton-del-comunidad')
const botonesAbandonarComunidad = document.getElementsByClassName('boton-abandonar-comunidad')
const botonesDetallesComunidadEditar = document.getElementsByClassName('boton-detalles-comunidad-editar')
const botonesDetallesComunidadInfo = document.getElementsByClassName('boton-detalles-comunidad-info')

// Modales
const modalComunidadPresidente = document.getElementById('caja-modal-comunidadEdit');
const modalComunidadInfo = document.getElementById('caja-modal-comunidadInfo');

// Event listeners de los formularios
for (let i = 0; i < botonesDelComunidad.length; i++) {
    const boton = botonesDelComunidad[i];

    boton.addEventListener('click', (e) => {
        e.preventDefault();

        Swal.fire({
            title: trans("Remove community?"),
            text: trans("This action is not able to be reversed"),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: trans("Confirm"),
            cancelButtonText: trans("Cancel")
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-del-comunidad-id').value = boton.value;
                formDelComunidad.submit();
            }
        })

    })
}

for (let i = 0; i < botonesAbandonarComunidad.length; i++) {
    const boton = botonesAbandonarComunidad[i];

    boton.addEventListener('click', (e) => {
        e.preventDefault();

        Swal.fire({
            title: trans("Leave community?"),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: trans("Confirm"),
            cancelButtonText: trans("Cancel")
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-abandonar-comunidad-id').value = boton.value;
                formAbandonarComunidad.submit();
            }
        })

    })
}

for (let i = 0; i < botonesDetallesComunidadEditar.length; i++) {
    const boton = botonesDetallesComunidadEditar[i];

    boton.addEventListener('click', async(e) => {

        e.preventDefault()

        abrirModalComunidadEditar(boton.value);
    })
}

for (let i = 0; i < botonesDetallesComunidadInfo.length; i++) {
    const boton = botonesDetallesComunidadInfo[i];

    boton.addEventListener('click', async(e) => {

        e.preventDefault()

        abrirModalComunidadInfo(boton.value)
    })
}

// Abrir modales
async function abrirModalComunidadEditar(id) {
    // Popular formulario con id de la comunidad
    const id_El = document.getElementById('form-detalles-comunidad-id');

    id_El.value = id;

    let comunidad = await axios.post(formDetallesComunidad.action, new FormData(formDetallesComunidad));
    comunidad = comunidad.data;

    // Poblar modal con la info del evento
    let campos = modalComunidadPresidente.getElementsByClassName('modal-editar-comunidad-campo');

    // Mostrar imagen
    const imagenEl = document.getElementById('img-modal-comunidad-editar');
    let imgRuta = imagenEl.dataset.ruta + '/' + comunidad.id + '.png';
    // Para comprobar si el archivo existe, hay que hacer una petición; si falla, el archivo no existe
    try {
        let response = await axios.get(imgRuta);

        imagenEl.style.backgroundImage = "url(" + imgRuta + ")";
    } catch (error) {
        imagenEl.style.backgroundImage = "url(" + imagenEl.dataset.ruta + '/default.png' + ")";
    }

    for (let i = 0; i < campos.length; i++) {
        const campo = campos[i];
        // Poblar selects
        if (campo.dataset.camposelects) {
            const config = JSON.parse(campo.dataset.camposelects);

            // Eliminar elementos existentes
            // Recoger el elemento nullo, si lo hay; vaciar el select y añadir el elemento nulo, si lo hay
            let elementoNulo = null;
            for (let i = 0; i < campo.childElementCount; i++) {
                const option = campo.children[i];
                if (option.value == 'null') {
                    elementoNulo = option;
                }
            }

            campo.innerHTML = '';

            if (elementoNulo) {
                campo.appendChild(elementoNulo);
            }

            for (let i = 0; i < comunidad[config.campo].length; i++) {
                const elemento = comunidad[config.campo][i];

                const option = document.createElement('option');
                option.value = elemento[config.value];
                option.innerText = elemento[config.text];

                campo.appendChild(option)
            }
        }

        if (campo.name in comunidad) {
            campo.value = comunidad[campo.name];
        }
    }

    // Cambiar valor de ver con vista de usuario
    const botonesVistaUsuario = modalComunidadPresidente.getElementsByClassName('boton-vista-miembro');

    for (let i = 0; i < botonesVistaUsuario.length; i++) {
        const boton = botonesVistaUsuario[i];

        boton.value = comunidad.id;
    }

    modalComunidadPresidente.classList.add('caja-modal-eventoInfo-abierta');
}

async function abrirModalComunidadInfo(id) {
    // Popular formulario con id de la comunidad
    const id_El = document.getElementById('form-detalles-comunidad-id');

    id_El.value = id;

    // Popular contenidos
    let comunidad = await axios.post(formDetallesComunidad.action, new FormData(formDetallesComunidad));
    comunidad = comunidad.data;

    // Poblar modal con la info del evento
    let campos = modalComunidadInfo.getElementsByClassName('modal-info-comunidad-campo');

    // Mostrar imagen. Si el archivo no se encuentra, mostrar imagen por defecto
    const imagenEl = document.getElementById('img-modal-comunidad-info');
    let imgRuta = imagenEl.dataset.ruta + '/' + comunidad.id + '.png'
        // Para comprobar si el archivo existe, hay que hacer una petición; si falla, el archivo no existe
    try {
        let response = await axios.get(imgRuta);

        imagenEl.src = imgRuta;
    } catch (error) {
        imagenEl.src = imagenEl.dataset.ruta + '/default.png';
    }

    for (let i = 0; i < campos.length; i++) {
        const campo = campos[i];
        const config = JSON.parse(campo.dataset.campo);

        if (!config.atributo) {
            config.atributo = 'innerText'
        }

        try {
            if (config.columna) {
                campo[config.atributo] = comunidad[config.campo][config.columna];
            } else {
                campo[config.atributo] = comunidad[config.campo];
            }
        } catch (error) {
            campo[config.atributo] = '-';
        }
    }

    modalComunidadInfo.classList.add('caja-modal-eventoInfo-abierta');
}

// Cerrar modales

// Cerrar modal editar comunidad
const botonCerrarModalComunidadPresidente = document.getElementById('cerrar-modal-comunidad-presidente');

botonCerrarModalComunidadPresidente.addEventListener('click', (e) => {
    cerrarModalComunidadPresidente();
})

function cerrarModalComunidadPresidente() {
    modalComunidadPresidente.classList.remove('caja-modal-eventoInfo-abierta');
}

// Cerrar modal editar comunidad
const botonCerrarModalComunidadInfo = document.getElementById('cerrar-modal-comunidad-info');

botonCerrarModalComunidadInfo.addEventListener('click', (e) => {
    cerrarModalComunidadInfo();
})

function cerrarModalComunidadInfo() {
    modalComunidadInfo.classList.remove('caja-modal-eventoInfo-abierta');
}

// Control de drag&drop
const dropZone = document.getElementById('img-modal-comunidad-editar');
const inputElement = document.getElementById('comunidad-img-input');
const img = dropZone;

inputElement.addEventListener('change', function(e) {
    const clickFile = this.files[0];
    if (clickFile) {
        const reader = new FileReader();
        reader.readAsDataURL(clickFile);
        reader.onloadend = function() {
            const result = reader.result;
            let src = this.result;
            img.style.backgroundImage = "url(" + src + ")";
        }
    }
})

dropZone.addEventListener('click', (e) => {
    e.preventDefault();
    inputElement.click()
});
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();

    dropZone.style.boxShadow = "#5454ce 0px 0px 20px 0px";
});
dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();

    dropZone.style.boxShadow = "none";
});
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    let file = e.dataTransfer.files[0];

    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = function() {
        e.preventDefault()
        let src = this.result;
        img.style.backgroundImage = "url(" + src + ")";

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        inputElement.files = dataTransfer.files;
    }
});

// Control de botones de vista de usuario
const botonesVistaUsuario = document.getElementsByClassName('boton-vista-miembro');

for (let i = 0; i < botonesVistaUsuario.length; i++) {
    const boton = botonesVistaUsuario[i];

    boton.addEventListener('click', (e) => {
        e.preventDefault();

        cerrarModalComunidadPresidente();
        abrirModalComunidadInfo(boton.value);
    })
}
