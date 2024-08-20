<x-app-layout>
    <style>
        .iconButton{
            font-size: 60px;
            padding-bottom: 10px;
        }
    </style>
    <div class="flex flex-col lg:flex-row w-full justify-center ">

        <div class="lg:w-1/4 bg-slate-100 shadow-lg border-green-500 border-2 rounded-lg flex flex-col gap-3 justify-center text-center m-4 py-3">
            <h1>Encuestas Totales: {{$eFinalizadas + $eEspera + $eActivas}}</h1>
            <h1>Encuestas Activas: {{$eActivas}}</h1>
            <h1>Encuestas en Espera: {{$eEspera}}</h1>
            <h1>Encuestas Finalizadas: {{$eFinalizadas}}</h1>
        </div>

        <div class="lg:w-1/4 bg-slate-100 shadow-lg border-blue-500 border-2 rounded-lg flex flex-col gap-3 justify-center text-center m-4 py-3">
            <h1>Usuarios Total: {{$usuarios}}</h1>
            <h1>Usuarios Validados: {{$usuariosVal}}</h1>
            <h1>Usuarios No Validados: {{$usuariosNoVal}}</h1>
        </div>

        <div class="lg:w-1/4 bg-slate-100 shadow-lg border-yellow-500 border-2 rounded-lg flex flex-col gap-3 justify-center text-center m-4 py-3">
            <h1>Tamaño carpeta Private: {{$folderPrivate}} bytes</h1>
            <h1>Tamaño carpeta Storage: {{$folderStorage}} bytes</h1>
        </div>
        <div class="lg:w-1/4 bg-slate-100 shadow-lg border-red-500 border-2 rounded-lg flex flex-col gap-3 justify-center text-center m-4 py-3">
            <h1>Num. Modulos: {{$modulos}}</h1>
            <h1>Num. Modulos Activos: {{$modulos}}</h1>
        </div>
    </div>
    @if(env('APP_ENV') =='local')
    <div>

        <form method="POST" action="{{route('reboot')}}" style="text-align: center;">
            @csrf
            <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all"><i class="fa-solid fa-arrow-rotate-right iconButton"></i><br>Reboot</button>
        </form>
    </div>
    @endif
</x-app-layout>
