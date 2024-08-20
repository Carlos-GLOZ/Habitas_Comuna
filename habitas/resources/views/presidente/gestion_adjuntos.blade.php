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
        caption{
            position: sticky;
            top: 0;
            z-index: 10;
        }
        thead{
            position: sticky;
            top: 61.5px;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <div class="scrolleable m-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <caption class="w-full bg-gray-50 border-b-2">
                <div class="px-6 py-3 text-right flex items-center">
                    <h3 class="uppercase text-black inline-block font-bold text-xl text-center grow">{{ __("Attachments")}}</h3>
                    <button onclick="generateInsertModal()" class="ml-2 px-5 py-2 bg-cyan-500 rounded text-white transition-all duration-200 hover:bg-cyan-700">{{ __("Create")}}</button>
                </div>
            </caption>
            <thead class="text-xs text-gray-900 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">{{ __("Name")}}</th>
                    <th scope="col" class="px-6 py-3 text-center">{{ __("Files")}}</th>
                    <th scope="col" class="px-6 py-3 text-center">{{ __("Actions")}}</th>
                </tr>
            </thead>
            <tbody id="tbladjuntos"> 
            @foreach ($archivos as $adjunto)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 text-center"><div class="flex justify-center flex-wrap">
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
                    {{$adjunto->nombre}}</div></td>
                    <td class="px-6 py-4 text-center" >
                    @if($adjunto->extension == "pdf" || explode("/", $adjunto->tipo)[0] == "image" || explode("/", $adjunto->tipo)[0] == "video")
                        <a type="button" class="ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500 margin-responsive-button" href="{{asset("adjuntos/seeArchiveInNavigator/$adjunto->id"."_"."$adjunto->comunidad_id".".$adjunto->extension/$adjunto->id")}}" target="_blank"><i class="fa-solid fa-eye" style="color: #000000;"></i></a>
                    @endif
                        <a type="button" class="ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500" href="{{asset("adjuntos/descargarArchivoPrivado/$adjunto->id"."_"."$adjunto->comunidad_id".".$adjunto->extension")}}" ><i class="fa-solid fa-download" style="color: #000000;"></i></a>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button id="btn_editar" class="ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500 margin-responsive-button" onclick="modificaradjunto({{$adjunto->id}},'{{'/'.$adjunto->id.'_'.$adjunto->comunidad_id.'.'.$adjunto->extension}}',this)"><i class="fa-solid fa-pen-to-square" style="color: #000000;"></i></button>
                        <button id="btn_eliminar" class="ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500" onclick="eliminaradjunto({{$adjunto->id}},'{{'/'.$adjunto->id.'_'.$adjunto->comunidad_id.'.'.$adjunto->extension}}')"><i class="fa-solid fa-trash" style="color: #000000;"></i></button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        
        <div class="h-[40vh] bg-white m-auto items-center justify-center hidden ext">
            <p class="m-auto w-4/5 block text-center">
                {{__("It seems like there's no archive available, be careful.")}}
                <br><br>
                <i class="fa-solid fa-file-xmark text-5xl"></i>
            </p>
            {{-- It seems like there's no archive available, be careful. --}}
        </div>

        <script>

            function generateInsertModal(){
                Swal.fire({
                    html:`
                    <form onsubmit="createNewAdjunto()" enctype="multipart/form-data">
                    <h4 class="uppercase text-black inline-block font-bold text-xl text-center grow">{{__("New attachment")}}</h4>
                    <br>
                    <br>
                    <label>{{__("Name")}}</label>
                    <br>
                    <input class="border-2 border-solid rounded border-gray-400 p-2 my-3" id="insertFormName" name="nombre" type"text" placeholder="{{ __('Write the name of the attachment')}}">
                    <br>
                    <label>{{ __('Files')}}</label>
                    <br>
                    <input  class="my-3 mb-6" id="insertFormFile" name="archivo" type="file">
                    <br>
                    <input id="btn_crear" class="ml-2 px-5 py-2 bg-cyan-500 rounded text-white transition-all duration-200 hover:bg-cyan-700" type="submit" value="{{ __('Send')}}">
                    </form>
                    `,
                    showConfirmButton: false
                })
                document.getElementById("insertFormName").blur();
            }

            const createNewAdjunto = async()=>{
                document.getElementById('btn_crear').disabled = true;

                event.preventDefault();
                form = new FormData(event.srcElement);
                let resul = await axios.post(URL_API+'/adjuntos/addNewAdjunto', form, {headers: { 'Content-Type': 'multipart/form-data' }});

                document.getElementById('btn_crear').disabled = false;

                if(resul.data == "OK"){
                    alertaDefecto("success",'{{__("Succesful result")}}','{{__("Attachment created")}}',true);
                    
                    document.querySelector('.ext').classList.remove('flex');
                    document.querySelector('.ext').classList.add('hidden');
                }else if(resul.data == "TypeError"){
                    alertaDefecto("error",'{{__("Error")}}','{{__("Extension not allowed")}}',false);
                }else{
                    alertaDefecto("error",'{{__("Error")}}',`{{__("Attachment not created")}}`,false);
                }

            }

            const eliminaradjunto = async(id,archivo)=>{

                form = new FormData();
                form.append("id",id);
                form.append("archivo",archivo);
                let resul = await axios.post(URL_API+'/adjuntos/deleteAdjunto', form);


                if(resul){
                    alertaDefecto("success",'{{__("Succesful result")}}','{{__("Attachment deleted")}}',true);
                }else{
                    alertaDefecto("error",'{{__("Error")}}',`{{__("Attachment not deleted")}}`,false);
                }
            }

            const  modificaradjunto = async(id,archivoOld,btn) => {
                btn.disabled = true;
                let resul = await axios.post(URL_API+'/adjuntos/getDataAdjunto/'+id);
                btn.disabled = false;
                makeModelMod(resul.data,archivoOld);
            }
            const makeModelMod = async(data,archivo) => {

                Swal.fire({
                    html:`
                    <form onsubmit="modAdjunto(${data.id})" enctype="multipart/form-data">
                    <input type="hidden" value="${archivo}" name="archivoOld">
                    <h4 class="uppercase text-black inline-block font-bold text-xl text-center grow">{{ __("Modify attachament")}}</h4>
                    <br>
                    <br>
                    <label>{{__("Name")}}</label>
                    <br>
                    <input class="border-2 border-solid rounded border-gray-400 p-2 my-3" id="modFormName" type"text" name="nombre" placeholder="{{ __('Write the name of the attachment')}}" value="${data.nombre}">
                    <br>
                    <label>{{__("File")}}</label>
                    <br>
                    <input class="my-3 mb-6" id="insertFormFile" name="archivo" type="file">
                    <br>
                    <input id="btn_editar_modal" class="ml-2 px-5 py-2 bg-cyan-500 rounded text-white transition-all duration-200 hover:bg-cyan-700" type="submit" value="{{__("Send")}}">
                    </form>
                    `,
                    showConfirmButton: false

                })
                document.getElementById("modFormName").blur();
            }

            const modAdjunto = async(id) =>{
                document.getElementById('btn_editar_modal').disabled = true;

                event.preventDefault();
                form = new FormData(event.srcElement);
                form.append("id",id)
                let resul = await axios.post(URL_API+'/adjuntos/updateDataAdjunto', form);
                
                document.getElementById('btn_editar_modal').disabled = false;
                
                if(resul){
                    alertaDefecto("success",'{{__("Succesful result")}}','{{__("Attachment modified")}}',true);
                }else{
                    alertaDefecto("error",'{{__("Error")}}','{{__("Attachment not modified")}}',false);
                }
            }

            function alertaDefecto(icon, title, text, recharge){
                Swal.fire({
                    position: 'center',
                    icon: icon,
                    title: title,
                    text : text,
                    timer:1500,
                    showConfirmButton: false,
                })
                if(recharge){
                    getData();
                }
            }

            const getData = async() => {
                let resul = await axios.post(URL_API+'/adjuntos/getShowDataAdjunto');
                var adjuntos = resul.data;
                document.getElementById("tbladjuntos").innerHTML = adjuntos;

                if (document.querySelector('#tbladjuntos').children.length == 0){
                    document.querySelector('.ext').classList.add('flex');
                    document.querySelector('.ext').classList.remove('hidden');
                }
            }

            if (document.querySelector('tbody').children.length == 0){
                document.querySelector('.ext').classList.add('flex');
                document.querySelector('.ext').classList.remove('hidden');
            }
        </script>
</x-app-layout>
