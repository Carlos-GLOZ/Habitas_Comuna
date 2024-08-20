<x-app-layout>
    <style>
        .icon_img{
            width: 20px;
        }
        .margin-responsive-button{
            margin-bottom: 5px; 
        }
        main{
            display: flex;
            justify-content: center
        }
        thead{
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .scrolleable{
            /* height: 100%; */
            overflow: auto;
            overflow-x: hidden; 
            overflow-y: auto;
            position: relative;
        }

        .showResp{
            display: none;
        }

        @media screen and (max-width: 815px){
            .notShowResp{
                display: none;
            }
            .showResp{
                display: block;
            }
        }
        @media (max-width: 640px) {
            .scrolleable{
                height: auto;
                padding-bottom: 101px;
            }
        }
        @media screen and (max-width: 394px){
            .scrolleable{
                overflow-x: scroll; 
            }
        }
    </style>
    <div class="scrolleable m-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-900 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">{{__("Name")}}</th>
                    <th scope="col" class="px-6 py-3 text-center">{{__("Actions")}}</th>
                </tr>
            </thead>
            <tbody>
        @foreach ($archivos as $adjunto)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center flex-wrap">
                    @if($adjunto->extension == "pdf")
                    <img class="icon_img" src="../images/icons/pdf.png" alt="pdf">
                    @endif
                    @if($adjunto->extension == "xlsx")
                    <img class="icon_img" src="../images/icons/excel.png" alt="excel">
                    @endif
                    @if($adjunto->extension == "docx")
                    <img class="icon_img" src="../images/icons/word.png" alt="docx">
                    @endif
                    @if($adjunto->extension == "pptx")
                    <img class="icon_img" src="../images/icons/pwp.png" alt="pptx">
                    @endif
                    @if(explode("/", $adjunto->tipo)[0] == "image")
                    <img class="icon_img" src="../images/icons/image.webp" alt="image">
                    @endif
                    @if(explode("/", $adjunto->tipo)[0] == "video")
                    <img class="icon_img" src="../images/icons/video.webp" alt="video">
                    @endif
                    &nbsp;
                    {{$adjunto->nombre}}
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($adjunto->extension == "pdf" || explode("/", $adjunto->tipo)[0] == "image" || explode("/", $adjunto->tipo)[0] == "video")
                        <a type="button" class="ml-2 px-5 py-2 bg-cyan-500 rounded transition-all duration-200 hover:bg-cyan-700 margin-responsive-button" href="{{asset("adjuntos/seeArchiveInNavigator/$adjunto->id"."_"."$adjunto->comunidad_id".".$adjunto->extension/$adjunto->id")}}" target="_blank"><i class="fa-solid fa-eye" style="color: #ffffff;"></i></a>
                    @endif
                    <a type="button" class="ml-2 px-5 py-2 bg-cyan-500 rounded transition-all duration-200 hover:bg-cyan-700" href="{{asset("adjuntos/descargarArchivoPrivado/$adjunto->id"."_"."$adjunto->comunidad_id".".$adjunto->extension")}}"><i class="fa-solid fa-download" style="color: #ffffff;"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="h-[40vh] bg-white m-auto items-center justify-center hidden no-files">
        <p class="m-auto w-4/5 block text-center">
            {{__("It seems like there's no archive available, be careful.")}}
            <br><br>
            <i class="fa-solid fa-file-xmark text-5xl"></i>
        </p>
        {{-- It seems like there's no archive available, be careful. --}}
    </div>
    </div>
    <script>
        if (document.querySelector('tbody').children.length == 0){
            document.querySelector('.no-files').classList.add('flex');
            document.querySelector('.no-files').classList.remove('hidden');
        }
    </script>
</x-app-layout>
