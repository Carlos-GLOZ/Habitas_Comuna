@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{asset('js/fontawesome.js')}}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const URL_API = "{{url('/')}}"
</script>

<div class="flex justify-center min-w-full h-full flex-col items-center">

    <table class="w-fit text-sm text-left text-gray-500 dark:text-gray-400">
        <caption class="w-full bg-gray-50 border-b-2 py-3">
            <button class="ml-2 px-5 py-2 bg-cyan-500 rounded text-white transition-all duration-200 hover:bg-cyan-700" id="votar">Enviar</button>
        </caption>
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Pregunta</th>
                <th scope="col" class="px-6 py-3 text-center">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($preguntas as $pregunta)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{$pregunta->pregunta}}</td>
                    <td class="px-6 py-4">
                        <select id="{{$pregunta->id}}" name="pregunta">
                            <option value="NULL">Blanco</option>
                            @foreach ($pregunta->opciones as $opcion)
                                <option value="{{$opcion->id}}">{{$opcion->opcion}}</option>
                            @endforeach
                        </select>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

</div>


<script>
    votar.addEventListener('click', function(){
        enviarVotos()
    });
    const enviarVotos = async() => {
        const preguntas = document.querySelectorAll('select[name="pregunta"]');
        const valores = [];
        preguntas.forEach(pregunta => {
            valores.push([pregunta.id, pregunta.value]);
        });
        let formdata = new FormData()
        formdata.append('votacion',JSON.stringify(valores) )
        formdata.append('encuesta',"{{$id}}")
        let resul = await axios.post(URL_API+'/votacion/votar_encuesta', formdata)
        console.log(resul);

        if(resul.data == 'votado'){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Ya has votado',
            })
        }else if(resul.data == 'delegado'){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Has delegado el voto',
            })
        }else{
            Swal.fire({
            icon: 'success',
            title: 'Voto enviado correctamente',
            })
        }

        //window.location = URL_API+'/votacion/lista_encuestas/';
    }
</script>
