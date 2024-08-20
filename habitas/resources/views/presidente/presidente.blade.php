<x-app-layout>



    <script src="{{asset('/js/Winwheel.js')}}"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>

    <div>
        <div class="pb-4">
            {{-- Encabezado presidencia --}}
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="Votación-tab" data-tabs-target="#Votación" type="button" role="tab" aria-controls="Votación" aria-selected="false">Votación</button>
                    </li>
        
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="Orden-tab" data-tabs-target="#Orden" type="button" role="tab" aria-controls="Orden" aria-selected="false">Orden</button>
                    </li>
        
                    <li role="presentation">
                        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="Historial-tab" data-tabs-target="#Historial" type="button" role="tab" aria-controls="Historial" aria-selected="false">Historial</button>
                    </li>
                </ul>
            </div>

            {{-- Contenido presidencia --}}
            <div id="myTabContent">
        
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800 flex flex-col gap-12" id="Votación" role="tabpanel" aria-labelledby="Votación-tab">
                    <form action="{{route('presidente_votos')}}" method="POST" class="flex flex-col gap-3">
                        <p class="w-full text-center text-xl">Crear Votación de presidencia</p>
                        @csrf
                        <div>
                            <label for="incluir">Incluirme: </label>
                            <input type="checkbox" name="incluir" id="incluir"/>
                        </div>
        
                        <button type="submit" class="bg-[#002D74] rounded-xl px-2 text-white py-2 hover:scale-105 duration-300" >Crea</button>
                    </form>
        
        
                    <form action="{{route('presidente_cambios')}}" method="POST" class="flex flex-col gap-3">
                        @csrf
                        <p class="w-full text-center text-xl">Cambio de presidencia</p>
                        <div>
        
                            <label for="">Presidente: </label>
                            <select name="newPresi">
        
                                @foreach ($vecinos as $vecino)
                                    @if($comunidad->presidente_id == $vecino->id)
                                        <option value="{{$vecino->id}}" selected>{{$vecino->name}} {{$vecino->apellidos}}</option>
                                    @else
                                        <option value="{{$vecino->id}}">{{$vecino->name}} {{$vecino->apellidos}}</option>
                                    @endif
        
                                @endforeach
        
        
                            </select>
                        </div>
                        <div>
                            <br>
                            <label for="">Vicepresidente: </label>
                            <select name="newVicePresi">
                                <option value="NULL">Blanco</option>
                                @foreach ($vecinos as $vecino)
                                    @if($comunidad->vicepresidente_id == $vecino->id)
                                        <option value="{{$vecino->id}}" selected>{{$vecino->name}} {{$vecino->apellidos}}</option>
                                    @else
                                        <option value="{{$vecino->id}}">{{$vecino->name}} {{$vecino->apellidos}}</option>
                                    @endif
        
                                @endforeach
                            </select>
                        </div>
        
                        <button type="submit" class="bg-[#002D74] rounded-xl px-2 text-white py-2 hover:scale-105 duration-300" >Cambiar Presidente y Vicepresidente</button>
                    </form>
        
                </div>
        
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800 flex flex-col gap-3" id="Orden" role="tabpanel" aria-labelledby="Orden-tab">
                    <p>Proximo Presidente: {{$orden[0]->name}} {{$orden[0]->apellidos}}</p>
        
                    @if(count($orden)>1)
                    <p>Proximo vicepresidente: {{$orden[1]->name}} {{$orden[1]->apellidos}}</p>
                    @endif
                    <form action="{{route('presidente_cambios')}}" method="POST">
                        @csrf
                        <input type="hidden" name="newPresi" value="{{$orden[0]->id}}" />
                        @if(count($orden)>1)
                        <input type="hidden" name="newVicePresi" value="{{$orden[1]->id}}" />
                        @endif
                        <button type="submit" class="bg-[#002D74] rounded-xl px-2 text-white py-2 hover:scale-105 duration-300" >Confirmar Cambios</button>
                    </form>
                </div>
        
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800 overflow-auto h-fit" id="Historial" style="min-height: calc(100vh - 11rem)" role="tabpanel" aria-labelledby="Historial-tab">
        
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center" >Nombre</th>
                                <th scope="col" class="px-6 py-3 text-center" >Fecha Inicio</th>
                                <th scope="col" class="px-6 py-3 text-center" >Fecha Final</th>
                                <th scope="col" class="px-6 py-3 text-center" >Tiempo total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($presidentesH as $presidente)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 text-center ">{{$presidente->usuario->name}} {{$presidente->usuario->apellidos}}</td>
                                    <td class="px-6 py-4 text-center ">{{$presidente->fecha_ini}}</td>
                                    <td class="px-6 py-4 text-center ">{{$presidente->fecha_fin}}</td>
                                    <td class="px-6 py-4 text-center ">{{\Carbon\Carbon::parse($presidente->fecha_fin)->diffInDays(\Carbon\Carbon::parse($presidente->fecha_ini))}} Dias</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
        
                </div>
        
            </div>
        </div>
    </div>


</x-app-layout>
