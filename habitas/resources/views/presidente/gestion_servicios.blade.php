<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
    <div class="scrolleable m-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <caption class="w-full bg-gray-50 border-b-2">
                <div class="px-6 py-3 text-right flex items-center">
                    <h3 class="uppercase text-black inline-block font-bold text-xl text-center grow">{{__("Services")}}</h3>
                    <button class="ml-2 px-5 py-2 bg-cyan-500 rounded text-white transition-all duration-200 hover:bg-cyan-700"onclick="generateInsertModal()">{{__("Create")}}</button>
                </div>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">{{__("Name")}}</th>
                    <th scope="col" class="px-6 py-3 text-center showResp">{{__("Data")}}</th>
                    <th scope="col" class="px-6 py-3 text-center notShowResp">{{__("Email")}}</th>
                    <th scope="col" class="px-6 py-3 text-center notShowResp">{{__("Website")}}</th>
                    <th scope="col" class="px-6 py-3 text-center notShowResp">{{__("Phone")}}</th>
                    <th scope="col" class="px-6 py-3 text-center">{{__("Actions")}}</th>
                </tr>
            </thead>
            <tbody id="tblServicios">
                @foreach ($servicios as $servicio)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 text-center">{{$servicio->nombre}}</td>
                        <td class="px-6 py-4 text-center showResp">
                            <p>{{__("Email")}}: {{$servicio->correo}}</p>
                            <p>{{__("Website")}}:
                            @if ($servicio->web == null)
                                -
                            @else
                                {{$servicio->web}}
                            @endif
                            </p>
                            <p>{{__("Phone")}}: {{$servicio->telefono}}</p>
                        </td>
                        <td class="px-6 py-4 text-center notShowResp">{{$servicio->correo}}</td>
                        @if ($servicio->web == null)
                        <td class="px-6 py-4 text-center notShowResp">-</td>
                        @else
                            <td class="px-6 py-4 text-center notShowResp">{{$servicio->web}}</td>
                        @endif
                        <td class="px-6 py-4 text-center notShowResp">{{$servicio->telefono}}</td>
                        <td class="px-6 py-4 text-center">
                            <button class="ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500 margin-responsive-button" onclick="modificarServicio({{$servicio->id}})"><i class="fa-solid fa-pen-to-square" style="color: #000000;"></i></button>
                            <button class="ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500" onclick="eliminarServicio({{$servicio->id}})"><i class="fa-solid fa-trash" style="color: #000000;"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="h-[40vh] bg-white m-auto items-center justify-center hidden no-files">
            <p class="m-auto w-4/5 block text-center">
                {{__("It seems like there's no service available, be careful.")}}
                <br><br>
                <i class="fa-solid fa-user-ninja text-5xl"></i>
            </p>
        </div>
    </div>
    <script>
        function generateInsertModal(){
            Swal.fire({
                html:`
                <form onsubmit="createNewService()">
                <h4 class="uppercase text-black inline-block font-bold text-xl text-center grow">{{__('New service')}}</h4>
                <br>
                <br>
                <label>{{__('Name')}}</label>
                <br>
                <input class="border-2 border-solid rounded border-gray-400 p-2 my-3" id="insertFormName" type="text" placeholder="{{__('Write the name of the service')}}">
                <br>
                <label>{{__('Email')}}</label>
                <br>
                <input class="border-2 border-solid rounded border-gray-400 p-2 my-3" id="insertFormCorreo" type="email" placeholder="{{__('Write the email of the service')}}">
                <br>
                <label>{{__('Website')}}</label>
                <br>
                <input class="border-2 border-solid rounded border-gray-400 p-2 my-3" id="insertFormWeb" type="text" placeholder="{{__('Write the website of the service')}}">
                <br>
                <label>{{__('Phone')}}</label>
                <br>
                <input class="border-2 border-solid rounded border-gray-400 p-2 my-3" id="insertFormPhone" type="text" placeholder="{{__('Write the phone of the service')}}">
                <br>
                <br>
                <input class="ml-2 px-5 py-2 bg-cyan-500 rounded text-white transition-all duration-200 hover:bg-cyan-700" class="btnCrud" type="submit" value="{{__('Send')}}">
                </form>
                `,
                showConfirmButton: false
            })
            document.getElementById("insertFormName").blur();
        }

        const createNewService = async()=>{
            event.preventDefault();
            form = new FormData();
            form.append("nombre",document.getElementById("insertFormName").value)
            form.append("correo",document.getElementById("insertFormCorreo").value)
            form.append("web",document.getElementById("insertFormWeb").value)
            form.append("telefono",document.getElementById("insertFormPhone").value)
            let resul = await axios.post(URL_API+'/servicios/insertNewService', form);
            if(resul){
                alertaDefecto("success",trans("Succesful result"),trans("Service created"),true);
            }else{
                alertaDefecto("error",trans("Error"),trans("Service not created"),false);
            }
        }

        const eliminarServicio = async(id)=>{
            let resul = await axios.delete(URL_API+'/servicios/deleteNewService/'+id);
            if(resul){
                alertaDefecto("success",trans("Succesful result"),trans("Service deleted"),true);
            }else{
                alertaDefecto("error",trans("Error"),trans("Service not deleted"),false);
            }
        }

        const  modificarServicio = async(id) => {
            let resul = await axios.post(URL_API+'/servicios/getDataService/'+id);
            makeModelMod(resul.data);
        }
        const makeModelMod = async(data) => {
            if(data.web == null){
                web = ""
            }else{
                web = data.web
            }
            Swal.fire({
                html:`
                <form onsubmit="modService(${data.id})">
                <h4 class="uppercase text-black inline-block font-bold text-xl text-center grow">{{__("Modify service")}}</h4>
                <br>
                <br>
                <label>{{__("Name")}}</label>
                <br>
                <input class="border-2 border-solid rounded border-gray-400 p-2 my-3" id="modFormName" type="text" placeholder="{{__('Write the name of the service')}}" value="${data.nombre}">
                <br>
                <label>{{__("Email")}}</label>
                <br>
                <input class="border-2 border-solid rounded border-gray-400 p-2 my-3" id="modFormCorreo" type="email" placeholder="{{__('Write the email of the service')}}" value="${data.correo}">
                <br>
                <label>{{__("Website")}}</label>
                <br>
                <input class="border-2 border-solid rounded border-gray-400 p-2 my-3" id="modFormWeb" type="text" placeholder="{{__('Write the website of the service')}}" value="${web}">
                <br>
                <label>{{__("Phone")}}</label>
                <br>
                <input class="border-2 border-solid rounded border-gray-400 p-2 my-3" id="modFormPhone" type="text" placeholder="{{__('Write the phone of the service')}}" value="${data.telefono}">
                <br>
                <br>
                <input class="ml-2 px-5 py-2 bg-cyan-500 rounded text-white transition-all duration-200 hover:bg-cyan-700" class="btnCrud" type="submit" value="{{__('Send')}}">
                </form>
                `,
                showConfirmButton: false
            })
            document.getElementById("modFormName").blur();
        }

        const modService = async(id) =>{
            event.preventDefault();
            form = new FormData();
            form.append("nombre",document.getElementById("modFormName").value)
            form.append("correo",document.getElementById("modFormCorreo").value)
            form.append("web",document.getElementById("modFormWeb").value)
            form.append("telefono",document.getElementById("modFormPhone").value)
            form.append("id",id)
            let resul = await axios.post(URL_API+'/servicios/updateDataService', form);
            if(resul){
                alertaDefecto("success",trans("Succesful result"),trans("Service modified"),true);
            }else{
                alertaDefecto("error",trans("Error"),trans("Service not modified"),false);
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
            }).then((result) => {
                if(recharge){
                    // getData();
                    location.reload();
                }
            })
        }

        const getData = async() => {
            let resul = await axios.post(URL_API+'/servicios/getShowDataService');
            var servicios = resul.data
            var tableData = "";
            servicios.forEach(element => {
                if(element.web == null){
                    web = "-"
                }else{
                    web = element.web
                }
                tableData += `
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4 text-center">${element.nombre}</td>
                <td class="px-6 py-4 text-center showResp">
                    <p>{{__("Email")}}: ${element.correo}</p>
                    <p>{{__("Website")}}: ${web} </p>
                    <p>{{__("Phone")}}: ${element.telefono}</p>
                </td
                <td class="px-6 py-4 text-center">${element.correo}</td>
                <td class="px-6 py-4 text-center">${web}</td>
                <td class="px-6 py-4 text-center">${element.telefono}</td>
                <td class="px-6 py-4 text-center">
                    <button class="ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500 margin-responsive-button" onclick="modificarServicio(${element.id})"><i class="fa-solid fa-pen-to-square" style="color: #000000;"></i></button>
                    <button class="ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500" onclick="eliminarServicio(${element.id})"><i class="fa-solid fa-trash" style="color: #000000;"></i></button>
                </td></tr>`
            });
            document.getElementById("tblServicios").innerHTML = tableData;
        }

        if (document.querySelector('tbody').children.length == 0){
            document.querySelector('.no-files').classList.add('flex');
            document.querySelector('.no-files').classList.remove('hidden');
        }
    </script>
</x-app-layout>
