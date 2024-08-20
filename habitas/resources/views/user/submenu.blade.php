<x-app-layout>
    @vite(['resources/css/menu.css'])
    <!-- <h2 class="sticky top-0 text-3xl text-center p-4 font-bold title-anuncio cursor-pointer">{{$comunidad->nombre}}</h2> -->


    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 h-full lg:grid-cols-4 justify-items-center items-center lg:overflow-x-hidden addPaddign">
        @if ($submenu == "2")

            <form method="GET" action="{{ route('vecinos_lista') }}" class="btn_test">
                <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-people-group iconButton"></i><br>{{__("Neighbors list")}}</button>
            </form>
            @if (in_array('2', $modulos))
            <form method="GET" action="{{route('Viewstats')}}" class="btn_test">
                <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-coins iconButton"></i><br>{{__("Expenses")}}</button>
            </form>
            @else
                <form class="btn_test" style="pointer-events: unset" >
                    <button type="button" class="bg-[#383736] w-52 h-52 m-5 rounded-full text-[#777670] outline-0 outline outline-offset-0 cursor-not-allowed transition-all"><i class="fa-solid fa-coins iconButton"></i><br>{{__("Expenses")}}</button>
                </form>
            @endif
            <form method="GET" action="{{route('seguros')}}" class="btn_test">
                <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-shield-halved iconButton"></i><br>{{ __('Insurances') }}</button>
            </form>
            <form method="GET" action="{{url('adjuntos')}}" class="">
                <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-folder-open iconButton"></i><br>{{__("Attachament files")}}</button>
            </form>
            <form method="GET" action="{{url('servicios')}}" class="btn_test">
                <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-person-military-pointing iconButton"></i><br>{{__("Services")}}</button>
            </form>
        @endif

        @if($submenu == "1")

        @if (in_array('1', $modulos))
        <form method="GET" action="{{route('meet')}}" class="btn_test" >
            <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-video iconButton"></i><br>{{__("Meeting")}}</button>
        </form>
        @else
            <form class="btn_test" style="pointer-events: unset" >
                <button type="button" class="bg-[#383736] w-52 h-52 m-5 rounded-full text-[#777670] outline-0 outline outline-offset-0 cursor-not-allowed transition-all"><i class="fa-solid fa-video iconButton"></i><br>{{__("Meeting")}}</button>
            </form>
        @endif
            <form method="GET" action="{{ route('calendario') }}" class="btn_test">
                <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-calendar iconButton"></i><br>{{__("Calendar")}}</button>
            </form>

            <form method="GET" action="{{route('anuncio')}}" class="">
                <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-bullhorn iconButton"></i><br>{{__("Bulletin board")}}</button>
            </form>

            @if (in_array('4', $modulos))
            <form method="GET" action="{{route('encuesta_lista')}}" class="btn_test">
                <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-square-poll-vertical iconButton"></i><br>{{__("Polls")}}</button>
            </form>
            @else
            <form class="btn_test" style="pointer-events: unset" >
                <button type="button" class="bg-[#383736] w-52 h-52 m-5 rounded-full text-[#777670] outline-0 outline outline-offset-0 cursor-not-allowed transition-all"><i class="fa-solid fa-square-poll-vertical iconButton"></i><br>{{__("Polls")}}</button>
            </form>
            @endif
            <form method="GET" action="{{route('incidencias')}}" class="">
                <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-house-crack iconButton"></i><br>{{__("Incidents")}}</button>
            </form>

            @if (in_array('3', $modulos))
                <form method="GET" action="{{route('chatPresidente')}}" class="btn_test" >
                    <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-comments iconButton"></i><br>{{__("President chat")}}</button>
                </form>

            @else
                <form class="btn_test" style="pointer-events: unset" >
                    <button type="button" class="bg-[#383736] w-52 h-52 m-5 rounded-full text-[#777670] outline-0 outline outline-offset-0 cursor-not-allowed transition-all"><i class="fa-solid fa-comments iconButton"></i><br>{{__("President chat")}}</button>
                </form>

            @endif
        @endif

        @if ($submenu == "3")

            @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)
                <form method="GET" action="{{url('servicios/Admin')}}" class="btn_test">
                    <button type="submit" class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all"><i class="fa-solid fa-person-military-to-person iconButton"></i><br>{{__("Management Services")}}</button>
                </form>
                <form method="GET" action="{{url('adjuntos/Admin')}}" class="text-center">
                    <button type="submit" class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all"><i class="fa-solid fa-file-pen iconButton"></i><br>{{__("Management")}}<br>{{__("Attachments")}}</button>
                </form>
                @if (in_array('4', $modulos))
                <form method="GET" action="{{route('encuesta_vista')}}" class="text-center">
                    <button type="submit" class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all"><i class="fa-solid fa-scale-balanced iconButton"></i><br>{{__("Management Polls")}}</button>
                </form>
                @else
                <form class="btn_test" style="pointer-events: unset" >
                    <button type="button" class="bg-[#383736] w-52 h-52 m-5 rounded-full text-[#777670] outline-0 outline outline-offset-0 cursor-not-allowed transition-all"><i class="fa-solid fa-scale-balanced iconButton"></i><br>{{__("Management Polls")}}</button>
                </form>
                @endif
                <form method="GET" action="{{route('presidente_elec')}}" class="text-center">
                    <button type="submit" class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all"><i class="fa-solid fa-crown iconButton"></i><br>{{__("President")}}</button>
                </form>
                @if (in_array('2', $modulos))
                <form method="GET" action="{{route('ViewGastos')}}" class="text-center">
                    <button class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all" type="submit"><i class="fa-solid fa-hand-holding-dollar iconButton"></i><br>{{__("Management Expenses")}}</button>
                </form>
                @else
                <form class="btn_test" style="pointer-events: unset" >
                    <button type="button" class="bg-[#383736] w-52 h-52 m-5 rounded-full text-[#777670] outline-0 outline outline-offset-0 cursor-not-allowed transition-all"><i class="fa-solid fa-hand-holding-dollar iconButton"></i><br>{{__("Management Expenses")}}</button>
                </form>
                @endif
                <form method="GET" action="{{url('modulos')}}" class="text-center">
                    <button type="submit" class="bg-sky-950 w-52 h-52 m-5 rounded-full text-white outline-0 outline outline-offset-0 hover:outline-8 outline-sky-950/[.40] transition-all"><i class="fa-solid fa-chart-pie iconButton"></i><br>{{__("Module")}}</button>
                </form>
            @endif




        @endif
    </div>

    <style>
        @media (max-width: 640px) {
            .addPaddign{
                height: auto;
                padding-bottom: 101px;
            }
        }
    </style>
    <script>
        // console.log(document.getElementsByClassName('lg:grid-cols-4')[0].children.length);
        if (document.getElementsByClassName('grid')[0].children.length == 3){
            document.getElementsByClassName('grid')[0].classList.remove('lg:grid-cols-4');
            document.getElementsByClassName('grid')[0].classList.add('lg:grid-cols-3');
        } else if(document.getElementsByClassName('grid')[0].children.length < 3){
            document.getElementsByClassName('grid')[0].classList.remove('lg:grid-cols-4');
            document.getElementsByClassName('grid')[0].classList.add('lg:grid-cols-2');
        }
        // console.log(document.getElementsByClassName('lg:grid-cols-4')[0].children.length);
        // document.querySelector('lg:grid-cols-4').children;
    </script>
</x-app-layout>
