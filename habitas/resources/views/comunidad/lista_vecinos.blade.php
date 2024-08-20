<x-app-layout>

    <div class="w-full flex justify-center flex-col">
        {{-- Listado de vecinos --}}
        <div class="p-6 grid grid-cols-1 gap-4 w-full max-w-3xl">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Members') }}</h3>
            </div>

            @foreach($comunidad->miembros as $vecino)
                <div style="width: 100%" class="py-8 px-8 mx-auto bg-white rounded-xl shadow-lg space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6 gap-6">
                    @if ($vecino->visible || $vecino->id == Auth::user()->id)
                        <img class="block mx-auto h-24 w-24 object-cover rounded-full sm:mx-0 sm:shrink-0" src="{{$vecino->profile_photo_url}}" alt="Profile">
                    @else
                        <img class="block mx-auto h-24 w-24 rounded-full sm:mx-0 sm:shrink-0" src="https:\/\/ui-avatars.com\/api\/?name={{ __('Anonymous Neighbor') }}&color=7F9CF5&background=EBF4FF" alt="Profile">
                    @endif

                    <div style="margin: 0" class="text-center space-y-2 sm:text-left">
                        <div class="space-y-0.5">
                            {{-- Nombre del usuario --}}
                            <p class="text-lg text-black font-semibold">
                                @if ($vecino->visible || $vecino->id == Auth::user()->id)
                                    {{$vecino->name}} {{$vecino->apellidos}}
                                @else
                                    @can('presidente', $comunidad)
                                        {{$vecino->name}} {{$vecino->apellidos}}
                                    @else
                                        {{ __('Anonymous Neighbor') }}
                                    @endcan
                                @endif

                            </p>

                            {{-- "Tú" si es tu usuario --}}
                            @if ($vecino->id == Auth::user()->id)
                                @if (Auth::user()->visible)
                                    <p class="text-gray-400">{{ __('You') }} - {{ __('Visible to other neighbors')}}</p>
                                @else
                                    <p class="text-gray-400">{{ __('You') }} - {{ __('Invisible to other neighbors')}}</p>
                                @endif
                            @endif

                            {{-- Botones de acciones para el presidente --}}
                            <div class="text-slate-500 font-medium truncate">
                                @can('presidente', $comunidad)
                                    @if ($vecino->id != $comunidad->presidente_id)
                                        <div class="flex gap-3 justify-center flex-wrap">
                                            <form action="{{ route('vecinos_blacklist') }}" class="form-blacklist-comunidad" method="post">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $vecino->id }}">
                                                <button class="boton-blacklist-comunidad rounded-lg px-4 py-2 border border-gray-300 text-black hover:bg-gray-100 duration-300">{{ __('Blacklist from Community') }}</button>
                                            </form>
                                            <form action="{{ route('vecinos_echar') }}" class="form-echar-comunidad" method="post">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $vecino->id }}">
                                                <button class="boton-echar-comunidad rounded-lg px-4 py-2 border border-gray-300 text-black hover:bg-gray-100 duration-300">{{ __('Kick Out from Community') }}</button>
                                            </form>
                                        </div>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        
        {{-- Usuarios blacklisteados --}}
        @can('presidente', $comunidad)
            @if (count($comunidad->blacklist) > 0)

                <x-section-border />

                <div class="p-6 grid grid-cols-1 gap-4 w-full max-w-3xl">

                    <div class="p-6 grid grid-cols-1 gap-4 w-full max-w-3xl">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Blacklisted Users') }}</h3>
                        </div>
                    </div>

                    @foreach($comunidad->blacklist as $vecino)
                        <div style="width: 100%" class="py-8 px-8 mx-auto bg-white rounded-xl shadow-lg space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6 gap-6">
                            @if ($vecino->visible)
                                <img class="block mx-auto h-24 w-24 object-cover rounded-full sm:mx-0 sm:shrink-0" src="{{$vecino->profile_photo_url}}" alt="Profile">
                            @else
                                <img class="block mx-auto h-24 w-24 rounded-full sm:mx-0 sm:shrink-0" src="https:\/\/ui-avatars.com\/api\/?name={{ __('Anonymous Neighbor') }}&color=7F9CF5&background=EBF4FF" alt="Profile">
                            @endif

                            <div style="margin: 0" class="text-center space-y-2 sm:text-left">
                                <div class="space-y-0.5">
                                    {{-- Nombre del usuario --}}
                                    <p class="text-lg text-black font-semibold">
                                        @if ($vecino->visible)
                                            {{$vecino->name}} {{$vecino->apellidos}}
                                        @else
                                            @can('presidente', $comunidad)
                                                {{$vecino->name}} {{$vecino->apellidos}}
                                            @else
                                                {{ __('Anonymous Neighbor') }}
                                            @endcan
                                        @endif

                                    </p>

                                    {{-- Botones de acciones para el presidente --}}
                                    <div class="text-slate-500 font-medium truncate">
                                        @can('presidente', $comunidad)
                                            @if ($vecino->id != $comunidad->presidente_id)
                                                <div class="flex gap-3 justify-center flex-wrap">
                                                    <form action="{{ route('vecinos_unblacklist') }}" class="form-unblacklist-comunidad" method="post">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $vecino->id }}">
                                                        <button class="boton-unblacklist-comunidad rounded-lg px-4 py-2 border border-gray-300 text-black hover:bg-gray-100 duration-300">{{ __('Unblacklist from Community') }}</button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endcan
    </div>
        
    {{-- Script control acciones comunidad --}}
    @vite('resources/js/listaVecinos.js')

</x-app-layout>