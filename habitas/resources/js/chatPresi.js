// var mesage = document.getElementById("mesage");


const mensaje=async(e)=>{
    e.preventDefault();
    if (document.querySelector('#form')){
        document.querySelector('#form').removeEventListener('submit', mensaje);
    }


    if (mesage.value != ''){
        let formdata = new FormData()
        formdata.append('mensaje',mesage.value)
        formdata.append('evento',evento)

        if (document.querySelector('[name="id_usuario"]')){
            formdata.append('vecino', document.querySelector('[name="id_usuario"]').value)
        }


        l_new_msg(mesage.value);
        mesage.value = '';

        await axios.post(URL_API+'/chatPresidente/EnviarMensaje', formdata)
    }

    if (document.querySelector('#form')){
        document.querySelector('#form').addEventListener('submit', mensaje);
    }
}


const listarVecinos = async(e) => {
    e.preventDefault();

    // Limpiamos todos los eventos asociados a los "botones" de los vecinos
    document.querySelectorAll('.vecino-item').forEach(vecino => {
        vecino.addEventListener('click', mostrarVecino);
    });

    const formdata = new FormData();
    formdata.append('name',document.querySelector('[name="busqueda"]').value);
    let data = await axios.post(URL_API+'/chatPresidente/buscar',formdata);

    // console.log(data.data);
    busqueda.innerHTML = ''; //Limpamos a los vecinos

    data.data.forEach(vecino =>{
        // console.log(vecino)
        let op1 = '';
        if (vecino.visible == 1){
            op1 = `<img class="w-12 h-12 block mx-auto rounded-full sm:mx-0 sm:shrink-0 pointer-events-none" src="${vecino.profile_photo_url}">`;
        } else {
            op1 = `<img class="w-12 h-12 block mx-auto rounded-full sm:mx-0 sm:shrink-0 pointer-events-none" src="https:\/\/ui-avatars.com\/api\/?name=VA&color=7F9CF5&background=EBF4FF" alt="Womans Face">`;
        }
        busqueda.innerHTML += `
        <li class="p-2 border-b hover:bg-gray-200 cursor-pointer transition-all vecino-item flex justify-start items-center"><input type="hidden" name="usuario" value="${vecino.id}" />${op1} <span class="pl-3 pointer-events-none">${vecino.name} ${vecino.apellidos}</span></li>`;
    })

    // Añade el evento a los nuevos vecinos para visualizar el chat
    document.querySelectorAll('.vecino-item').forEach(vecino => {
        vecino.addEventListener('click', mostrarVecino);
    });

    // document.querySelector('[name="busqueda"]').value = '';
}


var evento;
Pusher.logToConsole = false;
var channel = false;
var  pusher = new Pusher('413c67bec4e72c16bb0d', {
    cluster: 'eu'
});

const l_new_msg = (contenido) => {
    let msg = document.createElement('p');
    msg.className = 'msg_historial_enviado';
    msg.innerText = contenido;
    historyChat.appendChild(msg);
    historyChat.scrollTop = historyChat.scrollHeight;

}

const l_new_msg_recibido = (contenido) => {
    let msg = document.createElement('p');
    msg.className = 'msg_historial_recibido';
    msg.innerText = contenido;
    historyChat.appendChild(msg);
    historyChat.scrollTop = historyChat.scrollHeight;
}

const ini_chat=async(idU=0)=>{

    // Se crea el evento dependiendo del chat de usuario que seleccione el presi/vice
    evento = idU + '_' + id_comunidad.value;

    // console.log(evento,URL_API+'/chatPresidente/info');

    var resul = await axios.get(URL_API+'/chatPresidente/info');
    // console.log(resul.data);
    if(!channel){
        channel = pusher.subscribe(resul.data.meet);
    }else{
        try{
            channel.unsubscribeAll();
        }catch(e){

        }
    }

    // console.log('Canal: ',resul.data.meet,'  Evento: ',evento, '  Channel: ',channel);

    channel.bind(evento, function(data) {

        // console.log(data);
        // console.log(data.id_usuario == idU)
        // console.log(data.id_usuario, idU)
        if(data.id_usuario == idU){
            l_new_msg_recibido(data.msg);
            mesage.msg = '';
        }

    });
}

const mostrarVecino = async(e) => {
    let id = e.target.firstElementChild.value;

    chat_cli.classList.remove('bg-gray-400');
    chat_cli.classList.add('bg-gray-100');
    chat_cli.innerHTML = `
    <div id="historyChat" class="h-0 sm:h-4/5 px-4 grow bg-white text-black flex flex-col w-full overflow-auto" style=" background: #F3F4F6;">
    </div>

    <form id="form" class="flex w-full justify-center items-end sm:items-center" style="height:14%;">
        <input type="hidden" name="id_usuario" value="${id}">
        <div class="form-outline w-4/5 sm:h-20 flex items-center pr-2">
            <input id="mesage" class="form-control form-control-lg text-black h-10 resize-none overflow-hidden rounded-full shadow-md px-4 border-none w-full name="mesage" style="outline: none;caret-color: blue;" required/>
        </div>

        <button type="submit" id="Enviar_Mensaje_btn" class="inline-block bg-blue-400 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 hover:bg-blue-600 hover:text-white transition duration-300 ease-in-out shadow-md" style="height: 40px"><i class="fa-solid fa-paper-plane-top" style="color: #ffffff;"></i></button>
    </form>`;

    // <textarea id="mesage" class="form-control form-control-lg text-black h-10 resize-none overflow-hidden rounded-full shadow-md pb-2 px-4 border-none w-full" name="mesage"></textarea>
    if (document.querySelector('#form')){
        document.querySelector('#form').addEventListener('submit', mensaje);
    }

    // Envia el mensaje una vez seleccionado el vecino
    if (document.getElementById('Enviar_Mensaje_btn')){
        document.querySelector('#form').addEventListener('submit', mensaje);
    }

    // console.log(id);
    let formdata = new FormData();
    formdata.append('id_usu',id);

    let data = await axios.post(URL_API+'/chatPresidente/list_msg',formdata);

    // console.log(data);
    historyChat.innerHTML = '';
    data.data.forEach(msg => {
        let estilo = '';
        // console.log(msg.user_id, id);
        if (id != msg.user_id){
            estilo = 'msg_historial_enviado';
        } else {
            estilo = 'msg_historial_recibido';
        }
        historyChat.innerHTML += `
        <p class="${estilo}">${msg.msg}</p>`;
    })

    ini_chat(id);

    historyChat.scrollTop = historyChat.scrollHeight;

}

window.addEventListener('load',()=>{
    // Añade un evento al boton de crear un nuevo anuncio
    // if (document.getElementById('Enviar_Mensaje_btn')){
    //     Enviar_Mensaje_btn.addEventListener('click', mensaje);
    // }
    if (document.querySelector('#form')){
        document.querySelector('#form').addEventListener('submit', mensaje);
    }


    // console.log(document.querySelector('[name="busqueda"]'));
    // Filtra segun el buscador
    if (document.querySelector('#f_buscar')){
        document.querySelector('#f_buscar').addEventListener('submit',listarVecinos);
    }

    document.querySelectorAll('.vecino-item').forEach(vecino => {
        vecino.addEventListener('click', mostrarVecino);
    });
});


