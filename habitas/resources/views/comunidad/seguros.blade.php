<x-app-layout>

    <div class="w-full flex justify-center flex-col">
        
        {{-- Listado de Seguros --}}
        <div class="p-6 grid grid-cols-1 gap-16 w-full">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Insurances') }} - {{ $comunidad->nombre }}</h3>
            </div>

            {{-- Crear seguro (cambiará a editar seguro cuando le demos al botón de editar un seguro)--}}
            @can('presidente', $comunidad)
                <form method="POST" id="form-crear-seguro" action="{{ route('seguros.create') }}" data-rutacreate="{{ route('seguros.create') }}" data-rutaupdate="{{ route('seguros.update') }}" data-rutafind="{{ route('seguros.find', 'replaceable') }}" data-rutadelete="{{ route('seguros.delete') }}" class="py-8 px-8 mx-auto bg-white rounded-xl shadow-lg space-y-2 sm:flex sm:items-center sm:space-y-0 sm:space-x-6 gap-6 w-full flex flex-col">
                    @csrf

                    <input type="hidden" id="campo-id-formulario-seguro" name="id" value="{{ old('id') }}">

                    <div style="margin: 0" class="text-center space-y-2 sm:text-left w-full">
                        <div class="space-y-0.5 flex flex-col w-full seguro-inner">
                            @error('id')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            @error('nombre')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            @error('fecha_fin')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            @error('correo')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            @error('tel')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            @error('web')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            @error('direccion')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            @error('cuota')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            @error('num_polizas')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror

                            {{-- Header --}}
                            <div class="w-full text-4xl py-0 font-medium flex flex-row items-center justify-between gap-3 flex-wrap seguro-header">
                                <input type="text" name="nombre" class="w-fit text-xl py-1 border-gray-300 dark:border-gray-700 rounded-md @error('nombre') border-red-500 @enderror" placeholder="{{ __('Insurance Name') }}" value="{{ old('nombre') }}">
                                <div class="text-lg">
                                    <label for="fecha_fin">{{ __('expires on') }}</label>
                                    <input type="date" name="fecha_fin" class="w-fit py-1 border-gray-300 dark:border-gray-700 rounded-md @error('fecha_fin') border-red-500 @enderror" placeholder="{{ __('Insurance Expiration date') }}" value="{{ old('fecha_fin') }}">
                                </div>
                            </div>

                            <x-section-border />

                            {{-- Secciones --}}
                            <div class="w-full flex flex-row justify-around flex-wrap gap-10">
                                <div class="flex flex-col gap-4 max-w-[33%] seguro-seccion">
                                    <p class="text-3xl font-medium text-ellipsis overflow-x-hidden">{{ __('Contact') }}</p>
                                    {{-- <a class="text-lg underline text-ellipsis overflow-x-hidden" href="mailto:{{ $seguro->correo }}">{{ $seguro->correo }}</a> --}}
                                    <input type="email" name="correo" class="py-1 font-medium border-gray-300 dark:border-gray-700 rounded-md @error('correo') border-red-500 @enderror" placeholder="{{ __('Contact Email') }}" value="{{ old('correo') }}">
                                    {{-- <a class="text-lg underline text-ellipsis overflow-x-hidden" href="tel:{{ $seguro->tel }}">{{ $seguro->tel }}</a> --}}
                                    <input type="tel" name="tel" class="py-1 font-medium border-gray-300 dark:border-gray-700 rounded-md @error('tel') border-red-500 @enderror" placeholder="{{ __('Contact Phone Number') }}" value="{{ old('tel') }}">
                                </div>
                                
                                <div class="flex flex-col gap-4 max-w-[33%] seguro-seccion">
                                    <p class="text-3xl font-medium text-ellipsis overflow-x-hidden">{{ __('Addresses') }}</p>
                                    {{-- <a class="text-lg underline text-ellipsis overflow-x-hidden" href="https://{{ $seguro->web }}" target="_blank">{{ $seguro->web }}</a> --}}
                                    <div class="flex flex-row gap-2 items-center">
                                        <p>https://</p>
                                        <input type="text" name="web" class="py-1 font-medium border-gray-300 dark:border-gray-700 rounded-md @error('web') border-red-500 @enderror" placeholder="{{ __('Insurance Website') }}" value="{{ old('web') }}">
                                    </div>
                                    {{-- <p class="text-lg">{{ $seguro->direccion }}</p> --}}
                                    <input type="text" name="direccion" class="py-1 font-medium border-gray-300 dark:border-gray-700 rounded-md @error('direccion') border-red-500 @enderror" placeholder="{{ __('Insurance Address') }}" value="{{ old('direccion') }}">
                                </div>

                                <div class="flex flex-col gap-4 max-w-[33%] seguro-seccion">
                                    <p class="text-3xl font-medium">{{ __('Quota') }}</p>
                                    <div class="flex flex-row gap-2 items-center">
                                        {{-- <p class="text-lg">{{ $seguro->cuota }}/{{ __('month') }}</p> --}}
                                        <input type="number" step="0.01" name="cuota" class="py-1 font-medium border-gray-300 dark:border-gray-700 rounded-md @error('cuota') border-red-500 @enderror" placeholder="{{ __('Insurance Quota') }}" value="{{ old('cuota') }}">
                                        <p>/{{ __('month') }}</p>
                                    </div>
                                    <div>
                                        {{-- <p class="text-lg">*****{{ Str::substr($seguro->num_polizas, count(explode($seguro->num_polizas, ''))-5, 5) }}</p>
                                        <button class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border 
                                        border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 
                                        dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 
                                        dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 
                                        focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 
                                        transition ease-in-out duration-150 mt-2 mr-2
                                        poliza-copy-button"
                                        value="{{ $seguro->num_polizas }}">{{ __('Copy Number') }}</button> --}}
                                        <input type="text" name="num_polizas" class="py-1 font-medium border-gray-300 dark:border-gray-700 rounded-md @error('num_polizas') border-red-500 @enderror" placeholder="{{ __('Insurance Number') }}" value="{{ old('num_polizas') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex justify-between pt-4">
                        <button id="boton-limpiar-formulario-seguro" type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Clear') }}
                        </button>
                        
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            @endcan

            @foreach($comunidad->seguros as $seguro)

                <div class="py-8 px-8 mx-auto bg-white rounded-xl shadow-lg space-y-2 sm:flex sm:items-center sm:space-y-0 sm:space-x-6 gap-6 w-full">

                    <div style="margin: 0" class="text-center space-y-2 sm:text-left w-full">
                        <div class="space-y-0.5 flex flex-col w-full seguro-inner">
                            {{-- Header --}}
                            <div class="w-full text-4xl py-0 font-medium flex flex-row items-center justify-between gap-3 flex-wrap seguro-header">
                                <div class="flex items-center gap-4 flex-wrap justify-center">
                                    {{ $seguro->nombre }}
                                    @can('presidente', $comunidad)
                                        <i class="seguro-editar-boton fa-regular fa-pen-to-square text-lg h-[30px]" style="cursor: pointer" data-seguroid="{{ $seguro->id }}"></i>
                                        <i class="seguro-eliminar-boton fa-solid fa-ban text-lg h-[30px]" style="cursor: pointer" data-seguroid="{{ $seguro->id }}"></i>
                                    @endcan
                                </div>
                                <p class="text-lg">{{ __('expires on')}} {{ $seguro->fecha_fin }}</p>
                            </div>

                            <x-section-border />

                            {{-- Secciones --}}
                            <div class="w-full flex flex-row justify-around flex-wrap gap-10 seguro-secciones">
                                <div class="flex flex-col gap-4 max-w-[33%] seguro-seccion">
                                    <p class="text-3xl font-medium text-ellipsis overflow-x-hidden">{{ __('Contact') }}</p>
                                    <a class="text-lg underline text-ellipsis overflow-x-hidden" href="mailto:{{ $seguro->correo }}">{{ $seguro->correo }}</a>
                                    <a class="text-lg underline text-ellipsis overflow-x-hidden" href="tel:{{ $seguro->tel }}">{{ $seguro->tel }}</a>
                                </div>
                                
                                <div class="flex flex-col gap-4 max-w-[33%] seguro-seccion">
                                    <p class="text-3xl font-medium text-ellipsis overflow-x-hidden">{{ __('Addresses') }}</p>
                                    <a class="text-lg underline text-ellipsis overflow-x-hidden" href="https://{{ $seguro->web }}" target="_blank">{{ $seguro->web }}</a>
                                    <p class="text-lg">{{ $seguro->direccion }}</p>
                                </div>

                                <div class="flex flex-col gap-4 max-w-[33%] seguro-seccion">
                                    <p class="text-3xl font-medium">{{ __('Quota') }}</p>
                                    <p class="text-lg">{{ $seguro->cuota }}/{{ __('month') }}</p>
                                    <div>
                                        <p class="text-lg">*****{{ Str::substr($seguro->num_polizas, count(explode($seguro->num_polizas, ''))-5, 5) }}</p>
                                        <button class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border 
                                        border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 
                                        dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 
                                        dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 
                                        focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 
                                        transition ease-in-out duration-150 mt-2 mr-2
                                        poliza-copy-button"
                                        value="{{ $seguro->num_polizas }}">{{ __('Copy Number') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Mensaje de codigo copiado --}}
    <div id="mensaje-temporal-box" class="max-w-max bg-gray-100 text-black text-sm p-2 rounded-md border-2 fixed opacity-0 z-[1000]">
        <p id="mensaje-temporal"></p>
    </div>

    {{-- Script para copiar el numero de póliza --}}
    <script>
        
        // Numero de póliza
        const numPoliza = document.getElementsByClassName('poliza-copy-button');

        const mensajeCopiaBox = document.getElementById('mensaje-temporal-box');
        const mensajeCopiaVerticalOffsetPx = 3;

        for (let i = 0; i < numPoliza.length; i++) {
            const boton = numPoliza[i];
            
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
                document.getElementById('mensaje-temporal').innerText = "{{ __('Insurance number copied') }}";
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

    <style>
        @media (max-width: 573px) {
            .seguro-inner {
                gap: 40px
            }

            .seguro-seccion {
                max-width: 100%;
                width: 100%;
                text-align: left;
            }

            .seguro-header {
                align-items: flex-start
            }
        }
    </style>

    @can('presidente', $comunidad)
        {{-- Script para controles de presidente --}}
        @vite('resources/js/seguros_admin.js')
    @endcan

</x-app-layout>