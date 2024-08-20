<x-app-layout>

    <div class="w-full m-3 rounded-md shadow-md py-8 bg-white pb-14">
        <div class="w-full flex flex-row gap-4 items-center justify-around">
            <select onchange="cambioTipo()" id="tipo_select">
                <option value="luz">{{ __('Electricity')}} </option>
                <option value="gas">  {{ __('Gas')}}</option>
                <option value="agua"> {{ __('Water')}}</option>
                <option value="mantenimiento">{{ __('Maintenance')}}</option>
                <option value="otros">{{ __('Others')}}</option>
            </select>

            <div>
                <label for="year">{{ __('Year')}}:</label>
                <input type="number" id="year" name="year" min="1900" max="2099" value="{{date('Y')}}" onchange="cambioTipo()">
            </div>

        </div>



        <div id="contenedor">

            <div class="flex flex-col lg:flex-row gap-4 m-4 p-3">
                <div class="w-full lg:w-1/2">
                    <canvas id="consumo"></canvas>
                </div>
                <div class="w-full lg:w-1/2">
                    <canvas id="gasto"></canvas>
                </div>
            </div>
            <div class="mt-8 h-60 flex justify-center items-center">
                <div class="w-full ">
                    <p>{{ __('Total cost')}}: <span id="total"></span></p>
                  <canvas id="todo" ></canvas>
                </div>


            </div>
        </div>

        @livewire('stats-gastos')
    </div>









    <script>
        const meses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];


        const gasto =(chartDataG)=>{
            var ctxG = document.getElementById('gasto').getContext('2d');


            var clavesGasto = [];
            var valoresGasto = [];

            for (let i = 0; i < chartDataG.length; i++) {
                let objeto = chartDataG[i];
                let objetoClaves = Object.keys(objeto);
                let objetoValores = Object.values(objeto);
                let clave = parseInt(objetoClaves[0]) - 1;
                let mes = meses[clave];
                clavesGasto.push(mes);
                valoresGasto.push(objetoValores[0]);
            }


            var gasto = new Chart(ctxG, {
                type: 'bar',
                data: {
                    labels: clavesGasto,
                    datasets: [{
                        label: 'Gasto €',
                        data: valoresGasto,
                        backgroundColor: 'rgba(183, 3, 252, 0.2)',
                        borderColor: 'rgba(183, 3, 252, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    // otras opciones de la gráfica
                }
            });
        }

        const consumo =(chartDataC)=>{
            var ctxLC = document.getElementById('consumo').getContext('2d');


            let clavesConsumo = [];
            let valoresConsumo = [];

            for (let i = 0; i < chartDataC.length; i++) {
                let objeto = chartDataC[i];
                let objetoClaves = Object.keys(objeto);
                let objetoValores = Object.values(objeto);
                let clave = parseInt(objetoClaves[0]) - 1;
                let mes = meses[clave];
                clavesConsumo.push(mes);
                valoresConsumo.push(objetoValores[0]);
            }


            var consumo = new Chart(ctxLC, {
                type: 'bar',
                data: {
                    labels: clavesConsumo,
                    datasets: [{
                        label: 'Consumo',
                        data: valoresConsumo,
                        backgroundColor: 'rgba(225, 225, 3, 0.2)',
                        borderColor: 'rgba(225, 225, 3, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    // otras opciones de la gráfica
                }
            });
        }


        const todo =(chartDataTodo)=>{

            var ctxLTodo = document.getElementById('todo').getContext('2d');

            var clavesTodo = ['Luz','Gas','Agua','Mantenimiento','Otros'];
            var valoresTodo = [chartDataTodo.luz,chartDataTodo.gas,chartDataTodo.agua,chartDataTodo.mantenimiento,chartDataTodo.otros];


            var todo = new Chart(ctxLTodo, {
                type: 'bar',
                data: {
                    labels: clavesTodo,
                    datasets: [{
                        label: 'Gasto anual',
                        data: valoresTodo,
                        backgroundColor: [
                        'rgb(225, 255, 3)',
                        'rgb(37, 252, 3)',
                        'rgb(3, 168, 252)',
                        'rgb(183, 3, 252)',
                        'rgb(252, 3, 80)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    // otras opciones de la gráfica
                }
            });
        }

        const cambioTipo= async() => {

            var tipo = tipo_select.value

            var formdata = new FormData();
            formdata.append('tipo',tipo);
            formdata.append('año',year.value)

            let resul = await axios.post(URL_API+'/gastos/dataset', formdata);

            var parent = document.getElementById('contenedor');
            parent.innerHTML = '';

            parent.innerHTML =`
            <div class="flex flex-col lg:flex-row gap-4 m-4 p-3">
                <div class="w-full lg:w-1/2 flex item-center">
                    <canvas id="consumo"></canvas>
                </div>
                <div class="w-full lg:w-1/2 flex item-center">
                    <canvas id="gasto"></canvas>
                </div>
            </div>
            <div class="flex flex-col gap-4 m-4 p-3">
                <div class="w-full">
                    <p>{{ __('Total cost')}}: <span id="total"></span></p>
                    <canvas id="todo"></canvas>
                </div>


            </div>
            `
            consumo(resul.data[0])
            gasto(resul.data[1])
            todo(resul.data[2])

            let luz = resul.data[2].luz ? resul.data[2].luz : 0;
            let gas = resul.data[2].gas ? resul.data[2].gas : 0;
            let agua = resul.data[2].agua ? resul.data[2].agua : 0;
            let mantenimiento = resul.data[2].mantenimiento ? resul.data[2].mantenimiento : 0;
            let otro = resul.data[2].otro ? resul.data[2].otro : 0;

            var totalGasto = luz+gas+agua+mantenimiento+otro
            console.log(totalGasto);

            total.textContent  = `${totalGasto} €`

        };

        cambioTipo()
    </script>
</x-app-layout>
