<x-app-layout>
    {{-- CSS del calendario --}}
    @vite('resources/css/calendario.css')

    {{-- CSS del modal de incidencia --}}
    @vite('resources/css/incidencia.css')

    {{-- Establecer permisos en el JS --}}
    @can('presidente', $comunidad)
        <script>
            const isPresidente = true;
        </script>
    @else
        <script>
            const isPresidente = false;
        </script>
    @endcan

    <div class="h-full">
        {{-- Formularios para los actions --}}
        <form action="{{ route('calendario_eventos') }}" method="get" id="form-peticion-eventos"></form>
        <form action="{{ route('calendario_evento', 'replaceable') }}" method="get" id="form-peticion-evento"></form>
        <form action="{{ route('comunidad_incidencias_async') }}" method="get" id="form-peticion-selector-incidencias"></form>
        <form action="{{ route('calendario_evento_destroy', 'replaceable') }}" method="post" id="form-peticion-evento-destroy">@csrf @method('DELETE')</form>

        {{-- Full Calendar --}}
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js"></script>

        {{-- Cargar diferentes JS de calendario según permisos de usuario --}}
        @can('presidente', $comunidad)
            {{-- <script src="{{ asset('js/calendario_admin.js') }}" defer></script> --}}
            @vite('resources/js/calendario_admin.js')
        @else
            {{-- <script src="{{ asset('js/calendario.js') }}" defer></script> --}}
            @vite('resources/js/calendario.js')
        @endcan

        <div id="calendario" class="px-5 py-5" style="height: 300px;">

        </div>

        @can('presidente', $comunidad)
            {{-- Boton de nuevo evento para moviles --}}
            <button id="boton-evento-nuevo-responsive" class="w-14 h-14 bg-sky-600 text-white flex justify-center items-center text-3xl fixed right-[30px] bottom-[110px] z-[30]"
            style="box-shadow: 0px 4px 4px rgb(1, 1, 1, .25); border-radius: 12px;"><i class="fa-solid fa-plus"></i></button>
        @endcan

        @can('presidente', $comunidad)
            {{-- Modal de crear evento --}}
            <div class="caja-modal-eventoNew">
                <div id="caja-modal-eventoNew" class="caja-modal">
                    <form class="caja-modal__content" method="post" action="{{ route('calendario_evento_new') }}" id="form-peticion-evento-crear">
                        @csrf
                        <input class="@error('nombre') input-invalido @enderror" type="text" name="nombre" id="titulo-modal-evento-new" placeholder="{{ __('Event name')}}" value="{{ old('nombre') }}">
                        <div id="caja-fechas-modal-evento-new">
                            <label for="fecha_ini">{{ __('from')}} </label>
                            <input class="@error('fecha_ini') input-invalido @enderror" type="datetime-local" name="fecha_ini" id="fecha-inicio-modal-evento-new" placeholder="{{ __('Start date') }}" style="max-width: 192px" value="{{ old('fecha_ini') }}">
                            <label for="fecha_fin">{{ __('until')}} </label>
                            <input class="@error('fecha_fin') input-invalido @enderror" type="datetime-local" name="fecha_fin" id="fecha-final-modal-evento-new" placeholder="{{ __('End date') }}" style="max-width: 192px" value="{{ old('fecha_fin') }}">
                        </div>
                        <textarea class="@error('descripcion') input-invalido @enderror" name="descripcion" id="descripcion-modal-evento-new" placeholder="{{ __('Description')}}">{{ old('descripcion') }}</textarea>
                        <div class="caja-incidencias-modal-evento">
                            <p id="titulo-incidencias-modal-evento-new">{{ __('Related incidents')}}</p>
                            <div id="incidencias-modal-evento-new">
                                {{-- Añadir nuevas incidencias asociadas--}}
                                <button id="incidencias-modal-evento-new-incidencias-boton">+</button>
                            </div>
                        </div>
                        <button type="submit" style="border: solid 1px rgb(107, 114, 127); padding: 5px;">{{ __('Save')}}</button>
                        <p id="cerrar-modal-evento-new" class="caja-modal__close">&times;</p>
                    </form>
                </div>
            </div>

            {{-- Modal de editar evento --}}
            <div class="caja-modal-eventoEdit">
                <div id="caja-modal-eventoEdit" class="caja-modal">
                    <form class="caja-modal__content" method="post" action="{{ route('calendario_evento_update', ['replaceable']) }}" id="form-peticion-evento-editar" novalidate>
                        @csrf
                        @method('PUT')
                        <input class="@error('nombre') input-invalido @enderror" type="text" name="nombre" id="titulo-modal-evento-edit" placeholder="{{ __('Event name')}}" value="{{ old('nombre') }}">
                        <div id="caja-fechas-modal-evento-edit">
                            <label for="fecha_ini">{{ __('from')}} </label>
                            <input class="@error('fecha_ini') input-invalido @enderror" type="datetime-local" name="fecha_ini" id="fecha-inicio-modal-evento-edit" placeholder="{{ __('Start date') }}" style="max-width: 192px">
                            <label for="fecha_fin">{{ __('until')}} </label>
                            <input class="@error('fecha_fin') input-invalido @enderror" type="datetime-local" name="fecha_fin" id="fecha-final-modal-evento-edit" placeholder="{{ __('End date') }}" style="max-width: 192px">
                        </div>
                        <textarea class="@error('descripcion') input-invalido @enderror" name="descripcion" id="descripcion-modal-evento-edit" placeholder="{{ __('Description')}}" style="max-height: 285px; word-break: break-word"></textarea>
                        <div class="caja-incidencias-modal-evento">
                            <p id="titulo-incidencias-modal-evento-edit">{{ __('Related incidents')}}</p>
                            <div id="incidencias-modal-evento-edit">
                                {{-- Añadir nuevas incidencias asociadas--}}
                                <button id="incidencias-modal-evento-edit-incidencias-boton">+</button>
                            </div>
                        </div>
                        <button type="submit" style="border: solid 1px rgb(107, 114, 127); padding: 5px;">{{ __('Save')}}</button>
                        <p id="cerrar-modal-evento-edit" class="caja-modal__close">&times;</p>
                    </form>
                </div>
            </div>

            {{-- Modal de selector de incidencias --}}
            <div class="caja-modal-selector-incidencias">
                <div id="caja-modal-selector-incidencias" class="caja-modal">
                    <div class="caja-modal__content" style="400px">
                        <div id="caja-modal-selector-incidencias-lista"></div>
                        <p id="cerrar-modal-selector-incidencias" class="caja-modal__close">&times;</p>
                    </div>
                </div>
            </div>

        @endcan

        {{-- Modal de evento --}}
        <div class="caja-modal-eventoInfo">
            <div id="caja-modal-eventoInfo" class="caja-modal">
                <div class="caja-modal__content">
                    <div style="display: flex; flex-direction: row; align-items: center; gap: 10px;">
                        <p id="titulo-modal-evento"></p>
                        @can('presidente', $comunidad)
                            <i id="modal-evento-editar" class="fa-regular fa-pen-to-square" style="cursor: pointer"></i>
                            <i id="modal-evento-eliminar" class="fa-solid fa-ban" style="cursor: pointer"></i>
                        @endcan
                    </div>
                    <div id="caja-fechas-modal-evento">
                        <p id="fecha-inicio-modal-evento"></p>
                        <p id="fecha-final-modal-evento"></p>
                    </div>
                    <p id="descripcion-modal-evento" style="word-break: break-word"></p>
                    <div class="caja-incidencias-modal-evento">
                            <p id="titulo-incidencias-modal-evento">{{ __('Related incidents')}}</p>
                        <div id="incidencias-modal-evento"></div>
                    </div>
                    <p id="cerrar-modal-evento" class="caja-modal__close">&times;</p>
                </div>
            </div>
        </div>

        {{-- Modal de incidencia --}}
        <div id="div-modal-incidencia" style="--ancho_div: 50%;" data-rutaFind="{{ route('incidencia.find', 'replaceable') }}">
            <div class="tarjeta-incidencia">

                <div class="flexnoes justify-around pt-[20px]">
                    <div class="textos_inc w-1/2 flex flex-col">
                        <h5 id="modal-incidencia-titulo" class="texto1 mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-xl text-3xl mb-4"></h5>
                        <p id="modal-incidencia-descripcion" class="descripcion_listado font-normal text-gray-700 dark:text-gray-400"></p>
                    </div>

                    <div class="imgn" style="max-height: 450px; flex-direction: column;">
                        <img id="modal-incidencia-img" class="w-full object-cover imgI max-w-[300px]" data-ruta="{{ asset('storage/uploads/incidencia/') }}">
                        <div class="chip-incidencia-wrapper">
                            <div id="modal-incidencia-estado-color" class="chip-incidencia-punto"></div>
                            <p id="modal-incidencia-estado-texto" class="chip-incidencia-texto"></p>
                        </div>
                    </div>

                </div>

                <p id="cerrar-modal-incidencia" class="caja-modal__close">&times;</p>
            </div>
        </div>
    </div>

    {{-- Responsive calendario --}}
    <style>
        #boton-evento-nuevo-responsive {
            display: none;
        }

        @media (max-width: 840px) {
            .fc-newEvent-button {
                display: none!important;
            }

            .main-container {
                padding: 0;
            }

            #calendario {
                padding-left: 0!important;
                padding-right: 0!important;
                padding-top: 20px!important;
                height: 95%;
            }

            #boton-evento-nuevo-responsive {
                display: block;
            }

            .fc-button-primary {
                background-color: transparent!important;
                border-color: transparent!important;
                color: black!important;
            }

            .fc-button-primary:focus {
                box-shadow: none!important;
            }

            .fc-toolbar-title {
                font-size: 24px!important;
            }
        }
    </style>

    {{-- Estilos modal incidencia --}}
    <style>
        #div-modal-incidencia {
            display: none;
            z-index: 999;
        }
    </style>
</x-app-layout>
