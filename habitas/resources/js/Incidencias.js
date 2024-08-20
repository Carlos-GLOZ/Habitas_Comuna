var test = '';
var imgChange;


function mover(incidencia) {
    const columns = document.querySelectorAll(".columna");

    // Definimos el elemento que arrastramos
    test = incidencia.target;
    columns.forEach((columna) => {
        columna.addEventListener("dragover", dragoverI);
        columna.addEventListener("drop", dropInci);
    });
    test.addEventListener('dragend', endDrag);

    // El elemento que arrastre inhabilitará los elementos de la columna
    document.querySelectorAll('.columna').forEach(columna => {
        for (let i = 0; i < columna.children.length; i++) {
            if (columna.children[i] == test) {
                columna.children[i].classList.add('pointer-events-auto');
            } else {
                columna.children[i].classList.add('pointer-events-none');
            }
        }
    });
}

// Habilitas las incidencias
const endDrag = () => {
    document.querySelectorAll('.columna').forEach(columna => {
        for (let i = 0; i < columna.children.length; i++) {
            if (columna.children[i] == test) {
                columna.children[i].classList.remove('pointer-events-auto');
            } else {
                columna.children[i].classList.remove('pointer-events-none');
            }
        }
    });

    document.querySelectorAll(".columna").forEach((columna) => {
        columna.removeEventListener("dragover", dragoverI);
        columna.removeEventListener("drop", dropInci);
    });
    test.removeEventListener('dragend', endDrag);
}

// Funcion que permite mover el elemento por la página
function dragoverI(e) {
    e.preventDefault();
}

// Añade la incidencia a la columna sobre la que esta
async function dropInci(e) {
    if (e.target.getAttribute('draggable')) {
        e.target.parentElement.appendChild(test);
        e.target.parentElement.addEventListener("dragover", dragoverI);

    } else {
        //Aunque selecciones algo de dentro del div actua en todo el div
        e.toElement.appendChild(test);
        e.toElement.addEventListener("dragover", dragoverI);
        if (e.target.id != 1) {
            document.getElementById('editar_' + test.id).style.display = 'none';
        } else {
            document.getElementById('editar_' + test.id).style.display = 'block';
        }

        let formdata = new FormData()
        formdata.append('id_incidencia', test.id)
        formdata.append('id_estado', e.target.id)
        await axios.post(URL_API + '/incidencias/update_incidencia', formdata)

    }

}



//*******************MODALES***************************

const abrirMod = () => {

    modalIncidencia.style.display = 'flex';
    modalIncidencia.style.justifyContent = 'center';
    modalIncidencia.style.alignItems = 'center';
    document.querySelector('body').style.overflow = 'hidden';

    document.querySelector('.x-cerrar').addEventListener('click', cerrarMod);

    Cimagen.onchange = textMod;
}

// Cambia el texto del label por el nombre del fichero
const textMod = (e) => {
    e.target.previousElementSibling.innerText = e.target.files[0].name;
}


const cerrarMod = () => {
    document.removeEventListener('click', cerrarMod);
    modalIncidencia.style.display = 'none';
    document.querySelector('body').style.overflow = 'auto';
}

//*******************AXIOS***************************//

var titulo_inc = document.getElementById("titulo");
var descripcion_inc = document.getElementById("descripcion");

async function abrir_editar(element) {

    let id = element.target.dataset.incid;

    Cimagen_ed.onchange = textMod;

    let card_sel = element.target.parentElement.parentElement.parentElement;
    if (card_sel.classList.contains('activo')) {
        card_sel.querySelector('.boton_ver').click();
    };

    let resul = await axios.get(URL_API + '/incidencias/datos_editar/' + id);

    modalEditarIncidencia.style.display = 'flex';
    modalEditarIncidencia.style.justifyContent = 'center';
    modalEditarIncidencia.style.alignItems = 'center';
    // document.querySelector('body').style.overflow = 'hidden';

    document.querySelector('.x-cerrar3').addEventListener('click', cerrarModEditar);

    document.getElementById("titulo_ed").value = resul.data.titulo;
    document.getElementsByName("id_editar")[0].innerText = resul.data.id;
    document.getElementsByName("descripcion_ed")[0].innerText = resul.data.descripcion;

}

// Cierra el modal de editar
const cerrarModEditar = () => {
    document.removeEventListener('click', cerrarModVer);
    modalEditarIncidencia.style.display = 'none';
    modalEditarIncidencia.style.display = 'none';

    document.querySelector('body').style.overflow = 'auto';
}

// Crea una incidencia
const crearIncidencia = async() => {
    document.getElementById('crearIncidencias_btn').disabled = true;

    let formdata = new FormData()
    formdata.append('titulo', titulo_inc.value)
    formdata.append('descripcion', descripcion_inc.value)
    formdata.append('imagen', document.querySelector("#Cimagen").files[0]);

    let resul = await axios.post(URL_API + '/incidencias/crear_incidencia', formdata)

    if (resul.data == 'OK') {
        document.getElementById('form').reset();
        document.querySelector('.x-cerrar').click();


        // Alerta
        Swal.fire({
            icon: 'success',
            title: trans("Incident created successfully"),
            showConfirmButton: true,
        }).then(function() {
            cIncidencia();
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: trans("Too many characters or invalid file"),
            showConfirmButton: false,
            timer: 1500
        })
    }
    document.getElementById('crearIncidencias_btn').disabled = false;


}

const editarIncidencia = async() => {
    document.getElementById('actualizarIncidencias_btn').disabled = true;

    let formdata = new FormData()

    formdata.append('titulo', titulo_ed.value)
    formdata.append('id', id_editar.value)
    formdata.append('descripcion', descripcion_ed.value)
    formdata.append('imagen', document.querySelector("#Cimagen_ed").files[0]);

    let resul = await axios.post(URL_API + '/incidencias/editar_incidencia', formdata)

    document.getElementById('actualizarIncidencias_btn').disabled = false;

    if (resul.data[0] == 'OK') {
        document.getElementById('form2').reset();
        document.querySelector('.x-cerrar3').click();

        if ([...formdata][2][1].name != ''){
            imgChange = resul.data[1];
        } else {
            imgChange = '';
        }

        Swal.fire({
            icon: 'success',
            title: trans("Incident updated successfully"),
            showConfirmButton: true,
        }).then(function() {
            cIncidencia();
        });

    } else {
        Swal.fire({
            icon: 'error',
            title: trans("Too many characters or invalid file"),
            showConfirmButton: false,
            timer: 1500,
        })
    }
}



async function abrir(id) {

    let resul = await axios.get(URL_API + '/incidencias/ver_incidencia/' + id);

    modalVerIncidencia.style.display = 'flex';
    modalVerIncidencia.style.justifyContent = 'center';
    modalVerIncidencia.style.alignItems = 'center';
    // document.querySelector('body').style.overflow = 'hidden';

    document.querySelector('.x-cerrar2').addEventListener('click', cerrarModVer);

    document.getElementsByClassName("titulo_inci")[0].innerText = resul.data.titulo;
    document.getElementsByClassName("descripcion_inici")[0].innerText = resul.data.descripcion;
    document.getElementsByClassName("imgn")[0].src = URL_API + "/storage/uploads/incidencia/" + resul.data.id + ".png";

}

const cerrarModVer = () => {
    document.removeEventListener('click', cerrarModVer);
    modalVerIncidencia.style.display = 'none';
    modalEditarIncidencia.style.display = 'none';


    document.querySelector('body').style.overflow = 'auto';
}


const elim = async(e) => {

    e.target.disabled = true;

    let id = e.target.dataset.incid;

    await axios.delete(URL_API + '/incidencias/EliminarIncidencia/' + id)

    // Elimina la incidencia
    let cardDel = e.target.parentElement.parentElement.parentElement;
    cardDel.remove();
    e.target.disabled = false;



    Swal.fire({
        icon: 'success',
        title: trans("Incident removed successfully"),
        showConfirmButton: true
    });

}

const cIncidencia = async() => {

    let resul = await axios.get(URL_API + '/incidencias/mincidencias');
    let timestamp = new Date().getTime();

    document.getElementsByClassName('creacion')[0].innerHTML = '';
    resul.data.forEach(incidencia => {
        document.getElementsByClassName('creacion')[0].innerHTML += `
        <div id="${incidencia.id}" class="m-2.5" draggable="true">
            
            <div href="#" class="tarjeta-incidencia test">

                <div class="flexnoes justify-around">
                    <div class="textos_inc w-1/2 flex flex-col">
                        <h5 class="texto1 mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white" >${incidencia.titulo}</h5>
                        <p class="descripcion_listado font-normal text-gray-700 dark:text-gray-400">${incidencia.descripcion}</p>
                
                        <div class="estados my-2 grow content-end justify-center sm:justify-start" display="none">

                            <select class="select_estados" name="id_estados" id="update_estados" style="display: flex; flex-wrap: wrap; align-content: center;" >
                                <option value="1" content="NO">${trans('To be approved')}</option>
                                <option value="2">${trans('Open')}</option>
                                <option value="3">${trans('In progress')}</option>
                                <option value="4">${trans('Solved')}</option>
                            </select>
                        </div>
                    </div>

                    <div class="imgn w-0" style="max-height: 450px">
                        
                    </div>
                </div>

                <div class="botones">

                    <button type="button" class="bg-blue-500 hover:bg-red-500 text-white font-bold py-2 px-4 rounded-full boton_elim" data-incId="${incidencia.id}"><i class="fa-solid fa-trash pointer-events-none"></i></button>

                    <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white hover:text-black font-bold py-2 px-4 rounded-full boton_ver" data-incId="${incidencia.id}"><i class="fa-solid fa-eye pointer-events-none"></i></button>

                    <button type="button" class="bg-blue-500 hover:bg-yellow-300 text-white font-bold py-2 px-4 rounded-full boton_editar" data-incId="${incidencia.id}" id="editar_${incidencia.id}"><i class="fa-solid fa-pen-to-square pointer-events-none" style="color: #ffffff;"></i></button>    
                    
                </div>

            </div>
        </div>
        `;

        let nImg = document.createElement('img');
        nImg.className = 'w-full h-full imgI';

        nImg.addEventListener('error', errorImg);

        // console.log(imgChange, incidencia.id);
        if (imgChange == incidencia.id){
            nImg.src = URL_API+'/storage/uploads/incidencia/'+(incidencia.id)+'.png?t='+timestamp;
        } else {
            nImg.src = URL_API+'/storage/uploads/incidencia/'+(incidencia.id)+'.png';
        }

        document.querySelectorAll('.creacion')[0].lastElementChild.querySelector('.imgn').appendChild(nImg);
        // <img class="w-full object-cover imgI" src="{{asset('storage/uploads/incidencia/'.$incidencia->id.'.png')}}"></img>
    });

    document.querySelectorAll('.boton_ver').forEach(btn => {
        btn.addEventListener('click', mostrarA);
    });


    document.querySelectorAll('.boton_elim').forEach(boton => {
        boton.addEventListener('click',elim);
    });

    document.querySelectorAll('.boton_editar').forEach(boton => {
        boton.addEventListener('click', abrir_editar);
    });

}



/**
 * Asignar eventos a elementos de abrir/eliminar incidencia
 */


const botonesElim = document.getElementsByClassName('boton_elim');

for (let i = 0; i < botonesElim.length; i++) {
    const boton = botonesElim[i];

    boton.addEventListener('click', elim)
}

const botonesEditar = document.getElementsByClassName('boton_editar');

for (let i = 0; i < botonesEditar.length; i++) {
    const boton = botonesEditar[i];

    boton.addEventListener('click', (e) => {
        abrir_editar(e)
    })
}

const mostrarA = (e) => {
    let div_a = e.target.parentElement.parentElement.parentElement;

    if (div_a.clientWidth > window.innerWidth * 0.5) {
        div_a.style = `--ancho_div: 80%`;
    } else {
        div_a.style = `--ancho_div: 50%`;
    }
    div_a.classList.toggle('activo');

    div_a.classList.toggle('m-2.5');
    div_a.querySelector('.texto1').classList.toggle('text-xl');
    div_a.querySelector('.texto1').classList.toggle('text-3xl');
    div_a.querySelector('.texto1').classList.toggle('mb-4');

    div_a.querySelector('.boton_ver').children[0].classList.toggle('fa-eye');
    div_a.querySelector('.boton_ver').children[0].classList.toggle('fa-eye-slash');

    if (div_a.querySelector('.imgn')){
        div_a.querySelector('.imgn').classList.toggle('w-0');
        div_a.querySelector('.imgn').classList.toggle('w-1/2');
    }

}


const Estado_Inc = async(event) => {

    let obj = event.target.parentElement.parentElement.parentElement.parentElement.parentElement;
    let selectedOption = event.target.value;

    let col_estado = document.querySelectorAll('.columna')[selectedOption-1].querySelector('.creacion');
    col_estado.insertBefore(obj, col_estado.firstChild);

    let formdata = new FormData()
    formdata.append('id', obj.id)
    formdata.append('estado_select', selectedOption)

    await axios.post(URL_API + '/incidencias/editar_estado', formdata);
}

const errorImg = (e) => {
    e.target.parentElement.remove();
}

window.addEventListener('load', () => {
    document.querySelector('h1').addEventListener('click', abrirMod);

    crearIncidencias_btn.addEventListener('click', crearIncidencia)


    actualizarIncidencias_btn.addEventListener('click', editarIncidencia)
        //Elementos
    const incidencia = document.querySelectorAll(".incidencia")

    for (let i = 0; i < incidencia.length; i++) {
        incidencia[i].addEventListener("drag", mover)
    }

    document.querySelectorAll('.boton_ver').forEach(btn => {
        btn.addEventListener('click', mostrarA);
    });


    document.querySelectorAll('.select_estados').forEach(selectElement => {
        selectElement.addEventListener('change', Estado_Inc)
    });

    // Verifica si la imagen existe
    document.querySelectorAll('.imgI').forEach(img => {
        img.addEventListener('error',errorImg);
        img.src = img.src;
    })

});