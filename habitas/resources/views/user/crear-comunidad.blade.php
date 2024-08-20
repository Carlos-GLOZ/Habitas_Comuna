<x-form-section submit="crearComunidad">
    <x-slot name="title">
        {{ __('Create Community') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Create a new community with a secret code. Other people will need this code to join it.') }}
    </x-slot>

    <x-slot name="form">
        {{-- <div class="col-span-6 sm:col-span-4"> --}}
            <form id="formulario-crear-comunidad" method="POST" enctype="multipart/form-data" action="{{ route('comunidad_crear') }}" class="flex flex-col pt-4 m-auto px-6 h-full justify-between">
                @csrf
                
                <!-- Nombre -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="crear_nombre" value="{{ __('Name') }}" />
                    <x-input id="crear_nombre" name="crear_nombre" type="text" class="mt-1 block w-full" wire:model.defer="nombre" autocomplete="nombre" />
                    <x-input-error for="crear_nombre" class="mt-2" />
                </div>

                <!-- Codigo -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="crear_codigo" value="{{ __('Code') }}" />
                    <x-input id="crear_codigo" name="crear_codigo" type="text" class="mt-1 block w-full" wire:model.defer="codigo" autocomplete="codigo" />
                    <x-input-error for="crear_codigo" class="mt-2" />
                </div>

                <!-- Correo -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="crear_correo" value="{{ __('Email') }}" />
                    <x-input id="crear_correo" name="crear_correo" type="text" class="mt-1 block w-full" wire:model.defer="correo" autocomplete="correo" />
                    <x-input-error for="crear_correo" class="mt-2" />
                </div>

                <!-- Imagen -->
                <div class="col-span-6 sm:col-span-4">
                    <div class="flex items-center">
                        <label id="img-label" class="
                        inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 
                        rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm 
                        hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 
                        focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 
                        mt-2 mr-2">{{ __('Select an image') }}</label>
                        {{-- <x-label for="img" value="{{ __('Image') }}" /> --}}
                        {{-- <x-input wire:model="img" x-ref="photo" id="img" type="file" class="mt-1 block w-full" wire:model.defer="img" autocomplete="img" style="border-radius: 0"/> --}}
                        <p id="img-filename" class="h-fit" >{{ __('No file selected') }}</p>
                    </div>
                        <x-input-error for="img" class="mt-2" />
                </div>
    
    
                {{-- <button type="submit" class="rounded-lg px-4 py-2 bg-green-700 text-green-100 hover:bg-green-800 duration-300 mt-2">Crear</button> --}}
            </form>
        {{-- </div> --}}
        
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo" id="boton-crear-comunidad">
            {{ __('Save') }}
        </x-button>
        
        {{-- Script para envio del formulario porque livewire es una mierda --}}
        <script>
            // Crear formulario
            const formulario_crear = document.createElement('form');
            formulario_crear.method = 'POST';
            formulario_crear.action = "{{ route('comunidad_crear') }}";
            formulario_crear.enctype = "multipart/form-data";

            // Añadir formulario escondido al documento para poder enviarlo sin errores
            formulario_crear.style.display = 'none';
            document.body.appendChild(formulario_crear)

            // Volver a crear y eliminar el boton
            let botonCrearComunidad = document.getElementById('boton-crear-comunidad');

            
            // Añadir elementos del formulario
            const crear_elNombre = document.getElementById('crear_nombre');
            const crear_nombre = document.createElement('input');
            crear_nombre.type = crear_elNombre.type;
            crear_nombre.name = crear_elNombre.name;
            formulario_crear.appendChild(crear_nombre);
            
            const crear_elCodigo = document.getElementById('crear_codigo');
            const crear_codigo = document.createElement('input');
            crear_codigo.type = crear_elCodigo.type;
            crear_codigo.name = crear_elCodigo.name;
            formulario_crear.appendChild(crear_codigo);
            
            const crear_elCorreo = document.getElementById('crear_correo');
            const crear_correo = document.createElement('input');
            crear_correo.type = crear_elCorreo.type;
            crear_correo.name = crear_elCorreo.name;
            formulario_crear.appendChild(crear_correo);
            
            // Conectar input de imagen con label
            const imgLabel = document.getElementById('img-label');
            const img = document.createElement('input');
            img.type = 'file';
            img.name = 'img';
            img.id = 'img';
            imgLabel.setAttribute('for', img.id);
            console.log(imgLabel.for);
            formulario_crear.appendChild(img);
            
            const crear_elCSRF = document.createElement('input');
            crear_elCSRF.type = 'hidden';
            crear_elCSRF.name = '_token';
            crear_elCSRF.value = "{{ csrf_token() }}";
            formulario_crear.appendChild(crear_elCSRF);
                
            // Enviar formulario al clickar boton
            botonCrearComunidad.addEventListener('click', (e) => {
                e.preventDefault();

                // Actualizar los valores de los inputs
                crear_nombre.value = crear_elNombre.value;
                crear_codigo.value = crear_elCodigo.value;
                crear_correo.value = crear_elCorreo.value;

                formulario_crear.submit();
            });

            // Actualizar texto de nombre de imagen
            const imgFilename = document.getElementById('img-filename');
            img.addEventListener('input', (e) => {
                imgFilename.innerText = img.files[0].name;
            })
        </script>
    </x-slot>
</x-form-section>