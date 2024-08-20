<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <div class="py-2 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-sm bg-white h-full">

                <div class="pb-3.5 h-full">

                    <div class="w-full flex lg:flex-row flex-wrap h-full overflow-auto pb-2 scroll-bar">

                        {{-- Imagen de la comunidad --}}
                        <div class="lg:w-1/3 md:w-[45%] order-1 sm:order-none m-3 flex flex-col justify-center items-center lg:h-[40%] overflow-hidden">
                            <img src="{{asset('/storage/uploads/comunidades/img/'.Session::get('comunidad').'.png')}}" class="rounded-md w-full h-full object-cover" onerror="this.src='{{asset('/images/logo.png')}}'"/>
                        </div>

                        {{-- Info comunidad --}}
                        <div class="grow p-2 flex md:w-[45%] lg:grow order-2 sm:order-none shadow m-3 rounded-md flex-wrap justify-around lg:h-[40%] overflow-hidden">

                            <div class="flex flex-row items-center justify-around gap-4 p-3 py-4 pb-2 w-full">
                                <h2 class="text-2xl title-com">{{$comunidad->nombre}}</h2>
                            </div>

                            <div class="rounded-sm w-cover w-full sm:w-[48%] items-center bg-white justify-between">
                                <h2 class="p-1 m-3 text-center font-bold text-xl">{{__("President")}}</h2>
                                <p class="p-1 m-3 text-center">{{$comunidad->presidente->name}} {{$comunidad->presidente->apellidos}}</p>
                            </div>
                            <div class="rounded-sm w-cover w-full sm:w-[48%] items-center bg-white justify-between">
                                <h2 class="p-1 m-3 text-center font-bold text-xl">{{__("Neighbors")}}</h2>
                                <p class="p-1 m-3 text-center">{{count($comunidad->miembros)}}</p>
                            </div>




                        </div>

                        {{-- Encuestas --}}
                        @if (count($encuestas)>0)

                            <div class="rounded-sm w-cover flex flex-col grow md:w-[45%] lg:w-[45%] items-center bg-slate-100 justify-center gap-4 p-3 md:h-[55%] lg:h-[55%] sm:order-none order-3 overflow-auto">
                                <p class="p-1">{{__("Polls")}}:</p>
                                @foreach ($encuestas as $encuesta)
                                    <div class="bg-white rounded-lg shadow-lg flex flex-row w-full">

                                        <div class="flex flex-col w-full m-2">
                                            <p class="p-3 flex flex-row justify-between items-center">{{$encuesta->nombre}}: <a class="px-4 py-2 font-medium text-white bg-green-500 rounded-md hover:bg-green-600" href="{{url('/votacion/votar/'.$encuesta->id)}}" ><i class="fa-solid fa-person-booth"></i></a></p>
                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @endif

                        {{-- Anuncios --}}
                        @if (count($anuncios)>0)

                            <div class="rounded-lg shadow flex flex-col max-h-[492px] sm:max-h-full md:w-[45%] lg:w-[45%] bg-white gap-4 p-3 mx-3 md:h-auto lg:h-[55%] sm:order-none order-4 grow">
                                <p class="text-center font-bold text-xl">{{__("Announcements")}}</p>
                                <div class="overflow-auto px-2 scroll-bar">
                                    @foreach ($anuncios as $anuncio)
                                    <div class="bg-white rounded-lg shadow flex flex-row w-full my-2 p-2">

                                        @if(file_exists(public_path('storage/uploads/anuncio/'.$anuncio->id.'.png')))
                                        <div class="flex flex-col w-2/3 m-2">
                                            <p class="underline decoration-blue-400 text-center capitalize">{{$anuncio->nombre}}</p>
                                            <pre class=" whitespace-pre-line"><i class="fa-solid fa-arrow-right capitalize"></i> {{$anuncio->descripcion}}</pre>
                                        </div>
                                        <div class="w-1/3">
                                            <img src="{{ asset('storage/uploads/anuncio/'.$anuncio->id.'.png') }}"
                                            alt="Imagen" class="rounded-r-md" onerror="this.src='{{asset('/images/logo.png')}}'">
                                        </div>
                                        @else
                                        <div class="flex flex-col w-full m-2">
                                            <p class="underline decoration-blue-400 text-center capitalize">{{$anuncio->nombre}}</p>
                                            <pre class=" whitespace-pre-line"><i class="fa-solid fa-arrow-right capitalize"></i> {{$anuncio->descripcion}}</pre>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                                </div>

                            </div>

                        @endif
                    </div>

                </div>

                {{-- <x-welcome /> --}}
            </div>
        </div>
    </div>
</x-app-layout>
