// Botones
const botonesEditar = document.getElementsByClassName('seguro-editar-boton');
const botonesEliminar = document.getElementsByClassName('seguro-eliminar-boton');
const botonLimpiarFormulario = document.getElementById('boton-limpiar-formulario-seguro');

// Formularios
const formularioSeguro = document.getElementById('form-crear-seguro');

// Poblar formulario de editar seguro
async function poblarSeguroEditar(id) {
    // Recoger info de seguro
    const response = await axios.get(formularioSeguro.dataset.rutafind.replace('replaceable', id));
    const seguro = response.data;

    // Poblar inputs del formulario
    const inputs = formularioSeguro.getElementsByTagName('input');

    for (let i = 0; i < inputs.length; i++) {
        const input = inputs[i];

        if (input.name in seguro) {
            input.value = seguro[input.name];
        }
    }

    // Cambiar action del formulario
    formularioSeguro.action = formularioSeguro.dataset.rutaupdate;

    formularioSeguro.scrollIntoView();
}

// Despoblar formulario de editar seguro
async function despoblarSeguroEditar() {
    const inputsExemptos = [
        '_token',
        '_method'
    ];

    // despoblar inputs del formulario
    const inputs = formularioSeguro.getElementsByTagName('input');

    for (let i = 0; i < inputs.length; i++) {
        const input = inputs[i];

        if (!inputsExemptos.includes(input.name)) {
            input.value = '';
        }
    }

    // Cambiar action del formulario
    formularioSeguro.action = formularioSeguro.dataset.rutacreate;
}

function eliminarSeguro(id) {
    document.getElementById('campo-id-formulario-seguro').value = id;
    formularioSeguro.action = formularioSeguro.dataset.rutadelete;
    formularioSeguro.submit()
}

function promptEliminarSeguro(id) {
    Swal.fire({
        title: trans("Remove insurance?"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f36767',
        // cancelButtonColor: '#d33',
        confirmButtonText: trans("Confirm"),
        cancelButtonText: trans("Cancel")
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarSeguro(id);
        }
    })
}

// Event listeners de botones
for (let i = 0; i < botonesEditar.length; i++) {
    const boton = botonesEditar[i];

    boton.addEventListener('click', (e) => {
        poblarSeguroEditar(boton.dataset.seguroid);
    });
}

for (let i = 0; i < botonesEliminar.length; i++) {
    const boton = botonesEliminar[i];

    boton.addEventListener('click', (e) => {
        promptEliminarSeguro(boton.dataset.seguroid);
    });
}

botonLimpiarFormulario.addEventListener('click', (e) => {
    e.preventDefault();

    despoblarSeguroEditar();
})