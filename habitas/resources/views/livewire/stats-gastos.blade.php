<div class="w-full px-5 overflow-x-auto">

    <table class="text-sm text-left text-gray-500 dark:text-gray-400 w-full">
        <thead class="text-xs text-gray-800 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3 text-center">
                    <input type="number"  min="1900" max="2099" value="{{date('Y')}}" wire:model='year'>
                </th>
                <th scope="col" class="px-6 py-3 text-center">

                    <select wire:model='tipo'>
                        <option value="all">{{ __('All')}}</option>
                        <option value="luz">{{ __('Electricity')}} </option>
                        <option value="gas">  {{ __('Gas')}}</option>
                        <option value="agua"> {{ __('Water')}}</option>
                        <option value="mantenimiento">{{ __('Maintenance')}}</option>
                        <option value="otros">{{ __('Others')}}</option>
                    </select>
                </th>
                <th scope="col" class="px-6 py-3 text-center"></th>
                <th scope="col" class="px-6 py-3 text-center"></th>
                <th scope="col" class="px-6 py-3 text-center"></th>
            </tr>
            <tr>
                <th scope="col" class="px-6 py-3 text-center"> {{ __('Dates')}}</th>
                <th scope="col" class="px-6 py-3 text-center"> {{ __('Type')}} </th>
                <th scope="col" class="px-6 py-3 text-center"> {{ __('Spent')}} €</th>
                <th scope="col" class="px-6 py-3 text-center"> {{ __('Consumption')}}</th>
                <th scope="col" class="px-6 py-3 text-center"> {{ __('Description')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gastos as $gasto)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 text-center ">{{ $gasto->created_at }}</td>
                    <td class="px-6 py-4 text-center ">{{ $gasto->tipo }}</td>
                    <td class="px-6 py-4 text-center ">{{ $gasto->gasto }}</td>
                    <td class="px-6 py-4 text-center ">{{ $gasto->cantidad }}</td>
                    <td class="px-6 py-4 text-center">{{ $gasto->descripcion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


</div>
