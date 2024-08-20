<x-app-layout>
<!-- Chat -->



<input id="id_comunidad" type="hidden" value="{{Session::get('comunidad')}}" />

<div class="flex flex-col sm:flex-row h-full">
    @if(Auth::user()->id == $comunidad->presidente_id)
        <input id="id_user" type="hidden" value="{{Auth::user()->id}}" />

        @vite('resources/js/chatPresi.js')
        <div class=" w-full sm:w-1/4 p-2 bg-white">
            <form action="" class="flex" id="f_buscar">
                <input type="search" name="busqueda" value="" class="py-1 px-3 w-full mb-2" placeholder="Alice...">
            </form>
            <ul class="flex sm:flex-col flex-row sm:w-full overflow-x-auto sm:overflow-auto sm:h-full" style="max-height: calc(100% - 2.80rem);" id="busqueda">
                @foreach($vecinos as $vecino)
                    <li class="p-2 border-b hover:bg-gray-200 cursor-pointer transition-all vecino-item flex justify-start items-center min-w-[180px]">
                        <input type="hidden" name="usuario" value="{{$vecino->id}}" />
                        @if ($vecino->visible == 1)
                            <img class="w-12 h-12 block mx-auto rounded-full sm:mx-0 sm:shrink-0 pointer-events-none" src="{{asset('storage/'.$vecino->profile_photo_path)}}" onerror="">
                        @else
                            <img class="w-12 h-12 block mx-auto rounded-full sm:mx-0 sm:shrink-0 pointer-events-none" src="https:\/\/ui-avatars.com\/api\/?name={{ __("Vecino Anonimo") }}&color=7F9CF5&background=EBF4FF" alt="Womans Face">
                        @endif
                        <span class="pl-3 pointer-events-none">{{$vecino->name}} {{$vecino->apellidos}}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="mensaje grow">
        @if(Auth::user()->id == $comunidad->presidente_id)
            <div class="w-full h-full bg-gray-400 flex items-center flex-col justify-center text-white" id="chat_cli">
                <p class=" text-justify w-3/5 text-clip">Debes de seleccionar un chat, si quieres enviar mensajes a alguien.</p>
            </div>
        @else
        @vite('resources/js/chatVecino.js')
            <input id="id_user" type="hidden" value="{{Auth::user()->id}}" />
            <input id="id_user_presidente" type="hidden" value="{{$comunidad->presidente_id}}" />
            <div class="w-full h-full bg-gray-400 flex flex-col items-center justify-center text-white" id="chat_cli">

                <div id="historyChat" class="h-0 sm:h-4/5 px-4 grow bg-white text-black flex flex-col w-full overflow-auto" style="background: #F3F4F6;">
                    @foreach ($historial_mensajes as $hst_msg)
                        <p class="{{ $hst_msg->user_id_recibido == Auth::user()->id ? 'msg_historial_recibido' : 'msg_historial_enviado' }}">{{$hst_msg->msg}}</p>
                    @endforeach

                </div>

                <form id="form" class="flex w-full justify-center items-center" style="height: 14%;background: #F3F4F6;">
                    <div class="form-outline w-4/5 sm:h-20 flex items-center pr-2">
                        {{-- <textarea id="mesage" class="form-control form-control-lg text-black h-10 resize-none overflow-hidden rounded-full shadow-md pb-2 px-4 border-none w-full" name="mesage" required></textarea> --}}
                        <input id="mesage" class="form-control form-control-lg text-black h-10 resize-none overflow-hidden rounded-full shadow-md pb-2 px-4 border-none w-full" style="outline: none;caret-color: blue;" name="mesage" />
                    </div>
                    <button type="submit" id="Enviar_Mensaje_btn" class="inline-block bg-blue-400 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 hover:bg-blue-600 hover:text-white transition duration-300 ease-in-out" style="height: 40px"><i class="fa-solid fa-paper-plane-top" style="color: #ffffff;"></i></button>
                </form>
            </div>
        @endif
    </div>
</div>



<style>

    .msg_historial_recibido{

        margin-top: 20px;
        background: white;
        padding: 5px 9px;
        border-radius: 5px;
        align-self: flex-start;
    }

    .msg_historial_enviado{
        margin-top: 20px;
        background: #21abff;
        color: white;
        padding: 5px 9px;
        border-radius: 5px;
        align-self: flex-end;
        max-width: 80%;
    }
    .msg_historial_recibido + .msg_historial_recibido{
        margin-top: 5px;
    }

    .msg_historial_enviado + .msg_historial_enviado{
        margin-top: 5px;
    }

    #historyChat::-webkit-scrollbar {
    width: 12px;
    }

    #historyChat::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0);
    }

    #historyChat::-webkit-scrollbar-thumb {
    background-color: #037eca;
    border-radius: 20px;
    transition: all 250ms ease-in-out
    }

    #historyChat::-webkit-scrollbar-thumb:hover {
    background-color: #035d95;
    border-radius: 20px;
    }
</style>
<!-- En cuanto al backend del pusher debemos de tener en cuenta que solo el presidente puede ver quien le
esta hablando, es decir, puede ver multiples vecinos y que los chats de otros no se vean en los otros:
Podemos utilizar el "id_usuario" y "presidente" para tener una relación de quien recibe el mensaje del pusher

Checklist de como crear una cuenta de comunidad
-->


</x-app-layout>
