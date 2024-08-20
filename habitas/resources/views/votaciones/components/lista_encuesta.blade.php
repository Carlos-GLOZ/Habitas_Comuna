@vite('resources/css/encuestas.css')
@if(!isset($FRAME))
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{asset('js/fontawesome.js')}}"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const URL_API = "{{url('/')}}"
    </script>
@endif
<div class="contenedor-lista-enc">

    <table class="w-fit text-sm text-left text-gray-500 dark:text-gray-400 ">
        <thead class="text-xs text-gray-800 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">{{ __('Name')}}</th>
                <th scope="col" class="px-6 py-3 text-center">{{ __('Dates')}}</th>
                <th scope="col" class="px-6 py-3">{{ __('Action')}}</th>
                @if(isset($FRAME))
                <th scope="col" class="px-6 py-3">{{ __('Give up vote')}}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($encuestas as $encuesta)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{$encuesta->nombre}}</td>
                    <td class="px-6 py-4">{{$encuesta->updated_at}}</td>

                    <td class="px-6 py-4 text-center ">
                        @if($encuesta->estado == 1)
                            @if(isset($FRAME))
                                <a href="{{url('/votacion/votar/'.$encuesta->id)}}"><button class="rounded-lg px-4 py-2 bg-green-700 text-green-100 hover:bg-green-800 duration-300"><i class="fa-solid fa-person-booth"></i></button></a>
                            @else
                                <a href="{{url('/votacion/comp/votar/'.$encuesta->id)}}"><button class="rounded-lg px-4 py-2 bg-green-700 text-green-100 hover:bg-green-800 duration-300"><i class="fa-solid fa-person-booth"></i></button></a>
                            @endif
                        @elseif ($encuesta->estado == 2)

                            @if(isset($FRAME))
                                <a href="{{url('/votacion/stats/'.$encuesta->id)}}"><button class="rounded-lg px-4 py-2 bg-blue-500 text-blue-100 hover:bg-blue-600 duration-300"><i class="fa-solid fa-eye"></i></button></a>
                            @else

                                <a href="{{url('/votacion/comp/stats/'.$encuesta->id)}}"><button class="rounded-lg px-4 py-2 bg-blue-500 text-blue-100 hover:bg-blue-600 duration-300"><i class="fa-solid fa-eye"></i></button></a>
                            @endif
                        @else
                        <i class="fa-regular fa-circle-pause"></i>
                        @endif
                    </td>
                    @if(isset($FRAME))
                    <td class="px-6 py-4 text-center">
                        <button class="btn-ceder-boton" id="{{$encuesta->id}}"><i class="fa-solid fa-eye pointer-events-none"></i></button>
                    </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

<script type="text/javascript">
var En;
let nE = document.createElement('div');
nE.className = 'contenedor-modal hidden';
nE.innerHTML = `<div class="relative bg-white m-auto p-0 w-80">
        <div class=" py-0.5 px-4 bg-sky-800 text-white w-full h-10 mb-2.5">
            <span class="text-white float-right text-2xl mt-0.5 font-bold hover:text-red-600 hover:no-underline hover:cursor-pointer x-cerrar">&times;</span>
            <h2 class=" mr-32 mt-2">{{ __('Give up vote')}}</h2>
        </div>
        <div class="px-5 py-4">
                <div class="form-outline mb-4 flex flex-wrap">

                    <p class="block w-full flex justify-between">Vecino <span id="iconos_bus"><button id="bVecino" class="bg-blue-400 rounded-sm float-left px-3 py-1 text-sm font-semibold text-gray-700 mb-2 hover:bg-blue-600 text-white transition duration-300 ease-in-out">{{ __('Search')}}</button></span></p>

                    <input type="hidden" name="usuario" value="">
                    <input type="text" class=" float-left form-control form-control-lg w-2/4" name="nVecino" placeholder="Pablo..." />
                    <input type="text" class=" float-left form-control form-control-lg w-2/4" name="aVecino" placeholder="Escobar..." />

                    <div class="w-full relative">
                        <ul id="mVecinos" style="max-height: 23vh;" class="w-full h-fit overflow-auto absolute bg-slate-50 shadow-md">
                        </ul>
                    </div>

                    <textarea name="descripcion" rows="3" placeholder="Porque..." class="w-full mt-5"></textarea>

                </div>
                <button class="btn-delegar-voto" style="height: 40px">{{ __('Give up vote')}}</button>
        </div>
    </div>`;

document.querySelector('.contenedor-lista-enc').appendChild(nE);

const newDeVoto = async() => {
    const formdata = new FormData();
    formdata.append('descripcion', document.querySelector('[name="descripcion"]').value);
    formdata.append('nombre_receptor', document.querySelector('[name="nVecino"]').value);
    formdata.append('receptor_id', document.querySelector('[name="usuario"]').value);
    formdata.append('encuesta_id', En);

    let data = await axios.post(URL_API+'/votacion/delegar',formdata);
}

const aModal = (e) => {
    document.querySelector('.contenedor-modal').classList.remove('hidden');
    document.querySelector('.x-cerrar').addEventListener('click',cModal);

    En = e.target.id;
    document.querySelector('.btn-delegar-voto').addEventListener('click',newDeVoto);
}
const cModal = () => {
    document.querySelector('.contenedor-modal').classList.add('hidden');
    document.querySelector('.x-cerrar').removeEventListener('click',cModal);
}

const obtenerVecino = (e) => {
    const inf = e.target;

    document.querySelector('[name="usuario"]').value = inf.firstElementChild.value;
    document.querySelector('[name="nVecino"]').value = inf.children[2].innerText;
    document.querySelector('[name="aVecino"]').value = inf.children[3].innerText;
}

const limpiarBusqueda = (e) => {

    document.querySelectorAll('.item-vecino').forEach(vecino => {
        vecino.removeEventListener('click',obtenerVecino);
        vecino.remove();
    });

    us.removeEventListener('click',limpiarBusqueda);
    us.remove();
}

const buscar = async(e) => {
    let op1 = '';
    const formdata = new FormData();
    formdata.append('nombre',document.querySelector('[name="nVecino"]').value);
    formdata.append('apellidos',document.querySelector('[name="aVecino"]').value);
    let data = await axios.post(URL_API+'/votacion/lista_vec/', formdata);

    document.querySelector('#mVecinos').innerHTML = ``;
    // console.log(data.data);

    data.data.forEach(vecino => {
        if (vecino.visible == 1){
            op1 = `<img class="w-12 h-12 block mx-auto rounded-full sm:mx-0 sm:shrink-0" src="${vecino.profile_photo_url}">`;
        } else {
            op1 = '<img class="w-12 h-12 block mx-auto rounded-full sm:mx-0 sm:shrink-0" src="https:\/\/ui-avatars.com\/api\/?name={{ __("Vecino Anonimo") }}&color=7F9CF5&background=EBF4FF" alt="Womans Face">';
        }
        document.querySelector('#mVecinos').innerHTML += `
        <li class="py-3 px-2 item-vecino inline-flex items-center w-full border-b hover:bg-slate-200 cursor-pointer">
            <input type="hidden" name="vecino" value="${vecino.id}"> ${op1} <span class="campo_nombre px-3 pointer-events-none">${vecino.name}</span> <span class="campo_apellido px-3 pointer-events-none">Apellidos</span>
        </li>`;
    });

    document.querySelectorAll('.item-vecino').forEach(vecino => {
        vecino.addEventListener('click',obtenerVecino);
    })

    if (!document.getElementById('us')){
        const newOpt = document.createElement('button');
        newOpt.setAttribute('id','us');
        newOpt.setAttribute('title','Este boton limpia los resultados de la búsqueda.');
        newOpt.className = "bg-yellow-400 rounded-sm px-3 py-1 h-7 mb-2 ml-2 text-black cursor-pointer";
        newOpt.innerHTML = '<i class="fa-regular fa-broom-wide fa-fade pointer-events-none"></i>';
        iconos_bus.appendChild(newOpt);

        us.addEventListener('click',limpiarBusqueda);
    }
}

let ceder = document.querySelectorAll('.btn-ceder-boton');
ceder.forEach(boton => {
    boton.addEventListener('click',aModal);
})

document.querySelector('#bVecino').addEventListener('click',buscar);
</script>
