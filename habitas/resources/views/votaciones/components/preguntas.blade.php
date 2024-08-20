@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="{{asset('js/fontawesome.js')}}"></script>
<div class="flex justify-center min-w-full h-full flex-col items-center">

    <div class="h-4/5 max-w-5xl w-full overflow-hidden flex justify-center flex-col shadow-md">

        <!-- Encuesta caption -->
        <table class="w-full relative text-sm text-left text-gray-500 dark:text-gray-400 bg-white overflow-hidden z-10">
            <caption class="w-full bg-gray-50 border-b-2">
                <h2 class="uppercase text-black inline-block font-bold text-xl text-center w-full pt-4">Encuesta</h2>
                <div class="px-3 py-3 text-right flex items-center">

                    <input type="hidden" id="id_junta" value="{{$id}}"/>
                    <!-- <label for="pregunta" class=" text-lg">Pregunta</label> -->
                    <input type="text" id="pregunta" name="pregunta" class="mr-3 h-9 rounded grow" placeholder="Pregunta...">

                    <button id="añadir_op" class="ml-2 px-5 py-2 font-bold bg-cyan-500 rounded text-white transition-all duration-200 hover:bg-cyan-700">Añadir</button>
                </div>

            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 w-2/4">Pregunta</th>
                    <th scope="col" style="width: 16.6666%" class="px-2 py-3 text-center">Eliminar</th>
                </tr>
            </thead>
        </table>

        <!-- Cuerpo de la tabla -->
        <div class="h-full overflow-auto bg-white">
            <div id="opciones" class=" max-h-full z-30 top-0 sticky overflow-auto flex items-center flex-col bg-slate-100 w-full border-b-2"></div>
            <table class="w-full h-full relative text-sm text-left text-gray-500 dark:text-gray-400 bg-white overflow-hidden z-0 -mt-10">

                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 opacity-0">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-2/4">Pregunta</th>
                        <th style="width: 16.6666%" scope="col" class="px-2 py-3">Eliminar</th>
                    </tr>
                </thead>

                <tbody id="listado_preguntas" class="h-4 overflow-auto -mt-4">
                    @foreach ($preguntas as $pregunta)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="h-11 px-3 w-2/4">{{$pregunta->pregunta}}</td>
                            <td style="width: 16.6666%" class="h-11 text-center"><button onclick="eliminar_pregunta({{$pregunta->id}})"><i class="fa-regular fa-trash"></i></button></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class=" w-2/4"></td>
                        <td style="width: 16.6666%"></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Footer -->
        <table class="w-full">
            <tfoot class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <td colspan="4" scope="col" class="px-6 py-3">
                        <button type="button" onclick="crearEncuesta()"  class="w-full font-bold text-base px-5 py-2 bg-cyan-500 rounded text-white transition-all duration-200 hover:bg-cyan-700">Crear Pregunta</button>
                    </td>
                </tr>
            </tfoot>
        </table>

    </div>




</div>

<script>
    var num_op = 1;
    añadir_op.addEventListener("click", function(){
        // Contenedor (Padre)
        const opt = document.querySelector('#opciones');
        const newOpt = document.createElement('span');
        newOpt.innerHTML = `
        <div id="opcion_div_${num_op}" class="py-2 border-b-2">
            <label for="opcion${num_op}"> Opción
            <input type="text" id="opcion${num_op}" name="opciones[]" class="h-7 rounded">
            <button onclick="delete_op(${num_op})" class="px-3 py-1 bg-red-200 rounded"><i class="fa-regular fa-trash"></i></button></label>
        </div>`;
        opt.appendChild(newOpt);
        num_op++
    });
    const delete_op=(num)=>{
        document.getElementById('opcion_div_'+num).remove();
    }
    const crearEncuesta=async()=>{
        const pregunta = document.querySelector('#pregunta').value;
        const opciones = document.querySelectorAll('input[name="opciones[]"]');
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
        console.log(encuestaJSON);
        let formdata = new FormData();
        formdata.append('id',id_junta.value);
        formdata.append('encuesta', encuestaJSON);

        let resul = await axios.post(URL_API+'/votacion/new_pregunta', formdata);
        console.log('Crear: ',resul);
        if(resul.status === 201){
            lista_preguntas();
        }
    }
    const eliminar_pregunta=async(id)=>{
        let resul = await axios.delete(URL_API+'/votacion/del_pregunta/'+id)
        if(resul.status === 200){
            lista_preguntas();
        }
    }
    const lista_preguntas=async()=>{
        let resul = await axios.get(URL_API+'/votacion/lista_preguntas/'+id_junta.value)
        console.log(resul)
        if(resul.status === 200){
            listado_preguntas.innerHTML = '';
            resul.data.forEach(element => {
                listado_preguntas.innerHTML +=`
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="h-11 px-3">${element.pregunta}</td>
                    <td class="h-11 text-center"><button>Ver</button></td>
                    <td class="h-11 text-center"><i class="fa-regular fa-pen-to-square"></i></td>
                    <td class="h-11 text-center"><button onclick="eliminar_pregunta(${element.id})" ><i class="fa-regular fa-trash"></i></button></td>
                </tr>
                `;
            });
            listado_preguntas.innerHTML +=`
            <tr>
                <td class="h-auto"></td>
                <td class="h-auto"></td>
                <td class="h-auto"></td>
                <td class="h-auto"></td>
            </tr>`;
        }
    }
</script>
