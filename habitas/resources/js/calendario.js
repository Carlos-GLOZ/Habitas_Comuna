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
    // Recoger eventos
const formPeticionEventos = document.getElementById('form-peticion-eventos');

const getEventos = async() => {
    try {
        const response = await axios.get(
            formPeticionEventos.action,
        );
        return response.data;
    } catch (error) {
        // return false;
    }
}

// Modal de evento
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

// Inicializar calendario
document.addEventListener('DOMContentLoaded', async function() {
    // Crear array de eventos
    var eventosRaw = await getEventos();
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
            start: 'prev',
            center: 'title',
            end: 'today,next'
        },
        customButtons: {
            newEvent: {
                text: translate[USER_LANG]["NewEvent"],
                click: (e) => {
                    alert('Nuevo evento!');
                }
            },
            today: {
                text: translate[USER_LANG]["Today"],
                click: (e) => {
                    calendar.today();
                }
            }
        }
    });
    calendar.render();
});
