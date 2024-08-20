<x-form-section submit="crearComunidad">
    <x-slot name="title">
        {{ __('Join Community') }}
    </x-slot>

    <x-slot name="description">
        {{ __("Join a community using its secret code. If you don't know it, ask the president of the community you want to join for it.") }}
    </x-slot>

    <x-slot name="form">
        <form id="formulario-unirse-comunidad" method="POST" action="{{ route('unirseComunidad') }}" class="flex flex-col pt-4 m-auto px-6 h-full justify-between">
            @csrf

            <!-- Codigo -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="unirse_codigo" value="{{ __('Code') }}" />
                <x-input id="unirse_codigo" name="unirse_codigo" type="text" class="mt-1 block w-full" wire:model.defer="codigo" autocomplete="codigo" />
                <x-input-error for="unirse_codigo" class="mt-2" />
                <x-input-error for="blacklist" class="mt-2" />
            </div>    

        </form>
        
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo" id="boton-unirse-comunidad">
            {{ __('Join') }}
        </x-button>
        
        {{-- Script para envio del formulario porque livewire es una mierda --}}
        <script>
            // Crear formulario
            const formulario_unirse = document.createElement('form');
            formulario_unirse.method = 'POST';
            formulario_unirse.action = "{{ route('unirseComunidad') }}";

            // Añadir formulario escondido al documento para poder enviarlo sin errores
            formulario_unirse.style.display = 'none';
            document.body.appendChild(formulario_unirse)

            // Volver a crear y eliminar el boton
            let botonUnirseComunidad = document.getElementById('boton-unirse-comunidad');
            
            // Añadir elementos del formulario            
            const unirse_elCodigo = document.getElementById('unirse_codigo')
            const unirse_codigo = document.createElement('input');
            unirse_codigo.type = unirse_elCodigo.type;
            unirse_codigo.name = unirse_elCodigo.name;
            formulario_unirse.appendChild(unirse_codigo);
            
            const unirse_elCSRF = document.createElement('input');
            unirse_elCSRF.type = 'hidden';
            unirse_elCSRF.name = '_token';
            unirse_elCSRF.value = "{{ csrf_token() }}";
            formulario_unirse.appendChild(unirse_elCSRF);
                
            // Enviar formulario al clickar boton
            botonUnirseComunidad.addEventListener('click', (e) => {
                e.preventDefault();

                // Actualizar los valores de los inputs
                unirse_codigo.value = unirse_elCodigo.value;

                formulario_unirse.submit();
            });
        </script>
    </x-slot>
</x-form-section>