
const ini_meet=async()=>{

    var resul = await axios.get(URL_API+'/meeting/info');

    Pusher.logToConsole = false;

    var pusher = new Pusher('413c67bec4e72c16bb0d', {
    cluster: 'eu'
    });

    var channel = pusher.subscribe(resul.data.meet);
    channel.bind('encuesta', function(data) {
        console.log(data);
        votar(data.id)
    });

    console.log(resul.data);
}

ini_meet()
