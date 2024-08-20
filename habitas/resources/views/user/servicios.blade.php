<x-app-layout>
    <style>
        main{
            display: flex;
            justify-content: center
        }
        thead{
            position: sticky;
            top: 0;
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
        <table class=" w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">{{__("Name")}}</th>
                    <th scope="col" class="px-6 py-3 text-center showResp">{{__("Data")}}</th>
                    <th scope="col" class="px-6 py-3 text-center notShowResp">{{__("Email")}}</th>
                    <th scope="col" class="px-6 py-3 text-center notShowResp">{{__("Website")}}</th>
                    <th scope="col" class="px-6 py-3 text-center notShowResp">{{__("Phone")}}</th>
                </tr>
            </thead>
            <tbody>
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
        if (document.querySelector('tbody').children.length == 0){
            document.querySelector('.no-files').classList.add('flex');
            document.querySelector('.no-files').classList.remove('hidden');
        }
    </script>
</x-app-layout>
