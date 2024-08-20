const translate = {
        es: {
            NewEvent: "Nuevo evento",
            DeleteEvent: "¿Eliminar evento?",
            Confirm: "Confirmar",
            Cancel: "Cancelar",
            Today: "Hoy",
        },
        en: {
            NewEvent: "New event",
            DeleteEvent: "Delete event?",
            Confirm: "Confirm",
            Cancel: "Cancel",
            Today: "Today",
        }
    }
    /**
     * Recoger eventos
     */
const formPeticionEventos = document.getElementById('form-peticion-eventos');

const getEventos = async() => {
    try {
        const response = await axios.get(
            formPeticionEventos.action
        );
        return response.data;
    } catch (error) {
        // return false;
    }
}

/**
 * Modales - Info Evento
 */

// Modal de info evento
const formPeticionEvento = document.getElementById('form-peticion-evento');
const modalEvento = document.getElementById('caja-modal-eventoInfo');

async function abrirModalEvento(infoEvento) {
    // Recoger info del evento
    const response = await axios.get(formPeticionEvento.action.replace('replaceable', infoEvento.event.id))

    let evento = response.data;

    var options = { weekday: 'long', month: 'long', day: 'numeric' };
    var fecha_ini = new Date(Date.parse(evento.fecha_ini));
    var fecha_fin = new Date(Date.parse(evento.fecha_fin));

    // Poblar modal con la info del evento
    document.getElementById('titulo-modal-evento').innerText = evento.nombre;
    document.getElementById('fecha-inicio-modal-evento').innerText = fecha_ini.toLocaleDateString(USER_LANG, options);
    document.getElementById('fecha-final-modal-evento').innerText = fecha_fin.toLocaleDateString(USER_LANG, options);
    document.getElementById('descripcion-modal-evento').innerText = evento.descripcion;

    // Cambiar el id en el formulario de eliminar
    const formEliminarEvento = document.getElementById('form-peticion-evento-destroy');
    formEliminarEvento.dataset.eventoId = evento.id;
    const formEditarEvento = document.getElementById('form-peticion-evento-editar');
    formEditarEvento.dataset.eventoId = evento.id;

    const divIncidencias = document.getElementById('incidencias-modal-evento');
    if (evento.incidencias.length > 0) {
        divIncidencias.innerHTML = '';

        for (let i = 0; i < evento.incidencias.length; i++) {
            const incidencia = evento.incidencias[i];

            let a = document.createElement('a');
            a.classList.add('chip-incidencia');
            a.addEventListener('click', (e) => {
                abrirModalIncidencia(incidencia.id);
            });
            a.style.cursor = 'pointer';

            let wrapper = document.createElement('div');
            wrapper.classList.add('chip-incidencia-wrapper');

            let punto = document.createElement('div');
            punto.classList.add('chip-incidencia-punto');
            punto.classList.add('chip-incidencia-estado' + incidencia.estado)

            let textoIncidencia = document.createElement('p');
            textoIncidencia.classList.add('chip-incidencia-texto');
            textoIncidencia.innerText = incidencia.titulo;

            wrapper.appendChild(punto);
            wrapper.appendChild(textoIncidencia);
            a.appendChild(wrapper);

            divIncidencias.appendChild(a);
        }
    } else {
        document.getElementById('titulo-incidencias-modal-evento').style.display = 'none';
        divIncidencias.style.display = 'none';
    }

    modalEvento.classList.add('caja-modal-eventoInfo-abierta');
}

// Cerrar modal evento
const botonCerrarModalEvento = document.getElementById('cerrar-modal-evento');

botonCerrarModalEvento.addEventListener('click', (e) => {
    cerrarModalEvento();
})

function cerrarModalEvento() {
    modalEvento.classList.remove('caja-modal-eventoInfo-abierta');
}

/**
 * Modales - Info Incidencia
 */

// Abrir modal incidencia
const modalIncidencia = document.getElementById('div-modal-incidencia');
const tituloModalIncidencia = document.getElementById('modal-incidencia-titulo');
const descripcionModalIncidencia = document.getElementById('modal-incidencia-descripcion');
const imgModalIncidencia = document.getElementById('modal-incidencia-img');
const estadoColorModalIncidencia = document.getElementById('modal-incidencia-estado-color');
const estadoTextoModalIncidencia = document.getElementById('modal-incidencia-estado-texto');

const tablaEstados = {
    1: trans('To be approved'),
    2: trans('Open'),
    3: trans('In progress'),
    4: trans('Solved'),
};

async function abrirModalIncidencia(id) {
    let response = await axios.get(modalIncidencia.dataset.rutafind.replace('replaceable', id));

    let incidencia = response.data;

    if (incidencia) {
        tituloModalIncidencia.innerText = incidencia.titulo;
        descripcionModalIncidencia.innerText = incidencia.descripcion;
        imgModalIncidencia.src = imgModalIncidencia.dataset.ruta + '/' + id + '.png';
        estadoTextoModalIncidencia.innerText = tablaEstados[incidencia.estado];

        // Resetear clases de indicador de color de estado de incidencia
        const nombreClaseColorEstado = 'chip-incidencia-estado' + incidencia.estado;
        estadoColorModalIncidencia.className = "chip-incidencia-punto";
        estadoColorModalIncidencia.classList.add(nombreClaseColorEstado);

        // Enseñar modal incidencia
        modalIncidencia.classList.add('activo');
        modalIncidencia.style.display = 'block';
    }
}

// Cerrar modal incidencia
const botonCerrarIncidencia = document.getElementById('cerrar-modal-incidencia');

botonCerrarIncidencia.addEventListener('click', (e) => {
    modalIncidencia.classList.remove('activo');
    modalIncidencia.style.display = 'none';
})

/**
 * Modales - Nuevo Evento
 */

// Modal nuevo evento
const formPeticionEventoNuevo = document.getElementById('form-peticion-evento-crear');
const modalEventoNuevo = document.getElementById('caja-modal-eventoNew');

async function abrirModalEventoNuevo() {
    modalEventoNuevo.classList.add('caja-modal-eventoInfo-abierta');
}

// Cerrar modal evento nuevo
const botonCerrarModalEventoNuevo = document.getElementById('cerrar-modal-evento-new');

botonCerrarModalEventoNuevo.addEventListener('click', (e) => {
    cerrarModalEventoNuevo();
})

function cerrarModalEventoNuevo() {
    modalEventoNuevo.classList.remove('caja-modal-eventoInfo-abierta');
}


/**
 * Modales - Editar Evento
 */

// Modal editar evento
const formPeticionEventoEditar = document.getElementById('form-peticion-evento-editar');
const modalEventoEditar = document.getElementById('caja-modal-eventoEdit');

async function abrirModalEventoEditar() {

    // Recoger info del evento
    const response = await axios.get(formPeticionEvento.action.replace('replaceable', formPeticionEventoEditar.dataset.eventoId))

    let evento = response.data;

    // Poblar modal con la info del evento
    document.getElementById('titulo-modal-evento-edit').value = evento.nombre;
    document.getElementById('fecha-inicio-modal-evento-edit').value = evento.fecha_ini;
    document.getElementById('fecha-final-modal-evento-edit').value = evento.fecha_fin;
    document.getElementById('descripcion-modal-evento-edit').innerText = evento.descripcion;
    document.getElementById('descripcion-modal-evento-edit').style.height = document.getElementById('descripcion-modal-evento-edit').scrollHeight + 5 + 'px';

    const divIncidencias = document.getElementById('incidencias-modal-evento-edit');
    for (let i = 0; i < evento.incidencias.length; i++) {
        const incidencia = evento.incidencias[i];

        // Añadir elemento a la lista
        let button = document.createElement('button');
        button.dataset.incidenciaId = incidencia.id; // Le metemos un Id en el dataset para poder identificarlo y eliminarlo luego
        button.classList.add('chip-incidencia');

        let wrapper = document.createElement('div');
        wrapper.classList.add('chip-incidencia-wrapper');

        let punto = document.createElement('div');
        punto.classList.add('chip-incidencia-punto');
        punto.classList.add('chip-incidencia-estado' + incidencia.estado)

        let textoIncidencia = document.createElement('p');
        textoIncidencia.classList.add('chip-incidencia-texto');
        textoIncidencia.innerText = incidencia.titulo;

        wrapper.appendChild(punto);
        wrapper.appendChild(textoIncidencia);
        button.appendChild(wrapper);

        // Añadir input a la lista
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'incidencias[]';
        input.value = incidencia.id;

        divIncidencias.insertBefore(button, document.getElementById('incidencias-modal-evento-edit-incidencias-boton'));
        divIncidencias.insertBefore(input, document.getElementById('incidencias-modal-evento-edit-incidencias-boton'));

        // Eliminar elemento de la lista
        button.addEventListener('click', (e) => {
            e.preventDefault();

            for (let j = 0; j < divIncidencias.children.length; j++) {
                // Si el elemento en el que estamos tiene un dataset y su id es el mismo que la incidencia, eliminarlo
                if (divIncidencias.children[j].dataset.incidenciaId == incidencia.id) {
                    divIncidencias.children[j].remove();
                }

                // Si el elemento tiene un 'value' y, por tanto, es un input, eliminarlo
                if (divIncidencias.children[j].value == incidencia.id) {
                    divIncidencias.children[j].remove();
                }
            }
        })
    }

    modalEventoEditar.classList.add('caja-modal-eventoInfo-abierta');
}

// Cerrar modal editar evento
const botonCerrarModalEventoEditar = document.getElementById('cerrar-modal-evento-edit');

botonCerrarModalEventoEditar.addEventListener('click', (e) => {
    cerrarModalEventoEditar();
})

function cerrarModalEventoEditar() {
    modalEventoEditar.classList.remove('caja-modal-eventoInfo-abierta');
}

/**
 * Modales - Selector incidencias
 */
// Modal selector incidencias. returnElement => el elemento en el cual se pondrán los chips de incidencia, beforeElement => el elemento delante del cual se pondran los chips
const formPeticionSelectorIncidencias = document.getElementById('form-peticion-selector-incidencias');
const modalSelectorIncidencias = document.getElementById('caja-modal-selector-incidencias');

async function abrirModalSelectorIncidencias(returnElement = null, beforeElement = null) {
    modalSelectorIncidencias.classList.add('caja-modal-eventoInfo-abierta');

    if (!returnElement) {
        returnElement = document.getElementById('incidencias-modal-evento-new');
    }

    if (!beforeElement) {
        beforeElement = document.getElementById('incidencias-modal-evento-new-incidencias-boton');
    }

    // Recoger las incidencias de la comunidad
    const response = await axios.get(formPeticionSelectorIncidencias.action);

    let incidencias = response.data;

    const divListaIncidencias = document.getElementById('caja-modal-selector-incidencias-lista');

    divListaIncidencias.innerHTML = '';

    for (let i = 0; i < incidencias.length; i++) {
        // Crear elemento de incidencia
        const incidencia = incidencias[i];

        let wrapperIncidencia = document.createElement('div');
        wrapperIncidencia.classList.add('caja-modal-selector-incidencias-lista-incidencia');

        let punto = document.createElement('div');
        punto.classList.add('chip-incidencia-punto');
        punto.classList.add('chip-incidencia-estado' + incidencia.estado)

        let textoIncidencia = document.createElement('p');
        textoIncidencia.classList.add('caja-modal-selector-incidencias-lista-incidencia-titulo');
        textoIncidencia.innerText = incidencia.titulo;

        wrapperIncidencia.appendChild(punto);
        wrapperIncidencia.appendChild(textoIncidencia);
        divListaIncidencias.appendChild(wrapperIncidencia);

        // Añadir la incidencia a la lista al clicarla
        wrapperIncidencia.addEventListener('click', (e) => {

            // Añadir elemento a la lista
            let button = document.createElement('button');
            button.dataset.incidenciaId = incidencia.id; // Le metemos un Id en el dataset para poder identificarlo y eliminarlo luego
            button.classList.add('chip-incidencia');

            let wrapper = document.createElement('div');
            wrapper.classList.add('chip-incidencia-wrapper');

            let punto = document.createElement('div');
            punto.classList.add('chip-incidencia-punto');
            punto.classList.add('chip-incidencia-estado' + incidencia.estado)

            let textoIncidencia = document.createElement('p');
            textoIncidencia.classList.add('chip-incidencia-texto');
            textoIncidencia.innerText = incidencia.titulo;

            wrapper.appendChild(punto);
            wrapper.appendChild(textoIncidencia);
            button.appendChild(wrapper);

            // Añadir input a la lista
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'incidencias[]';
            input.value = incidencia.id;

            returnElement.insertBefore(button, beforeElement);
            returnElement.insertBefore(input, beforeElement);
            // returnElement.appendChild(button);
            // returnElement.appendChild(input);

            // Eliminar elemento de la lista
            button.addEventListener('click', (e) => {
                e.preventDefault();

                for (let j = 0; j < returnElement.children.length; j++) {
                    // Si el elemento en el que estamos tiene un dataset y su id es el mismo que la incidencia, eliminarlo
                    if (returnElement.children[j].dataset.incidenciaId == incidencia.id) {
                        returnElement.children[j].remove();
                    }

                    // Si el elemento tiene un 'value' y, por tanto, es un input, eliminarlo
                    if (returnElement.children[j].value == incidencia.id) {
                        returnElement.children[j].remove();
                    }
                }
            })


            cerrarModalSelectorIncidencias();
        });
    }
}

// Cerrar modal evento nuevo
const botonCerrarModalSelectorIncidencias = document.getElementById('cerrar-modal-selector-incidencias');

botonCerrarModalSelectorIncidencias.addEventListener('click', (e) => {
    cerrarModalSelectorIncidencias();
})

function cerrarModalSelectorIncidencias() {
    modalSelectorIncidencias.classList.remove('caja-modal-eventoInfo-abierta');
}

// Abrir modal selector incidencias al clicar en el botón de 'asociar incidencia' en modal de nuevo evento
document.getElementById('incidencias-modal-evento-new-incidencias-boton').addEventListener('click', (e) => {
    e.preventDefault();

    abrirModalSelectorIncidencias();
})

// Abrir modal selector incidencias al clicar en el botón de 'asociar incidencia' en modal de editar evento
document.getElementById('incidencias-modal-evento-edit-incidencias-boton').addEventListener('click', (e) => {
    e.preventDefault();

    abrirModalSelectorIncidencias(document.getElementById('incidencias-modal-evento-edit'), document.getElementById('incidencias-modal-evento-edit-incidencias-boton'));
})

/**
 * Interacciones de admin con eventos
 */

// Interacción Eliminar Evento
const botonEliminarEvento = document.getElementById('modal-evento-eliminar');
const formEliminarEvento = document.getElementById('form-peticion-evento-destroy');

botonEliminarEvento.addEventListener('click', (e) => {
    Swal.fire({
        title: trans("Remove event?"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f36767',
        // cancelButtonColor: '#d33',
        confirmButtonText: trans("Confirm"),
        cancelButtonText: trans("Cancel")
    }).then((result) => {
        if (result.isConfirmed) {
            formEliminarEvento.action = formEliminarEvento.action.replace('replaceable', formEliminarEvento.dataset.eventoId);
            formEliminarEvento.submit();
        }
    })
})

// Interacción Editar Evento
const botonEditarEvento = document.getElementById('modal-evento-editar');

botonEditarEvento.addEventListener('click', (e) => {
    cerrarModalEvento();
    formPeticionEventoEditar.action = formPeticionEventoEditar.action.replace('replaceable', formPeticionEventoEditar.dataset.eventoId);
    abrirModalEventoEditar();
});

// Interaccion Crear Evento (botón responsive)
const botonCrearEventoResponsive = document.getElementById('boton-evento-nuevo-responsive');

botonCrearEventoResponsive.addEventListener('click', (e) => {
    abrirModalEventoNuevo();
})

/**
 * Calendario
 */
// Inicializar calendario
document.addEventListener('DOMContentLoaded', async function() {
    // Crear array de eventos
    var eventosRaw = await getEventos()
    let eventos = [];

    for (let i = 0; i < eventosRaw.length; i++) {
        let evento = {
            extendedProps: {}
        };
        evento.id = eventosRaw[i].id;
        evento.title = eventosRaw[i].nombre;
        evento.start = eventosRaw[i].fecha_ini;
        evento.end = eventosRaw[i].fecha_fin;
        evento.description = eventosRaw[i].descripcion;
        eventos.push(evento);
    }
    // Renderizar calendario en español con los eventos
    var calendarEl = document.getElementById('calendario');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: USER_LANG,
        events: eventos,
        firstDay: 1,
        eventClick: (event) => { abrirModalEvento(event) },
        headerToolbar: {
            start: 'prev,newEvent',
            center: 'title',
            end: 'today,next'
        },
        customButtons: {
            newEvent: {
                text: trans('New Event'),
                click: (e) => {
                    abrirModalEventoNuevo();
                }
            },
            today: {
                text: trans('Today'),
                click: (e) => {
                    calendar.today();
                }
            }

        }
    });
    calendar.render();
});
