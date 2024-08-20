<x-app-layout>

    @vite(['resources/js/Anuncio.js','resources/css/anuncio.css'])
    
    @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)
        <div class="flex w-full sticky top-0">
            <h1 class="header-btn-anuncio anuncioC">{{__("Announcements")}} <br> <i class="fa-regular fa-circle-plus"></i></h1>
        </div>
    @else
        <div class="flex w-full sticky top-0">
            <h1 class="header-anuncio">{{__("Announcements")}}</h1>
        </div>

    @endif

    <style>
        main {
            height: initial !important;
        }
    </style>

    <div class="overflow-auto border-gray-400 px-6 -mt-3 pb-[101px] sm:pb-0">
        <!-- Grid de los anuncios -->
        <div class="contenedor-anuncios">
            @foreach($anuncios as $anuncio)
            <div style="height: 33vh" class="div_anuncio">
                <div class="h-full">
                    <div class="px-6 py-4 h-4/6 overflow-hidden w-full">
                        <h2 class="text-xl text-gray-700 mb-2 truncate" title="{{$anuncio['nombre']}}">{{$anuncio['nombre']}}</h2>

                        <div class="flex">
                            <pre class="text-gray-700 whitespace-pre-line text-base font-thin text-justify line-clamp-5 grow">{{$anuncio['descripcion']}}</pre>
                            
                            <div class="w-0 h-full img_con overflow-hidden" style="max-height: 52vh;">
                                <img class="imgn w-full h-full imgA" src="{{asset('storage/uploads/anuncio/'.$anuncio->id.'.png')}}">
                            </div>
                        </div>
                    </div>

                    <div class="botones">

                        @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)
                            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_elim" data-anunId="{{$anuncio->id}}"><i class="fa-solid fa-trash"></i></button>

                            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_edit" data-anunId="{{$anuncio->id}}"><i class="fa-solid fa-edit pointer-events-none"></i></button>
                        @endif
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full boton_ver"><i class="fa-solid fa-eye pointer-events-none"></i></button>    

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div id="modalAnuncio" class="hidden fixed z-50 left-0 top-0 w-full h-full overflow-auto bg-black bg-opacity-40">
        <!-- Modal content -->
        <div class="relative bg-white m-auto p-0 w-4/5 rounded overflow-hidden" style="align-items: center; width:500px;">
            <div class=" py-0.5 px-4 bg-sky-800 text-white w-full h-10 mb-2.5">
                <span class="text-white float-right text-2xl mt-0.5 font-bold hover:text-red-600 hover:no-underline hover:cursor-pointer x-cerrar">&times;</span>
                <h2 class=" mr-32 mt-2">{{__("ANNOUNCEMENT")}}</h2>
            </div>
            <div class="px-5 py-4">
                <!-- <div id="form"> -->
                <form id="form" action="" method="post">
                    <div class="form-outline mb-4" style="display: flex; flex-direction:column-reverse;">
                        <input type="text" class="form-control form-control-lg" name="Titulo"/>
                        <label class="form-label mb-1" >{{__("Title")}}</label>
                        @error('Titulo')
                            <span class="invalid-feedback">*{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="form-outline mb-4" style="display: flex; flex-direction:column-reverse;">
                        
                        <textarea class="form-control form-control-lg" name="Informacion"></textarea>

                        <label class="form-label mb-1" >{{__("Information")}}</label>
                        @error('Informacion')
                            <span class="invalid-feedback">*{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-outline mb-4 flex flex-col">
                       
                        <label  class="form-label mb-1">{{__("Image")}} (.png {{__("or")}} .jpg)</label>
                        <input type="file" class="form-control" style="display:none" name="imagen" id="Cimagen"/>
                        <label for="Cimagen" class="px-4 py-2 rounded transition-all bg-sky-500 w-fit text-white cursor-pointer hover:bg-sky-700">{{__("Attach image")}}</label>

    
                    </div>
                    <button class="btn-modal-anuncio" style="height: 40px">{{__("Send")}}</button>  
                </form>
                <!-- </div> -->
            </div>
        </div>
    </div>
    

    <style>
        .botones{
            display: flex;
            justify-content: space-around;
            padding: 10px 0 20px 0;
        }
    </style>
</x-app-layout>