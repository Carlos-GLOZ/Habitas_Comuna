@if(!isset($FRAME))
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{asset('js/fontawesome.js')}}"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const URL_API = "{{url('/')}}"
    </script>
@endif


<div class="flex justify-center min-w-full h-full flex-col items-center">

    <table class="w-fit text-sm text-left text-gray-500 shadow dark:text-gray-400">
        <caption class="w-full bg-gray-50 border-b-2 shadow">
            <form action="{{route('encuesta_crear')}}" method="POST" class="px-6 py-3 text-right flex items-center flex-wrap sm:flex-nowrap">
                @csrf
                <h3 class="uppercase pb-2 sm:pb-0 text-black inline-block font-bold text-xl text-center grow w-full sm:w-auto">{{ __('Polls')}}   </h3>
                <div class="flex">
                    <input type="text" id="nueva_junta" name="nombre" class="h-9 rounded grow" placeholder="{{ __('New board...')}}" />
                    <button id="crear_junta" class="ml-2 px-5 py-2 bg-cyan-500 rounded text-white transition-all duration-200 hover:bg-cyan-700">{{ __('Create')}}</button>
                </div>
            </form>
        </caption>
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 w-full">
            <tr>
                <th scope="col" class="px-6 py-3">{{ __('Name')}}</th>
                <th scope="col" class="px-6 py-3 text-center hidden sm:block">{{ __('Dates')}}</th>
                {{-- <th scope="col" class="px-6 py-3">{{ __('See')}}</th>
                <th scope="col" class="px-6 py-3">{{ __('Delete')}}</th>
                <th scope="col" class="px-6 py-3 text-center">{{ __('Phase')}}</th> --}}
                <th scope="col" class="px-6 py-3 text-center">{{ __('Actions')}}</th>
                <th scope="col" class="px-6 py-3 text-center">{{ __('Phase')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($encuestas as $encuesta)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{$encuesta->nombre}}</td>
                    <td class="px-6 py-4 hidden sm:table-cell">{{$encuesta->updated_at}}</td>
                    <td class="px-6 py-4 text-center flex justify-around">
                        <a href="{{url('votacion/encuestas/'.$encuesta->id)}}"><i class="fa-regular fa-pen-to-square"></i></a>
                    {{-- </td> --}}
                    {{-- <td class="px-6 py-4 text-center "> --}}
                        @if ($encuesta->estado == '0')
                            <button onclick="delEncuesta({{$encuesta->id}})"><i class="fa-regular fa-trash hover:text-red-600 hover:font-bold transition-all duration-200"></i></button>
                        @else
                            <!-- No es posible -->
                            <i class="fa-regular fa-trash-slash dis"></i>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if ($encuesta->estado == '0')
                            <button onclick="activarEncuesta({{$encuesta->id}})">{{ __('Activate')}}</button>
                        @elseif ($encuesta->estado == '1')
                            <button onclick="cerrarEncuesta({{$encuesta->id}})">{{ __('Close')}}</button>
                        @else
                            @if(isset($FRAME))
                                <a href="{{url('/votacion/stats/'.$encuesta->id)}}" >{{ __('Data')}}</a>
                            @else
                                <a href="{{url('/votacion/comp/stats/'.$encuesta->id)}}" >{{ __('Data')}}</a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
    <script>
        const delEncuesta=async(id)=>{
            let resul = await axios.delete(URL_API+'/votacion/encuesta/'+id);
            console.log(resul);
            listar();
        }
        const activarEncuesta=async(id)=>{
            let resul = await axios.put(URL_API+'/votacion/activarEncuesta/'+id);
            if(resul.status == 200){
                console.log(resul);
                //window.location = URL_API+'/stats/'+id;
            }else{
                console.log(resul);
            }
            listar();
        }
        const cerrarEncuesta=async(id)=>{
            let resul = await axios.put(URL_API+'/votacion/cerrarEncuesta/'+id);
            if(resul.status == 200){
                console.log(resul);
                //window.location = URL_API+'/stats/'+id;
            }else{
                console.log(resul.data);
            }
            listar();
        }
        const listar=()=>{
            location.reload();
        }
    </script>
