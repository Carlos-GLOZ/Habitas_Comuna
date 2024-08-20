// var mesage = document.getElementById("mesage");


const mensaje=async()=>{
    if (document.querySelector('#form')){
        document.querySelector('#form').removeEventListener('submit', mensaje);
    }

    if (mesage.value != ''){
        let formdata = new FormData()
        formdata.append('mensaje',mesage.value)
        formdata.append('evento',evento)
        
        if (document.querySelector('[name="id_usuario"]')){
            formdata.append('vecino',document.querySelector('[name="id_usuario"]').value)
        }
        l_new_msg(mesage.value);
        mesage.value = '';

        await axios.post(URL_API+'/chatPresidente/EnviarMensaje', formdata)
    }

    if (document.querySelector('#form')){
        document.querySelector('#form').addEventListener('submit', mensaje);
    }
}

var evento;
var channel = false;
var pusher = new Pusher('413c67bec4e72c16bb0d', {
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

const ini_chat=async()=>{

    // Se crea el evento dependiendo del chat de usuario que seleccione el presi/vice
    evento = id_user.value + '_' + id_comunidad.value;

    console.log(evento);
    // console.log('Evento: ',evento);
    var resul = await axios.get(URL_API+'/chatPresidente/info');

    Pusher.logToConsole = true;

    if(!channel){
        channel = pusher.subscribe(resul.data.meet);
    }else{
        try{
            channel.unsubscribeAll();
        }catch(e){

        }
    }

    console.log('Canal: ',resul.data.meet,'  Evento: ',evento);

    var channel = pusher.subscribe(resul.data.meet);

    channel.bind(evento, function(data) {
        console.log(data.id_usuario == id_user.value)
        console.log(data.id_usuario, id_user.value)
        if(data.id_usuario != id_user.value){
            l_new_msg_recibido(data.msg);
            mesage.msg = '';
        }

    });
    // console.log(resul.data);
}


window.addEventListener('load',()=>{
    // Añade un evento al boton de crear un nuevo anuncio
    if (document.getElementById('Enviar_Mensaje_btn')){
        Enviar_Mensaje_btn.addEventListener('click', mensaje);
    }

    if (document.querySelector('#form')){
        document.querySelector('#form').addEventListener('submit', mensaje);
    }

    ini_chat();

    historyChat.scrollTop = historyChat.scrollHeight;
});



