<form class="space-y-4 py-9 bg-slate-50 shadow-xl rounded-lg">
    <div class=" m-3 flex flex-col gap-3">
        <div>
            <label class="block font-medium">{{ __('Expense type')}}:</label>
            <select wire:model="tipo" name="tipo" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="luz">{{ __('Electricity')}} </option>
                <option value="gas">  {{ __('Gas')}}</option>
                <option value="agua"> {{ __('Water')}}</option>
                <option value="mantenimiento">{{ __('Maintenance')}}</option>
                <option value="otros">{{ __('Others')}}</option>
            </select>
        </div>

        <div>
            <label class="block font-medium">{{ __('Cost')}}:</label>
            <input wire:model="gasto" type="number" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block font-medium">{{ __('Consumption')}}:</label>
            <input wire:model="cantidad" type="number" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block font-medium">{{ __('Description')}}:</label>
            <input wire:model="descripcion" type="text" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
        </div>

        <div>
            <button type="button" wire:click.prevent="guardar()" class="px-4 py-2 font-medium text-white bg-indigo-500 rounded-md hover:bg-indigo-600">{{ __('Save')}}</button>
        </div>

        <div>
            <button type="button" wire:click="cerrarModal()" class="px-4 py-2 font-medium text-white bg-red-500 rounded-md hover:bg-red-600">{{ __('Close')}}</button>
        </div>

    </div>

</form>
