<x-app-layout>
    @vite(['resources/css/menu.css'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div id="comunidades_div" style="height: calc(100vh - 96px)" class="flex flex-col gap-6 sm:p-auto overflow-hidden">
        <div class=" bg-white px-6 py-6 rounded-lg w-80">
            <h4 class="text-center mb-3 font-bold text-lg">{{__("JOIN A COMMUNITY")}}</h4>
            <form method="POST" action="{{ route('addToCommunity') }}">
                @csrf
                <p class=" text-gray-500 text-xs mb-3">* {{__("Write the code of community if it's already created or create a community for the neighbors below.")}}</p>
                <input type="text" name="codigo" placeholder="{{__("Code")}}" class="w-full rounded">
                <input type="submit" class="bg-[#002D74] mt-6 rounded cursor-pointer px-2 text-white py-2 hover:scale-105 duration-300 w-full" value="{{__("Send")}}">
            </form>
        </div>

        <div class="mt-6 grid grid-cols-3 items-center text-gray-400">
            <hr class="border-gray-400">
            <p class="text-center text-sm">{{__("OR")}}</p>
            <hr class="border-gray-400">
        </div>

        <a href="{{route('comunidad_vista')}}" class="bg-[#002D74] rounded-xl px-2 text-white py-2 hover:scale-105 duration-300">
            {{__("Create Community")}}
        </a>
    </div>
    @if(isset($error))
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{__('Community not found')}}",
            })
        </script>
    @endif
</x-app-layout>
