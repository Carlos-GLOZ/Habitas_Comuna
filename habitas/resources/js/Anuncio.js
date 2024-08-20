var imgChange;


// Muestra el modal
const abrirMod = () => {
    modalAnuncio.style.display = 'flex';
    modalAnuncio.style.justifyContent = 'center';
    modalAnuncio.style.alignItems = 'center';
    document.querySelector('body').style.overflow = 'hidden';

    document.querySelector('.x-cerrar').addEventListener('click',cerrarMod);
    Cimagen.labels[0].innerText = 'Añadir imagen';
    Cimagen.onchange = textMod;

    // Asigna un evento cuando se hace un submit
    form.addEventListener('submit', anuncio);
}

const abrirModE = (e) => {
    let card_sel = e.target.parentElement.parentElement.parentElement;

    if (card_sel.classList.contains('activo')){card_sel.querySelector('.boton_ver').click();};

    let id = e.target.dataset.anunid;

    modalAnuncio.style.display = 'flex';
    modalAnuncio.style.justifyContent = 'center';
    modalAnuncio.style.alignItems = 'center';
    document.querySelector('body').style.overflow = 'hidden';

    document.querySelector('.x-cerrar').addEventListener('click',cerrarMod);
    Cimagen.labels[0].innerText = 'Cambiar imagen';
    Cimagen.onchange = textMod;

    let titulo = e.target.parentElement.previousElementSibling.children[0].innerText;
    let descripcion = e.target.parentElement.previousElementSibling.children[1].children[0].innerText;

    document.querySelector('[name="Titulo"]').value = titulo;
    document.querySelector('[name="Informacion"]').value = descripcion;

    let c_input = document.createElement('span');
    c_input.innerHTML = `<input type="hidden" name="anuncio" value="${id}">`;
    form.appendChild(c_input);

    // Asigna un evento cuando se hace un submit
    form.addEventListener('submit', anuncioEdit);
}

// Lista todos los anuncios y actualiza los anuncios
const anuncioEdit = async(e) => {
    e.preventDefault();
    form.removeEventListener('submit', anuncioEdit);
    document.querySelector('.btn-modal-anuncio').disabled = true;


    document.querySelectorAll('.boton_edit').forEach(edit => {
        edit.removeEventListener('click', abrirModE);
    })

    const formdata = new FormData(form);
    
    let resul = await axios.post(URL_API+'/anuncios/editAnuncio',formdata)

    // console.log(resul.data);
    if (resul.data[0] == 'OK'){
        let btns_ver = document.querySelectorAll('.boton_ver');
        btns_ver.forEach(btn=>{
            btn.removeEventListener('click',mostrarA);
        });
        
        if ([...formdata][2][1].name != ''){
            imgChange = resul.data[1];
        } else {
            imgChange = '';
        }
        cAnuncios();

        Swal.fire({
            icon: 'success',
            title: 'Anuncio actualizado correctamente',
        });
        document.querySelector('.btn-modal-anuncio').disabled = false;
    }

    document.querySelector('.x-cerrar').click();

}

const textMod = (e) => {
    e.target.labels[0].innerText = e.target.files[0].name;
}

// Quital el modal
const cerrarMod = () => {
    document.querySelector('.x-cerrar').removeEventListener('click',cerrarMod);
    modalAnuncio.style.display = 'none';
    document.querySelector('body').style.overflow = 'auto';
    form.reset();
    form.removeEventListener('submit', anuncioEdit);
    form.removeEventListener('submit', anuncio);
}

const errorImg = (e) => {
    e.target.parentElement.remove();
}

// Muestra todos los anuncios
const cAnuncios = async() => {
    let info = await axios.get(URL_API+'/anuncios/manuncios');

    if (info.statusText === 'OK'){
        document.querySelector('.contenedor-anuncios').innerHTML = '';
        // console.log(info);

        let timestamp = new Date().getTime();

        // console.log(imgChange)

        info.data.forEach(anuncio => {
            document.querySelector('.contenedor-anuncios').innerHTML += `
            <div style="height: 33vh" class="div_anuncio">
                <div class="h-full">
                    <div class="px-6 py-4 h-4/6 overflow-hidden w-full">

                        <h2 class="text-xl text-gray-700 mb-2 truncate">${anuncio.nombre}</h2>
                            
                        <div class="flex">
                            <p class="text-gray-700 text-base font-thin text-justify line-clamp-5 grow">${anuncio.descripcion}</p>
                            <div class="w-0 h-full img_con" style="max-height: 52vh;">

                            </div>
                        </div>
                    </div>


                    <div class="botones">

                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_elim" data-anunId="${anuncio.id}"><i class="fa-solid fa-trash"></i></button>

                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_edit" data-anunId="${anuncio.id}"><i class="fa-solid fa-edit pointer-events-none"></i></button>

                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_ver"><i class="fa-solid fa-eye pointer-events-none"></i></button>    

                    </div>
                </div>
            </div>
            `;

            let nImg = document.createElement('img');
            nImg.className = 'imgn w-full h-full imgA';

            nImg.addEventListener('error', errorImg);

            if (imgChange == anuncio.id){
                nImg.src = URL_API+'/storage/uploads/anuncio/'+(anuncio.id)+'.png?t='+timestamp;
            } else {
                nImg.src = URL_API+'/storage/uploads/anuncio/'+(anuncio.id)+'.png';
            }

            document.querySelector('.contenedor-anuncios').lastElementChild.querySelector('.img_con').appendChild(nImg);

        });

        document.querySelectorAll('.boton_ver').forEach(btn=>{
            btn.addEventListener('click',mostrarA);
        })

        document.querySelectorAll('.boton_edit').forEach(edit => {
            edit.addEventListener('click', abrirModE);
        })

        const botonesElim = document.getElementsByClassName('boton_elim');

        for (let i = 0; i < botonesElim.length; i++) {
            const boton = botonesElim[i];

            boton.addEventListener('click', () => {
                elim(boton.dataset.anunid, boton)
            })
        }
    }
}

const elim = async(id, button) => {
    button.disabled = true;

    await axios.delete(URL_API+'/anuncios/eliminar_anuncio/'+id);

    Swal.fire({
        icon: 'success',
        title: 'Anuncio eliminado correctamente',
    }).then(function(){
        button.disabled = false;
        let btns_ver = document.querySelectorAll('.boton_ver');
        btns_ver.forEach(btn=>{
            btn.removeEventListener('click',mostrarA);
        });
        cAnuncios();
    });

}

const botonesElim = document.getElementsByClassName('boton_elim');

for (let i = 0; i < botonesElim.length; i++) {
    const boton = botonesElim[i];

    boton.addEventListener('click', () => {
        elim(boton.dataset.anunid, boton)
    })
}

// Crear nuevos anuncios
const anuncio = async(e) => {
    e.preventDefault();

    form.addEventListener('submit', anuncio);
    document.querySelector('.btn-modal-anuncio').disabled = true;
    
    const formdata = new FormData(document.querySelector('#form'));

    // console.log(formdata)
    
    let text2 = '<div style="text-align:left;">';
    formdata.forEach((value, key) => {
        if (!value) {
            text2 += `- El campo "<strong>${key}</strong>" está vacío.<br>`;
        }
    });
    if (text2 != '<div style="text-align:left;">') {
        
        return Swal.fire({
            icon: 'error',
            title: 'No se ha creado el anuncio, debido:',
            html: text2+'</div>',
        });
    }
    
    const info = await axios.post(URL_API+'/anuncios/crearAnuncio', formdata);


    // console.log(info.data);
    if (info.data[0] === 'OK') {
        form.reset();
        document.querySelector('.x-cerrar').click();

        // Alerta correcto
        Swal.fire({
            icon: 'success',
            title: 'Anuncio creado correctamente',
        }).then(function(){
            document.querySelector('.btn-modal-anuncio').disabled = false;

            let newAnuncio = document.createElement('div');
            newAnuncio.className = 'div_anuncio h-[33vh]';
            newAnuncio.innerHTML = `
            <div class="h-full">
                <div class="px-6 py-4 h-4/6 overflow-hidden w-full">
                    <h2 class="text-xl text-gray-700 mb-2 truncate" title="test11">${info.data[1].nombre}</h2>

                    <div class="flex">
                        <p class="text-gray-700 text-base font-thin text-justify line-clamp-5 grow">${info.data[1].descripcion}</p>
                        
                        <div class="w-0 h-full img_con overflow-hidden" style="max-height: 52vh;">
                            
                        </div>
                    </div>
                </div>

                <div class="botones">
                    <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_elim" data-anunid="${info.data[1].id}"><i class="fa-solid fa-trash"></i></button>

                    <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_edit" data-anunid="${info.data[1].id}"><i class="fa-solid fa-edit pointer-events-none"></i></button>
                    
                    <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_ver"><i class="fa-solid fa-eye pointer-events-none"></i></button>    
                </div>
            </div>
            `;
            newAnuncio.querySelector('.boton_ver').addEventListener('click',mostrarA);
            newAnuncio.querySelector('.boton_edit').addEventListener('click',abrirModE);
            newAnuncio.querySelector('.boton_elim').addEventListener('click',(e)=>{
                elim(e.target.data.anunid, e.target);
            });

            document.querySelector('.contenedor-anuncios').insertBefore(newAnuncio,document.querySelector('.contenedor-anuncios').firstChild);

            if (info.data[2]){
                document.querySelector('.contenedor-anuncios').firstChild.querySelector('.img_con').innerHTML = `<img class="imgn w-full h-full imgA" src="${URL_API}/storage/uploads/anuncio/${info.data[1].id}.png">`;
            } else {
                document.querySelector('.contenedor-anuncios').firstChild.querySelector('.img_con').remove();
            }


        });

        
    } else if(typeof info.data == 'object'){
        Swal.fire({
            icon: 'error',
            title: 'Los datos del anuncio no cumple los requisitos',
            timer: 1500,
        })
    } else {
        // console.log(info.errors);
        Swal.fire({
            icon: 'error',
            title: 'No se ha podido crear el anuncio debido a un error.',
        })
    }
}

// Muestra el anuncio
const mostrarA = (e) => {
    // Recojemos el anuncio que vamos a visualizar
    let div_a = e.target.parentElement.parentElement.parentElement;

    if (div_a.clientWidth > window.innerWidth*0.5){
        div_a.style=`--ancho_div: 80%`;
    } else {
        div_a.style=`--ancho_div: 50%`;
    }
    div_a.classList.toggle('activo');

    // Contenedor card
    div_a.querySelector('.h-full').classList.toggle('min-h-[300px]');
    div_a.querySelector('.h-full').classList.toggle('flex');
    div_a.querySelector('.h-full').classList.toggle('flex-col');

    div_a.querySelector('.text-gray-700').parentElement.classList.toggle('grow');
    div_a.querySelector('.text-gray-700').classList.toggle('text-xl');
    div_a.querySelector('.text-gray-700').classList.toggle('text-3xl');
    div_a.querySelector('.text-gray-700').classList.toggle('mb-4');

    div_a.querySelector('.boton_ver').children[0].classList.toggle('fa-eye');
    div_a.querySelector('.boton_ver').children[0].classList.toggle('fa-eye-slash');

    if (div_a.querySelector('.img_con')){
        div_a.querySelector('.img_con').classList.toggle('w-0');
        div_a.querySelector('.img_con').classList.toggle('w-1/2');
    }
}

window.addEventListener('load',()=>{
    // Añade un evento al boton de crear un nuevo anuncio
    if (document.querySelector('.anuncioC')){
        document.querySelector('.anuncioC').addEventListener('click', abrirMod);
    }

    if (document.querySelectorAll('.boton_edit')){
        document.querySelectorAll('.boton_edit').forEach(edit => {
            edit.addEventListener('click', abrirModE);
        })
    }

    // Verifica si la imagen existe
    document.querySelectorAll('.imgA').forEach(img => {
        img.addEventListener('error',errorImg);
        img.src = img.src;
    })

    // Recorre todos los botones de visualizacion
    document.querySelectorAll('.boton_ver').forEach(btn=>{
        btn.addEventListener('click',mostrarA);
    })
});
