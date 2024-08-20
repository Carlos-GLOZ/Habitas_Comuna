
<div class="w-full px-5 overflow-x-auto flex flex-col gap-6">
    {{-- Nothing in the world is as soft and yielding as water. --}}

    @if($modal)
        @include('livewire.crear')
    @else
        <div>
            <button wire:click="crear()" class="px-4 py-2 font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Crear</button>
        </div>

    @endif
    <table class="text-sm text-left text-gray-500 dark:text-gray-400 w-full pt-9">
        <thead class="text-xs text-gray-800 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3 text-center">{{ __('Dates')}} </th>
                <th scope="col" class="px-6 py-3 text-center">{{ __('Type')}}  </th>
                <th scope="col" class="px-6 py-3 text-center">{{ __('Spent')}} </th>
                <th scope="col" class="px-6 py-3 text-center">{{ __('Description')}} </th>
                <th scope="col" class="px-6 py-3 text-center">{{ __('Action')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gastos as $gasto)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td scope="col" class="px-6 py-3 text-center">{{ $gasto->created_at }}</td>
                    <td scope="col" class="px-6 py-3 text-center">{{ $gasto->tipo }}</td>
                    <td scope="col" class="px-6 py-3 text-center">{{ $gasto->gasto }}</td>
                    <td scope="col" class="px-6 py-3 text-center">{{ $gasto->descripcion }}</td>

                    <td scope="col" class="px-6 py-3 text-center">
                        <button wire:click="editar({{ $gasto->id }})" class="px-4 py-2 font-medium text-white bg-yellow-500 rounded-md hover:bg-yellow-600"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button wire:click="eliminar({{ $gasto->id }})" class="px-4 py-2 font-medium text-white bg-red-500 rounded-md hover:bg-red-600"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
