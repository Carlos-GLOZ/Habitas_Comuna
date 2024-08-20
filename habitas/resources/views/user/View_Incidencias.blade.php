<x-app-layout>
    @vite('resources/css/incidencia.css')
    @vite('resources/js/Incidencias.js')

    @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)
        
        <style>
            .user{
                display: block;
            }
            .columna{
                width: 25%;
                margin-top: 10px;
                overflow: auto;
            }     
        </style>

    @else
        <style>
            .user{
                display: none;
            }
            .columna{
                width: 33%;
                margin-top: 10px;
                overflow: auto;
            }
            .estados{
                display: none;
            }
        </style>
    
    @endif



    <div class="top flex flex-col h-auto">

    <h1 id="crearIncidencias" class="sticky top-0 text-3xl text-center p-4 font-bold title-incidencia cursor-pointer hover:text-white transition-all self-center shadow-md" style="z-index:1;">{{ __('Incidents') }} <br> <i class="fa-regular fa-circle-plus"></i></h1>


    <div class="columnas_incidencias h-auto">

        <div id="1" class="incidencia columna border border-gray-200 rounded-lg shadow user mt-2.5">    
            <h5 class="mb-2 text-2xl sticky top-0 flex justify-center text-white font-bold tracking-tight  dark:text-white pointer-events-none" style="justify-content: center !important; background-color: #246A9C;">{{ __('To be approved') }}</h5>
            <div class="creacion">
                @foreach ($incidencias as $incidencia)
                    @if($incidencia->estado == 1)
                        <div id="{{ $incidencia->id }}" class="m-2.5 hover:bg-gray-100" draggable="true">
                            <div href="#" class="tarjeta-incidencia test">

                                <div class="flexnoes justify-around">
                                    <div class="textos_inc w-1/2 flex flex-col">
                                        <h5 class="texto1 mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $incidencia->titulo }}</h5>
                                        <p class="descripcion_listado font-normal text-gray-700 dark:text-gray-400">{{ $incidencia->descripcion }}</p>

                                        <div class="estados my-2 grow content-end justify-center sm:justify-start" display="none">

                                            <select class="select_estados" name="id_estados" id="update_estados" style="display: flex; flex-wrap: wrap; align-content: center;" >
                                                <option value="1" content="NO">{{ __('To be approved') }}</option>
                                                <option value="2">{{ __('Open') }}</option>
                                                <option value="3">{{ __('In progress') }}</option>
                                                <option value="4">{{ __('Solved') }}</option>
                                            </select>
    
                                        </div>
                                    </div>
    
                                    <div class="imgn w-0" style="max-height: 450px">
                                        <img class="w-full object-cover imgI" src="{{asset('storage/uploads/incidencia/'.$incidencia->id.'.png')}}">
                                    </div>

                                </div>
                                
                                <div class="botones">

                                    @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)

                                        <button type="button" class="bg-blue-500 hover:bg-red-500 text-white font-bold py-2 px-4 rounded-full boton_elim" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-trash pointer-events-none"></i></button>

                                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white hover:text-black font-bold py-2 px-4 rounded-full boton_ver" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-eye pointer-events-none"></i></button>

                                        <button type="button" class="bg-blue-500 hover:bg-yellow-300 text-white font-bold py-2 px-4 rounded-full boton_editar" data-incId="{{$incidencia->id}}" id="editar_{{$incidencia->id}}">
                                            <i class="fa-solid fa-pen-to-square pointer-events-none" style="color: #ffffff;" ></i>
                                        </button>    
                                    @else
                                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_ver" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-eye pointer-events-none"></i></button>    

                                    @endif  
                                </div>

                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            
        </div>

        <div id="2" class="incidencia columna border border-gray-200 rounded-lg shadow mt-2.5">    
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-white dark:text-white pointer-events-none sticky top-0 flex justify-center" style="background-color: #246A9C;">{{ __('Open') }}</h5>
            <div class="creacion">

                @foreach ($incidencias as $incidencia)

                    @if($incidencia->estado == 2)
                        <div id="{{ $incidencia->id }}" class="m-2.5" draggable="true">
                            <div href="#" class="tarjeta-incidencia test">

                                <div class="flexnoes justify-around">
                                    <div class="textos_inc w-1/2 flex flex-col">
                                        <h5 class="texto1 mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $incidencia->titulo }}</h5>
                                        <p class="descripcion_listado font-normal text-gray-700 dark:text-gray-400">{{ $incidencia->descripcion }}</p>

                                        <div class="estados mt-2 grow content-end justify-center sm:justify-start">

                                            <select class="select_estados" name="id_estados" id="update_estados" style="display: flex; flex-wrap: wrap; align-content: center;" style="display: flex; align">
                                                <option value="2" content="NO">{{ __('Open') }}</option>
                                                <option value="1">{{ __('To be approved') }}</option>
                                                <option value="3">{{ __('In progress') }}</option>
                                                <option value="4">{{ __('Solved') }}</option>
                                            </select>
    
                                        </div>
                                    </div>
    
                                    <div class="imgn w-0" style="max-height: 450px">
                                        <img class="w-full object-cover imgI" src="{{asset('storage/uploads/incidencia/'.$incidencia->id.'.png')}}">
                                    </div>

                                    
                                </div>

                                <div class="botones">
                                    @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)

                                        <button type="button" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_elim" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-trash pointer-events-none"></i></button>
                                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_ver" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-eye pointer-events-none"></i></button>    
                                        <button type="button" class="bg-blue-500 hover:bg-yellow-300 text-white font-bold py-2 px-4 rounded-full hidden boton_editar" data-incId="{{$incidencia->id}}" id="editar_{{$incidencia->id}}">
                                            <i class="fa-solid fa-pen-to-square pointer-events-none" style="color: #ffffff;" ></i>
                                        </button>    

                                    @else
                                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_ver" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-eye pointer-events-none"></i></button>    

                                    @endif
                                </div>

                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div id="3" class="incidencia columna border border-gray-200 rounded-lg shadow">    
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white pointer-events-none" style=" position: sticky; top: 0; display:flex; justify-content: center !important; background-color: #246A9C; color: white">{{ __('In progress') }}</h5>
            <div class="creacion">

                @foreach ($incidencias as $incidencia)

                    @if($incidencia->estado == 3)
                        <div id="{{ $incidencia->id }}" class="m-2.5" draggable="true">
                            <div href="#" class="tarjeta-incidencia test">

                                <div class="flexnoes" style="justify-content: space-around;">
                                    <div class="textos_inc w-1/2 flex flex-col">
                                        <h5 class="texto1 mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $incidencia->titulo }}</h5>
                                        <p class="descripcion_listado font-normal text-gray-700 dark:text-gray-400">{{ $incidencia->descripcion }}</p>

                                        <div class="estados my-2 grow content-end justify-center sm:justify-start">

                                            <select class="select_estados" name="id_estados" id="update_estados" style="display: flex; flex-wrap: wrap; align-content: center;" style="display: flex; align">
                                                <option value="3" content="NO">{{ __('In progress') }}</option>
                                                <option value="1">{{ __('To be approved') }}</option>
                                                <option value="2">{{ __('Open') }}</option>
                                                <option value="4">{{ __('Solved') }}</option>
                                            </select>
                                        </div>
                                    </div>
    
                                    <div class="imgn w-0" style="max-height: 450px">
                                        <img class="w-full object-cover imgI" src="{{asset('storage/uploads/incidencia/'.$incidencia->id.'.png')}}">
                                    </div>
                                    
                                </div>

                                <div class="botones">

                                    @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)

                                        <button type="button" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_elim" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-trash pointer-events-none"></i></button>
                                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_ver" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-eye pointer-events-none"></i></button>    
                                        <button type="button" class="bg-blue-500 hover:bg-yellow-300 text-white font-bold py-2 px-4 rounded-full hidden boton_editar" data-incId="{{$incidencia->id}}" id="editar_{{$incidencia->id}}">
                                            <i class="fa-solid fa-pen-to-square pointer-events-none" style="color: #ffffff;" ></i>
                                        </button>    

                                    @else
                                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_ver" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-eye pointer-events-none"></i></button>    

                                    @endif                                
                                </div>

                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div id="4" class="incidencia columna border border-gray-200 rounded-lg shadow">    
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white pointer-events-none" style="position: sticky; top: 0; display:flex; justify-content: center !important; background-color: #246A9C; color: white;">{{ __('Solved') }}</h5>
            <div class="creacion">

                @foreach ($incidencias as $incidencia)

                    @if($incidencia->estado == 4)
                        <div id="{{ $incidencia->id }}" class="m-2.5" draggable="true">
                            <div href="#" class="tarjeta-incidencia test">

                                <div class="flexnoes" style="justify-content: space-around;">
                                    <div class="textos_inc w-1/2 flex flex-col">
                                        <h5 class="texto1 mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $incidencia->titulo }}</h5>
                                        <p class="descripcion_listado font-normal text-gray-700 dark:text-gray-400">{{ $incidencia->descripcion }}</p>

                                        <div class="estados my-2 grow content-end justify-center sm:justify-start">

                                            <select class="select_estados" name="id_estados" id="update_estados" style="display: flex; flex-wrap: wrap; align-content: center;" style="display: flex; align">
                                                <option value="4" content="NO">{{ __('Solved') }}</option>
                                                <option value="1">{{ __('To be approved') }}</option>
                                                <option value="2">{{ __('Open') }}</option>
                                                <option value="3">{{ __('In progress') }}</option>
                                            </select>
    
                                        </div>
                                    </div>
    
                                    <div class="imgn w-0" style="max-height: 450px">
                                        <img class="w-full object-cover imgI" src="{{asset('storage/uploads/incidencia/'.$incidencia->id.'.png')}}">
                                    </div>
                                    

                                </div>
                                
                                <div class="botones">
                                    @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)

                                        <button type="button" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_elim" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-trash pointer-events-none"></i></button>
                                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_ver" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-eye pointer-events-none"></i></button>  
                                        <button type="button" class="bg-blue-500 hover:bg-yellow-300 text-white font-bold py-2 px-4 rounded-full hidden boton_editar" data-incId="{{$incidencia->id}}" id="editar_{{$incidencia->id}}">
                                            <i class="fa-solid fa-pen-to-square pointer-events-none" style="color: #ffffff;" ></i>
                                        </button>    
  
                                    @else
                                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_ver" data-incId="{{$incidencia->id}}"><i class="fa-solid fa-eye pointer-events-none"></i></button>    

                                    @endif     
                                </div>

                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>


<div id="modalIncidencia" class="hidden fixed z-50 left-0 top-0 w-full h-full overflow-auto bg-black bg-opacity-40">
    
    <div class="relative bg-white m-auto p-0 w-4/5" style="align-items: center; width:500px;">
        <div class=" py-0.5 px-4 bg-sky-800 text-white w-full h-10 mb-2.5">
            <span class="text-white float-right text-2xl mt-0.5 font-bold hover:text-red-600 hover:no-underline hover:cursor-pointer x-cerrar">&times;</span>
            <h2 class="mr-32 mt-2">{{ __('Incident') }}</h2>
        </div>
        <div class="px-5 py-4">
            <!-- <div id="form"> -->
            <form id="form">

                <div class="form-outline mb-4" style="display: flex; flex-direction:column-reverse;">
                    
                    <input id="titulo" type="text" class="form-control form-control-lg" name="Titulo"/>
                    <label class="form-label" >{{ __('Title') }}</label>

                </div>

                <div class="form-outline mb-4" style="display: flex; flex-direction:column-reverse;">
                   
                    <textarea id="descripcion" class="form-control form-control-lg" name="Informacion"></textarea>
                    <label  class="form-label" >{{ __('Information') }}</label>

                </div>
                <div class="form-outline mb-4" style="display: flex; flex-direction:column-reverse;">

                    <label for="Cimagen" class="px-4 py-2 rounded transition-all bg-sky-500 w-fit text-white cursor-pointer hover:bg-sky-700">Añadir imagen</label>
                    <input type="file" class="form-control" style="display:none" name="imagen" id="Cimagen"/>
                    <label  class="form-label mb-1">{{ __('Image') }} (.png / .jpg)</label>

                </div>

                <button type="button" id="crearIncidencias_btn" class="inline-block bg-blue-400 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2 hover:bg-blue-600 hover:text-white transition duration-300 ease-in-out " style="height: 40px">Enviar</button>  
            </form>
            <!-- </div> -->

        </div>
    </div>
</div>



<div id="modalEditarIncidencia" class="hidden fixed z-50 left-0 top-0 w-full h-full overflow-auto bg-black bg-opacity-40">

    <div class="relative bg-white m-auto p-0 w-4/5" style="align-items: center; width:500px;">
        <div class=" py-0.5 px-4 bg-sky-800 text-white w-full h-10 mb-2.5">
            <span class="text-white float-right text-2xl mt-0.5 font-bold hover:text-red-600 hover:no-underline hover:cursor-pointer x-cerrar3">&times;</span>
            <h2 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white" style="color: white">{{ __('Incident') }}</h2>

        </div>
        <div class="px-5 py-4">
            <!-- <div id="form"> -->
            <form id="form2">

                <div class="form-outline mb-4" style="display: flex; flex-direction:column-reverse;">
                    <textarea class="hidden" name="id_editar" id="id_editar"></textarea>
                    <input id="titulo_ed" type="text" class="form-control form-control-lg" name="Titulo_ed" />
                    <label class="form-label" >{{ __('Title') }}</label>

                </div>

                <div class="form-outline mb-4" style="display: flex; flex-direction:column-reverse;">

                    <textarea id="descripcion_ed" class="form-control form-control-lg" name="descripcion_ed"></textarea>
                    <label  class="form-label" >{{ __('Information') }}</label>

                </div>
                <div class="form-outline mb-4" style="display: flex; flex-direction:column-reverse;">
                   
                    <label for="Cimagen_ed" class="px-4 py-2 rounded transition-all bg-sky-500 w-fit text-white cursor-pointer hover:bg-sky-700">Cambiar imagen</label>
                    <input type="file" class="form-control" style="display:none" name="imagen_ed" id="Cimagen_ed"/>
                    <label  class="form-label mb-1" >{{ __('Image') }} (.png / .jpg)</label>

                </div>

                <button type="button" id="actualizarIncidencias_btn" class="inline-block bg-blue-400 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2 hover:bg-blue-600 hover:text-white transition duration-300 ease-in-out" style="height: 40px">Actualizar</button>  
            </form>
            <!-- </div> -->

        </div>
    </div>
</div>

</x-app-layout>