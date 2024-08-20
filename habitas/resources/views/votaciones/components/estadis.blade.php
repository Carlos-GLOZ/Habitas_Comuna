
@if(!isset($FRAME))
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{asset('js/fontawesome.js')}}"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const URL_API = "{{url('/')}}"
    </script>
@endif
<div id="data_chart" class="w-full flex flex-row flex-wrap gap-3 py-3">

</div>

<script>
    function createBarChart(canvasId, data, options) {
    }
    var label = [];
    var votos = [];
    //var datas = {};
    const getData=async()=>{
        var resul = await axios.get(URL_API+'/votacion/dataset/'+{{$id}});

        if(resul.status === 200){


            resul.data.forEach(pregunta => {
                // console.log(pregunta);
                data_chart.innerHTML +=`
                <div class="w-1/4 mx-auto bg-white shadow-md rounded-md p-4">
                    <h2 class="text-xl font-bold mb-4">${pregunta.pregunta}</h2>
                    <canvas id="Pi_${pregunta.id}" width="400" height="400"></canvas>
                </div>
                `
            });
            resul.data.forEach(pregunta => {
                // console.log(pregunta);
                // data_chart.innerHTML +=`
                //     <div class="">
                //         <h2>${pregunta.pregunta}</h2>
                //         <canvas id="Pi_${pregunta.id}" ></canvas>
                //     </div>
                // `
                label = [];
                votos = [];
                pregunta.opciones.forEach(opcion => {
                    label.push(opcion.opcion);
                    votos.push(opcion.votos);
                });
                data={
                    labels: label,
                    datasets: [{
                        label: pregunta.pregunta,
                        data: votos,
                        hoverOffset: 4
                    }]
                };
                // var config = {
                //     type: 'doughnut',
                //     data,
                // };
                //createBarChart('Pi_'+pregunta.id,data);
                new Chart(document.getElementById('Pi_'+pregunta.id).getContext('2d') , {
                    type: 'doughnut',
                    data: data,
                }).resize(50, 50);
                //new Chart(ctx, config);
            });
        }
    }

    getData()

</script>
