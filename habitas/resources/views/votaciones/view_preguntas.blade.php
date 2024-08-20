<x-app-layout>

@vite(['resources/css/encuestas.css'])
<script>
    const delete_op = (num) => {
        document.getElementById('opcion_div_'+num).parentElement.remove();
        document.querySelector('#opciones').childNodes.length == 0 ? (document.querySelector('.btn-crear-pregunta').parentElement.classList.add('hidden')) : '';
    }
</script>

<div class="flex justify-center min-w-full h-full flex-col items-center">

    <a href="{{route('encuesta_vista')}}" class="self-start px-3 py-1 bg-slate-300 hover:bg-slate-600 hover:text-white transition-all rounded mb-3"><i class="fa-solid fa-arrow-left"></i></a>

    <div class="h-4/5 max-w-5xl w-full overflow-hidden flex justify-center flex-col shadow-md z-10">

        <!-- Encuesta caption -->
        <table class="w-full relative text-sm text-left text-gray-500 dark:text-gray-400 bg-white overflow-hidden z-10">
            <caption class="w-full bg-gray-50 border-b-2">
                <h2 class="uppercase text-black inline-block font-bold text-xl text-center w-full pt-4">{{ __('Polls')}}</h2>
                <div class="px-3 py-3 text-right flex items-center">

                    <input type="hidden" id="id_junta" value="{{$id}}"/>
                    <!-- <label for="pregunta" class=" text-lg">Pregunta</label> -->
                    <input type="text" id="pregunta" name="pregunta" class="mr-2 h-9 rounded grow" placeholder="Pregunta...">

                    <button id="añadir_op" class="btn-nueva-opcion">Añadir opción</button>
                </div>

            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 w-auto">{{ __('Ask')}}</th>
                    <th scope="col" style="width: 16.6666%" class="px-2 py-3 text-center">{{ __('Delete')}}</th>
                </tr>
            </thead>
        </table>

        <!-- Cuerpo de la tabla -->
        <div class="h-full overflow-auto bg-white">
            <div id="opciones" class=" max-h-full z-30 top-0 sticky overflow-auto flex items-center flex-col bg-slate-100 w-full border-b-2"></div>

            <table class="w-full h-full relative text-sm text-left text-gray-500 dark:text-gray-400 bg-white overflow-hidden z-0 -mt-10">

                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 opacity-0">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-auto">{{ __('Ask')}}</th>
                        <th scope="col" class="px-2 py-3 w-[16.6666%]">{{ __('Delete')}}</th>
                    </tr>
                </thead>

                <tbody id="listado_preguntas" class="h-4 overflow-auto -mt-4">
                    @foreach ($preguntas as $pregunta)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="h-11 px-3 w-auto">{{$pregunta->pregunta}}</td>
                            <td style="width: 16.6666%" class="h-11 text-center"><button onclick="eliminar_pregunta({{$pregunta->id}},this)"><i class="fa-regular fa-trash hover:text-red-500 hover:font-bold"></i></button></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="w-auto"></td>
                        <td style="width: 16.6666%"></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Footer -->
        <table class="w-full">
            <tfoot class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <td colspan="4" scope="col" class="px-6 py-3 hidden">
                        <button type="button"  class="btn-crear-pregunta">Crear Pregunta</button>
                    </td>
                </tr>
            </tfoot>
        </table>

    </div>

</div>

<script>
    var num_op = 1;

    const nueva_opt = () => {
        const opt = document.querySelector('#opciones');
        const newOpt = document.createElement('article');
        newOpt.innerHTML = `
        <div id="opcion_div_${num_op}" class="py-2 border-b-2">
            <label for="opcion${num_op}"> Opción  
            <input type="text" id="opcion${num_op}" name="opciones[]" class="h-7 rounded">
            <button onclick="delete_op(${num_op})" class="px-3 py-1 bg-red-200 rounded"><i class="fa-regular fa-trash"></i></button></label>
        </div>`;
        opt.appendChild(newOpt);
        num_op++;

        document.querySelector('.btn-crear-pregunta').parentElement.classList.remove('hidden');
    }

    const crearPregunta=async()=>{
        document.querySelector('.btn-crear-pregunta').disabled = true;
        const pregunta = document.querySelector('#pregunta').value;
        const opciones = document.querySelectorAll('input[name="opciones[]"]');
        console.log(pregunta);
        if (!pregunta){
            return true;
        }

        // Crear un objeto de encuesta
        const encuesta = {
            pregunta: pregunta,
            opciones: []
        };

        // Agregar las opciones a la encuesta
        opciones.forEach((opcion) => {
            encuesta.opciones.push(opcion.value);
        });

        // Convertir la pregunta en JSON
        const encuestaJSON = JSON.stringify(encuesta);

        let formdata = new FormData();
        formdata.append('id',id_junta.value);
        formdata.append('encuesta', encuestaJSON);

        // Creamos la nueva pregunta de la encuesta y listamos las preguntas en la tabla
        let resul = await axios.post(URL_API+'/votacion/new_pregunta', formdata);
        if(resul.status === 201){
            lista_preguntas();
        }

        document.querySelector('#opciones').innerHTML = ''; //Limpiamos las opciones
        document.querySelector('#pregunta').value = ''; //Limpiamos la pregunta

        document.querySelector('.btn-crear-pregunta').parentElement.classList.add('hidden'); //Escondemos el boton pregunta
        document.querySelector('.btn-crear-pregunta').disabled = false; //Habilitamos el boton
    }
    const eliminar_pregunta=async(id,boton)=>{
        boton.disabled = true;
        boton.parentElement.parentElement.remove();
        let resul = await axios.delete(URL_API+'/votacion/del_pregunta/'+id)
        if(resul.status === 200){
            lista_preguntas();
        }
        boton.disabled = false;
    }
    const lista_preguntas=async()=>{
        let resul = await axios.get(URL_API+'/votacion/lista_preguntas/'+id_junta.value)
        if(resul.status === 200){
            listado_preguntas.innerHTML = '';
            resul.data.forEach(element => {
                listado_preguntas.innerHTML +=`
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="h-11 px-3 w-auto">${element.pregunta}</td>
                    <td class="h-11 text-center w-[16.6666%]"><button onclick="eliminar_pregunta(${element.id},this)" ><i class="fa-regular fa-trash hover:text-red-500 hover:font-bold"></i></button></td>
                </tr>
                `;
            });
            listado_preguntas.innerHTML +=`
            <tr>
                <td class="h-auto"></td>
                <td class="h-auto"></td>
            </tr>`;
        }
    }

    document.querySelector('.btn-nueva-opcion').addEventListener('click',nueva_opt);
    document.querySelector('.btn-crear-pregunta').addEventListener('click',crearPregunta);
</script>
</x-app-layout>
