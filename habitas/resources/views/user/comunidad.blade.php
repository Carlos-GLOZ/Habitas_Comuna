<x-app-layout>
    
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-4 lg:px-8">
            {{-- Unirse a una una comunidad --}}
            @livewire('user.unirse-comunidad')

            <x-section-border />

            {{-- Crear una comunidad --}}
            <div class="mt-10 sm:mt-0">
                @livewire('user.crear-comunidad')
            </div>
    
            <x-section-border />

            {{-- Tabla de comunidades --}}
            <div class="mt-10 sm:mt-0">
                @livewire('user.tabla-comunidades', ['comunidades' => $comunidades])
            </div>
        </div>
    </div>

</x-app-layout>
