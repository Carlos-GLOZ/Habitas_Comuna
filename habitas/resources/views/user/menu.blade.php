<x-app-layout>
    <style>
        main{
            justify-content: center;
            display: flex;
            flex-flow: column;
            align-items:center;
            height: auto !important;
        }
        .divImgDisplay{
            width: 100%;
            text-align: center;
            display: flex;
            padding-bottom: 10px;
            justify-content: center;
        }
        .ImgDisplay{
            width: 100px;
            height: 100px;
            background-position: center;
            background-attachment: contain;
            background-size: cover;
            border-radius: 20%;
        }
        @media (max-width: 640px) {
            .addPaddign{
                height: auto;
                padding-bottom: 101px;
            }
        }

    </style>
    <div>
        <h2 class="sticky top-0 bg-white rounded-lg p-4 border text-center w-fit m-auto">{{__("Choose your community")}}</h2>

        <div id="comunidades_div" style="min-height: calc(100vh - 10rem); width: 100% !important" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 w-full justify-center items-center addPaddign">
            @foreach ($comunidades as $comunidad)
                <form method="GET" class="text-center w-full" action="{{route('establishCommunity', ['id_comunidad' => $comunidad->id])}}">
                    <button type="submit" class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all">
                        <div class="divImgDisplay"><div class="ImgDisplay" style="background-image: url({{asset('storage/uploads/comunidades/img/'.$comunidad->id.'.png')}})"></div></div>
                        {{-- <div class="divImgDisplay"><div class="ImgDisplay"><img src="{{asset('storage/uploads/comunidades/img/'.$comunidad->id.'.png')}}" alt="logo"></div></div> --}}
                        {{$comunidad->nombre}}
                    </button>
                </form>
            @endforeach
        </div>
    </div>

    <script>
        window.onload = changeEstructure
        function changeEstructure(){
            console.log(comunidades_div.children);
            comunidades_div.classList.replace('lg:grid-cols-4','lg:grid-cols-2');
            comunidades_div.classList.add('lg:w-1/2');

            document.getElementById("menuSocialRedirect").remove();
            document.getElementById("menuComunidadRedirect").remove();
            document.getElementById("menuAdminRedirect").remove();
        }
    </script>
</x-app-layout>
