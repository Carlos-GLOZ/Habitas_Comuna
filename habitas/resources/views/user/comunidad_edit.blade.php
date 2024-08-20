<x-app-layout>
    <div>
        <form method="POST" enctype="multipart/form-data" action="{{route('comunidad_editar')}}">
            @csrf
            <input type="hidden" name="id" value='{{$comunidad->id}}' />
            <br>
            <input type='text' name="nombre" value="{{$comunidad->nombre}}"/>
            <br>
            <input type='text' name="codigo" value="{{$comunidad->codigo}}"/>
            <br>
            <input type='file' name="img" />
            <br>
            <select name="newCreator">
                <option value=null>
                    {{__("Me")}}
                </option>
                @foreach ($vecinos as $vecino)
                <option value={{$vecino->id}}>
                    {{$vecino->nombre}}
                </option>
                @endforeach

            </select>
            <br>
            <button type="submit" >{{__("Save")}}</button>
        </form>
    </div>


</x-app-layout>
