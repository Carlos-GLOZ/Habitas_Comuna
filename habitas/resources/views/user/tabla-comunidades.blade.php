<x-action-section>
    <x-slot name="title">
        {{ __('My Communities') }}
    </x-slot>

    <x-slot name="description">
        {{ __("Manage what communities you're a part of and edit or delete your communities.") }}
    </x-slot>

    <x-slot name="content">
        
        {{-- CSS del calendario para los estilos del modal --}}
        @vite('resources/css/calendario.css')

        <x-input-error for="img" class="m-2" />
        <x-input-error for="nombre" class="m-2" />
        <x-input-error for="codigo" class="m-2" />
        <x-input-error for="presidente_id" class="m-2" />
        <x-input-error for="vicepresidente_id" class="m-2" />
        <x-input-error for="accion" class="m-2" />
        <table class="w-full text-center ">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3 text-center">{{ __('Name') }}</th>
                    <th class="px-6 py-3 text-center responsive-eliminable-sm">{{ __('Code') }}</th>
                    <th class="px-6 py-3 text-center responsive-eliminable-md">{{ __('President') }}</th>
                    <th class="px-6 py-3 text-center responsive-eliminable-md">{{ __('Vicepresident') }}</th>
                    <th class="px-6 py-3 text-center responsive-eliminable-md">{{ __('Email') }}</th>
                    <th class="px-6 py-3 text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody id="ttable">

                {{-- Formularios adicionales --}}
                <form action="{{ route('delComunidad') }}" method="post" id="form-del-comunidad">
                    @csrf 
                    @method('DELETE')
                    <input type="hidden" id="form-del-comunidad-id" name="id">
                </form>

                <form action="{{ route('abandonarComunidad') }}" method="post" id="form-abandonar-comunidad">
                    @csrf 
                    <input type="hidden" id="form-abandonar-comunidad-id" name="id">
                </form>

                <form action="{{ route('comunidad_detalles') }}" method="post" id="form-detalles-comunidad">
                    @csrf 
                    <input type="hidden" id="form-detalles-comunidad-id" name="id">
                </form>
                
                @foreach ($comunidades as $comunidad)
                    <tr>
                        @can('presidente', $comunidad) 
                            {{-- Formulario para el presidente --}}
                            <form action="{{ route('comunidad_editar') }}" method="post">
                                @csrf

                                {{-- Id de la comunidad --}}
                                <input type="hidden" name="id" value="{{ $comunidad->id }}">

                                {{-- Nombre --}}
                                <td class="py-2 px-3"><input type="text" name="nombre" id="" class="border-0 border-b" value="{{$comunidad->nombre}}"></td>

                                {{-- Código --}}
                                <td class="py-2 px-3 responsive-eliminable-sm"><input type="text" name="codigo" id="" class="border-0 border-b" value="{{$comunidad->codigo}}"></td>

                                {{-- Presidente --}}
                                <td class="py-2 px-3 responsive-eliminable-md">
                                    <select name="presidente_id" id="">
                                        @foreach ($comunidad->miembros as $miembro)
                                            @if ($miembro->id == $comunidad->presidente_id)
                                                <option value="{{ $miembro->id }}" selected>{{ $miembro->name }} {{ strtoupper(substr($miembro->apellidos, 0, 1)) }}.</option>
                                            @else
                                                <option value="{{ $miembro->id }}">{{ $miembro->name }} {{ strtoupper(substr($miembro->apellidos, 0, 1)) }}.</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                
                                {{-- Vicepresidente --}}
                                <td class="py-2 px-3 responsive-eliminable-md">
                                    <select name="vicepresidente_id" id="">
                                        <option value="null">{{ __('Unassigned') }}</option>
                                        @foreach ($comunidad->miembros as $miembro)
                                            @if ($miembro->id == $comunidad->vicepresidente_id)
                                                <option value="{{ $miembro->id }}" selected>{{ $miembro->name }} {{ strtoupper(substr($miembro->apellidos, 0, 1)) }}.</option>
                                            @else
                                                <option value="{{ $miembro->id }}">{{ $miembro->name }} {{ strtoupper(substr($miembro->apellidos, 0, 1)) }}.</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>

                                {{-- Correo --}}
                                {{-- <td class="py-2 px-3 responsive-eliminable-md"><button class="meet-copy-button p-2 rounded-l border-2" data-codigo="{{$comunidad->meet}}">Copiar código</button></td> --}}
                                <td class="py-2 px-3 responsive-eliminable-md"><input type="text" name="correo" id="" class="border-0 border-b" value="{{$comunidad->correo}}"></td>

                                {{-- Acciones --}}
                                <td class="py-2 px-3">
                                    <div class="flex gap-1 justify-center">
                                        {{-- <button class="boton-copiar-codigo-comunidad responsive-mostrable-sm rounded-lg px-4 py-2 bg-green-500 text-green-100 hover:bg-green-600 duration-300" data-codigo="{{ $comunidad->codigo }}"><i class="fa-solid fa-copy"></i></button> --}}
                                        <button type="submit" class="boton-guardar-comunidad rounded-lg px-4 py-2 bg-green-500 text-green-100 hover:bg-green-600 duration-300"><i class="fa-solid fa-save"></i></button>
                                        {{-- Al listar los datos de la comunidad, haremos una petición al servidor para que sólo los datos que el usuario tenga permisos para ver --}}
                                        <button value="{{ $comunidad->id }}" class="boton-detalles-comunidad-editar rounded-lg px-4 py-2 bg-blue-500 text-blue-100 hover:bg-blue-600 duration-300"><i class="fa-solid fa-list"></i></button>
                                        <button class="boton-del-comunidad rounded-lg px-4 py-2 bg-red-600 text-red-100 hover:bg-red-700 duration-300" value="{{ $comunidad->id }}"><i class="fa-solid fa-trash"></i></button>
                                        <button class="boton-abandonar-comunidad rounded-lg px-4 py-2 bg-yellow-600 text-yellow-100 hover:bg-yellow-700 duration-300" value="{{ $comunidad->id }}"><i class="fa-solid fa-right-from-bracket"></i></button>
                                    </div>
                                </td>                            
                            </form>
                        @else
                            {{-- Tabla para el usuario --}}

                            {{-- Nombre --}}
                            <td class="py-2 px-3">{{$comunidad->nombre}}</td>

                            {{-- Código --}}
                            <td class="py-2 px-3 responsive-eliminable-sm"> - </td>

                            {{-- Presidente --}}
                            @if ($comunidad->presidente)
                                <td class="py-2 px-3 responsive-eliminable-md">{{ $comunidad->presidente->name }} {{ strtoupper(substr($comunidad->presidente->apellidos, 0, 1)) }}.</td>
                            @else
                                <td class="py-2 px-3 responsive-eliminable-md">{{ __('Unassigned') }}</td>
                            @endif
                            
                            {{-- Vicepresidente --}}
                            @if ($comunidad->vicepresidente)
                                <td class="py-2 px-3 responsive-eliminable-md">{{ $comunidad->vicepresidente->name }} {{ strtoupper(substr($comunidad->vicepresidente->apellidos, 0, 1)) }}.</td>
                            @else
                                <td class="py-2 px-3 responsive-eliminable-md">{{ __('Unassigned') }}</td>
                            @endif

                            {{-- Correo--}}
                            {{-- <td class="py-2 px-3 responsive-eliminable-md"><button class="meet-copy-button p-2 rounded-l border-2" data-codigo="{{$comunidad->meet}}">Copiar código</button></td> --}}
                            <td class="py-2 px-3 responsive-eliminable-md"> - </td>

                            {{-- Acciones --}}
                            <td class="py-2 px-3">
                                <div class="flex gap-1 justify-center">
                                    {{-- Al listar los datos de la comunidad, haremos una petición al servidor para que sólo los datos que el usuario tenga permisos para ver --}}
                                    <button value="{{ $comunidad->id }}" class="boton-detalles-comunidad-info rounded-lg px-4 py-2 bg-blue-500 text-blue-100 hover:bg-blue-600 duration-300"><i class="fa-solid fa-list"></i></button>
                                    <button class="boton-abandonar-comunidad rounded-lg px-4 py-2 bg-yellow-600 text-yellow-100 hover:bg-yellow-700 duration-300" value="{{ $comunidad->id }}"><i class="fa-solid fa-right-from-bracket"></i></button>
                                </div>
                            </td> 
                        @endcan
                    </tr>
                @endforeach

            </tbody>
        </table>
        
        {{-- Script control acciones comunidad --}}
        @vite('resources/js/tablaComunidades.js')

        {{-- Responsive de la tabla --}}
        <style>
            .responsive-mostrable-sm {
                display: none
            }

            @media (max-width: 1500px) {
                .responsive-eliminable-md {
                    display: none;
                }
            }

            @media (max-width: 640px) {
                td>input {
                    width: 160px;
                }

                .boton-guardar-comunidad {
                    display: none
                }

                .boton-del-comunidad {
                    display: none
                }

                .responsive-eliminable-sm {
                    display: none
                }

                .responsive-mostrable-sm {
                    display: block
                }
            }
        </style>

        {{-- Sobreescribir clases para modal --}}
        <style>
            .caja-modal__content {
                width: 80%;
                flex-direction: row;
                justify-content: space-between;
                min-width: 340px;

            }

            @media only screen and (max-width: 400px) {
                .caja-modal__content {
                    width: 90%;
                    flex-direction: column;
                }

                .caja-modal__content>div {
                    width: 100%;
                }
            }
        </style>

        {{-- Modal de editar comunidad --}}
        <div class="caja-modal-comunidadEdit">
            <div id="caja-modal-comunidadEdit" class="caja-modal">
                <form class="caja-modal__content contenidos-modal-comunidad" method="post" action="{{ route('comunidad_editar') }}" enctype="multipart/form-data" id="form-peticion-comunidad-editar" novalidate> 
                    @csrf
                    
                    {{-- Columna de la imagen --}}
                    <div class="w-8/12 h-auto flex items-center justify-center">
                        {{-- Imagen --}}
                        <img src="" >
                        <label for="img" class="h-min comunidad-img-drop-zone" data-ruta="{{ asset('storage/uploads/comunidades/img/') }}" id="img-modal-comunidad-editar">
                            <p class="comunidad-img-dropzone-text w-full h-full text-center flex justify-center items-center">
                                {{ __("Drag and drop a file or select a file to upload") }}
                            </p>
                        </label>
                        <input type="file" id="comunidad-img-input" name="img" hidden>
                    </div>

                    {{-- Columna de la info --}}
                    <div class="w-1/4 flex-col flex justify-between gap-4 pb-2.5">
                        {{-- ID --}}
                            <input type="hidden" name="id" class="modal-editar-comunidad-campo" id="id-modal-comunidad-edit" value="{{ old('id') }}">

                        {{-- Nombre --}}
                        <div>
                            <p class="">{{ __('Name') }}</p>
                            <input class="w-full modal-editar-comunidad-campo @error('nombre') input-invalido @enderror" type="text" name="nombre" id="nombre-modal-comunidad-edit" placeholder="{{__("Community name")}}" value="{{ old('nombre') }}">
                        </div>
                        
                        {{-- Codigo --}}
                        <div>
                            <p class="">{{ __('Code') }}</p>
                            <input class="w-full modal-editar-comunidad-campo @error('codigo') input-invalido @enderror" type="text" name="codigo" id="codigo-modal-comunidad-edit" placeholder="{{__("Community code")}}" value="{{ old('codigo') }}">
                        </div>

                        {{-- Presidente --}}
                        <div>
                            <p class="">{{ __('President') }}</p>
                            <div class="">
                                <select name="presidente_id" id="presidente-modal-comunidad-edit" class="w-full modal-editar-comunidad-campo" data-campoSelects='{"campo":"miembros","value":"id","text":"name"}'>

                                </select>
                            </div>
                        </div>

                        {{-- Vicepresidente --}}
                        <div>
                            <p class="">{{ __('Vicepresident') }}</p>
                            <div class="">
                                <select name="vicepresidente_id" id="vicepresidente-modal-comunidad-edit" class="w-full modal-editar-comunidad-campo" data-campoSelects='{"campo":"miembros","value":"id","text":"name"}'>
                                    <option value="null">{{ __('Unassigned') }}</option>

                                </select>
                            </div>
                        </div>

                        {{-- Correo --}}
                        <div>
                            <p class="">{{ __('Email') }}</p>
                            <input type="text" name="correo" class="w-full modal-editar-comunidad-campo" id="correo-modal-comunidad-edit" class="border-0 border-b">
                        </div>

                        {{-- Meet --}}
                        <div>
                            <p class="">{{ __('Meet Lobby') }}</p>
                            <button name="meet" class="w-full modal-editar-comunidad-campo meet-copy-button p-2 rounded-l border-2" id="meet-modal-comunidad-edit">{{ __('Copy Code') }}</button>
                        </div>

                        <button type="submit" class="w-full" style="border: solid 1px rgb(107, 114, 127); padding: 5px;">{{ __('Save') }}</button>
                        
                        <button type="submit" class="w-full boton-vista-miembro" style="border: solid 1px rgb(107, 114, 127); padding: 5px;">{{ __('Member view') }}</button>
                    </div>
                    <p id="cerrar-modal-comunidad-presidente" class="caja-modal__close">&times;</p> 
                </form> 
            </div>
        </div>

        {{-- Modal de info comunidad --}}
        <div class="caja-modal-comunidadInfo">
            <div id="caja-modal-comunidadInfo" class="caja-modal">
                <div class="caja-modal__content contenidos-modal-comunidad">

                    {{-- Columna de la imagen --}}
                    <div class="w-8/12 h-auto flex items-center justify-center">
                        {{-- Imagen --}}
                        <img src="" class="h-min" data-ruta="{{ asset('storage/uploads/comunidades/img/') }}" alt="Imagen de Comunidad" id="img-modal-comunidad-info">
                    </div>

                    {{-- Columna de la info --}}
                    <div class="w-1/4 flex-col flex justify-between gap-4 pb-2.5">

                        {{-- Nombre --}}
                        <div>
                            <p class="">{{ __('Name') }}</p>
                            <p class="w-full text-lg modal-info-comunidad-campo" id="nombre-modal-comunidad-info" data-campo='{"campo":"nombre"}'></p>
                        </div>
                        
                        {{-- Codigo --}}
                        {{-- <div>
                            <p class="">{{ __('Code') }}</p>
                            <p class="w-full text-lg modal-info-comunidad-campo" id="codigo-modal-comunidad-info" data-campo='{"campo":"codigo"}'></p>
                        </div> --}}
        
                        {{-- Presidente --}}
                        <div>
                            <p class="">{{ __('President') }}</p>
                            <p class="w-full text-lg modal-info-comunidad-campo" id="codigo-modal-comunidad-info" data-campo='{"campo":"presidente","columna":"name"}'></p>
                        </div>
        
                        {{-- Vicepresidente --}}
                        <div>
                            <p class="">{{ __('Vicepresident') }}</p>
                            <p class="w-full text-lg modal-info-comunidad-campo" id="codigo-modal-comunidad-info" data-campo='{"campo":"vicepresidente","columna":"name"}'></p>
                        </div>
        
                        {{-- Correo --}}
                        {{-- <div>
                            <p class="">{{ __('Email') }}</p>
                            <p class="w-full text-lg modal-info-comunidad-campo" id="codigo-modal-comunidad-info" data-campo='{"campo":"correo"}'></p>
                        </div> --}}

                        {{-- Meet --}}
                        <div>
                            <p class="">{{ __('Meet Lobby') }}</p>
                            <button name="meet" class="w-full modal-info-comunidad-campo meet-copy-button p-2 rounded-l border-2" id="meet-modal-comunidad-info" data-campo='{"campo":"meet", "atributo": "value"}'>{{ __('Copy Code') }}</button>
                        </div>
                    </div>
    
                    <p id="cerrar-modal-comunidad-info" class="caja-modal__close">&times;</p> 

                </div>
            </div>
        </div>

        {{-- Estilos de los modales --}}
        <style>
            .contenidos-modal-comunidad {
                max-height: 90%;
                overflow-y: scroll
            }

            @media (max-width: 640px) {
                #img-modal-comunidad-editar {
                    height: auto;
                }
                #img-modal-comunidad-info {
                    height: auto;
                }
            }
        </style>

        {{-- Mensaje de codigo copiado --}}
        <div id="mensaje-temporal-box" class="max-w-max bg-gray-100 text-black text-sm p-2 rounded-md border-2 fixed opacity-0 z-[1000]">
            <p id="mensaje-temporal"></p>
        </div>

        {{-- Script para copiar el codigo meet y de comunidad al portapapeles --}}
        <script>
            // Codigo meet
            const botonesMeet = document.getElementsByClassName('meet-copy-button');

            const mensajeCopiaBox = document.getElementById('mensaje-temporal-box');
            const mensajeCopiaVerticalOffsetPx = 3;

            for (let i = 0; i < botonesMeet.length; i++) {
                const boton = botonesMeet[i];
                
                boton.addEventListener('click', (e) => {
                    e.preventDefault();

                    // Copiar código
                    navigator.clipboard.writeText(boton.value)

                    // Mostrar mensaje de copia
                    const x = event.clientX - parseInt(getComputedStyle(mensajeCopiaBox).width)/2;
                    const y = event.clientY - parseInt(getComputedStyle(mensajeCopiaBox).height) - mensajeCopiaVerticalOffsetPx; // Añadir offset

                    // Cambiar posición del elemento
                    mensajeCopiaBox.style.left = x + 'px';
                    mensajeCopiaBox.style.top = y + 'px';

                    // Mostrar elemento
                    document.getElementById('mensaje-temporal').innerText = "Codigo meet copiado";
                    mensajeCopiaBox.style.opacity = 1;

                    // Difuminar elemento
                    setTimeout(() => {
                        let opacityInterval = setInterval(() => {
                            mensajeCopiaBox.style.opacity -= .005;

                            if (parseFloat(mensajeCopiaBox.style.opacity) <= 0) {
                                mensajeCopiaBox.style.top = '0px';
                                
                                clearInterval(opacityInterval);
                            }
                        }, 1);
                    }, 1000);
                });
            }

            // Codigo comunidad
            const botonesComunidad = document.getElementsByClassName('boton-copiar-codigo-comunidad');

            for (let i = 0; i < botonesComunidad.length; i++) {
                const boton = botonesComunidad[i];
                
                boton.addEventListener('click', (e) => {
                    e.preventDefault();

                    // Copiar código
                    navigator.clipboard.writeText(boton.dataset.codigo)

                    // Mostrar mensaje de copia
                    const x = event.clientX - parseInt(getComputedStyle(mensajeCopiaBox).width)/2;
                    const y = event.clientY - parseInt(getComputedStyle(mensajeCopiaBox).height) - mensajeCopiaVerticalOffsetPx; // Añadir offset

                    // Cambiar posición del elemento
                    mensajeCopiaBox.style.left = x + 'px';
                    mensajeCopiaBox.style.top = y + 'px';

                    // Mostrar elemento
                    document.getElementById('mensaje-temporal').innerText = "{{ __('Community code copied') }}";
                    mensajeCopiaBox.style.opacity = 1;

                    // Difuminar elemento
                    setTimeout(() => {
                        let opacityInterval = setInterval(() => {
                            mensajeCopiaBox.style.opacity -= .005;

                            if (parseFloat(mensajeCopiaBox.style.opacity) <= 0) {
                                mensajeCopiaBox.style.top = '0px';
                                
                                clearInterval(opacityInterval);
                            }
                        }, 1);
                    }, 1000);
                });
            }
        </script>

        {{-- Estilo del drag&drop de la imagen --}}
        <style>
            .comunidad-img-drop-zone {
                max-width: 460px;
                height: 450px;
                border: 2px dashed gray;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                width: 100%;
                border-radius: 10px;

                background-size: cover;
                background-position: center;

                color: transparent;
                transition-duration: .3s;              
            }

            .comunidad-img-dropzone-text {
                font-size: 24px;
                text-align: center;
                padding: 20px;
                transition-duration: .2s;              
                font-weight: 500;  
            }

            .comunidad-img-dropzone-text:hover {
                color: black;
                backdrop-filter: contrast(0.3) blur(5px);
            }

            #comunidad-img-input {
                object-fit: cover;
                width: 100%;
                height: 100%;
                display: none;
            }
        </style>
    </x-slot>
</x-action-section>