<x-app-layout>
    @if(isset($_GET["IsPay"]))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{__("Thank you for your purchase, the receipt has been sent to your email")}}',
            })
        </script>
    @endif
    <style>
        .stripe-button-el{
            display: none;
        }
        #inputSubmit{
            cursor: pointer;
        }
        #inputSubmit:disabled{
            background: #2a577c;
            cursor:not-allowed;
        }
        .priceStyle{
            padding-top: 10px;
            font-size: 20px;
        }
    </style>
    <div>
        <div class="pb-4">
            <div class="modulos justify-around flex-col lg:flex-row bg-white p-8 rounded-xl " >
                <div style="padding-bottom:40px"><h2 class="text-2xl text-center">{{__("Payment information")}}</h2></div>
                
                <div style="padding-bottom:40px" class="flex w-full">
                    <div style="width:50%;" class="text-center"><b>{{__("Current subscription price")}} 
                        <div class="priceStyle"><span id="actualSubPrice">{{count($modulos)*5}}</span>€/{{__("month")}}</b></div></div>
                    <div style="width:50%;" class="text-center"><b>{{__("Price of the new content")}}
                        <div class="priceStyle"><span id="newSubPrice">0</span>€/{{__("month")}}</b></div></div>
                </div>
                
                <div style="font-size: 13px">
                    <div>{{__("The individual price of each module is 5€/month")}}</div>
                    <div>{{__("The content will be charged monthly from the day of purchase, if you want not to be charged you must desactivate it")}}</div>
                    <div>{{__("If a content is in red, it means that it is disabled for the next payment, but it can be used until the end of the paid subscription")}}</div>
                </div>
            </div>
            <br>
            <form action="{{route("PayForm")}}" method="POST">
                @csrf
                <div class="modulos flex justify-around flex-col lg:flex-row bg-white p-8 rounded-xl " >

                    <div class="social">
                        <h2 class="text-4xl" style="padding-bottom: 20px">{{__("Social")}}</h2>

                        @if (in_array('1', $modulos))
                            <div class="meeting" style="display: flex; justify-content:space-between; padding:10px">
                                <h3 id="1_h3">{{__("Meeting")}}</h3>
                                <label class="switch-a">
                                    <input type="checkbox" name="1" checked onchange="disablePayed()">
                                    <span class="slider round"></span>
                                </label>
                                {{--  --}}
                            </div>
                        @else
                            @if (in_array('1', $modulosDisabledPayed))
                            <div class="meeting" style="display: flex; justify-content:space-between; padding:10px">
                                <h3 id="1_h3" style="color:red;">{{__("Meeting")}}</h3>
                                <label class="switch-a">
                                    <input type="checkbox" name="1" onchange="enabledPayed()">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            @else
                            <div class="meeting" style="display: flex; justify-content:space-between; padding:10px">
                                <h3 id="1_h3">{{__("Meeting")}}</h3>
                                <label class="switch-a">
                                    <input type="checkbox" name="1">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            @endif
                        @endif
            
                        @if (in_array('3', $modulos))
                        <div class="chat_presidente" style="display: flex; justify-content:space-between; padding:10px">
                            <h3 id="3_h3">{{__("President chat")}}</h3>
                            <label class="switch-a">
                                <input type="checkbox" name="3" checked onchange="disablePayed()">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        @else
                        @if (in_array('3', $modulosDisabledPayed))
                        <div class="chat_presidente" style="display: flex; justify-content:space-between; padding:10px">
                            <h3 id="3_h3" style="color:Red;">{{__("President chat")}}</h3>
                            <label class="switch-a">
                                <input type="checkbox" name="3" onchange="enabledPayed()">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        @else
                        <div class="chat_presidente" style="display: flex; justify-content:space-between; padding:10px">
                            <h3 id="3_h3">{{__("President chat")}}</h3>
                            <label class="switch-a">
                                <input type="checkbox" name="3">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        @endif
            
                        @endif
            
            
                    </div>
            
                    <div class="comunidad">
                        <h2 class="text-4xl" style="padding-bottom: 20px">{{__("Community")}}</h2>
            
                        @if (in_array('2', $modulos))
                        <div class="pagos" style="display: flex; justify-content:space-between; padding:10px">
                            <h3 id="2_h3">{{__("Payments")}}</h3>
                            <label class="switch-a">
                                <input type="checkbox" name="2" checked onchange="disablePayed()">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        @else
                        @if (in_array('2', $modulosDisabledPayed))
                        <div class="pagos" style="display: flex; justify-content:space-between; padding:10px">
                            <h3 id="2_h3" style="color:red;">{{__("Payments")}}</h3>
                            <label class="switch-a">
                                <input type="checkbox" name="2" onchange="enabledPayed()">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        @else        
                        <div class="pagos" style="display: flex; justify-content:space-between; padding:10px">
                            <h3 id="2_h3">{{__("Payments")}}</h3>
                            <label class="switch-a">
                                <input type="checkbox" name="2">
                                <span class="slider round"></span>
                            </label>
                        </div>
                            
                        @endif
            
                        @endif
            
                        @if (in_array('4', $modulos))
                        <div class="votaciones" style="display: flex; justify-content:space-between; padding:10px">
                            <h3 id="4_h3">{{__("Polls")}}</h3>
                            <label class="switch-a">
                                <input type="checkbox" name="4" checked onchange="disablePayed()">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        @else
                            @if (in_array('4', $modulosDisabledPayed))
                            <div class="votaciones" style="display: flex; justify-content:space-between; padding:10px">
                                <h3 id="4_h3" style="color:red;">{{__("Polls")}}</h3>
                                <label class="switch-a">
                                    <input type="checkbox" name="4" onchange="enabledPayed()">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            @else
                            <div class="votaciones" style="display: flex; justify-content:space-between; padding:10px">
                                <h3 id="4_h3">{{__("Polls")}}</h3>
                                <label class="switch-a">
                                    <input type="checkbox" name="4">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            @endif
            
                        @endif
            
                        <div class="pagar" style=" display:flex; margin-top: 20px; justify-content: flex-end;">
            
                            <input id="inputSubmit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" type="submit" value="{{__("Send")}}" disabled="disabled">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>

        .switch-a {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        }

        .switch-a input { 
        opacity: 0;
        width: 0;
        height: 0;
        }

        .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        }

        .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        }

        input:checked + .slider {
        background-color: #2196F3;
        }

        input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
        border-radius: 34px;
        }

        .slider.round:before {
        border-radius: 50%;
        }


        @media screen and (max-width:1145px) {



        }
  </style>

  <script>

    var precioIndividual = 5 
    var precioActual = 0
    var precioNuevo = 0
    var arrayModules = []
    var arrayNotSeted = []
    
    window.addEventListener("load", function(){
        cantidad = {{count($modulos)}}
        precioActual = precioIndividual*cantidad
        arrayModules = @json($modulos);
        arrayNotSeted = @json($modulosDisabledPayed);
        document.getElementsByName("1")[0].addEventListener("change", changeNewStatus);
        document.getElementsByName("2")[0].addEventListener("change", changeNewStatus);
        document.getElementsByName("3")[0].addEventListener("change", changeNewStatus);
        document.getElementsByName("4")[0].addEventListener("change", changeNewStatus);
    })



    function changeNewStatus(){
        modulo = event.srcElement.name;
        if(arrayModules.includes(parseInt(modulo)) || arrayNotSeted.includes(parseInt(modulo))){
            if(event.srcElement.checked){
                precioActual += precioIndividual
            }else{
                precioActual -= precioIndividual
            }
        }else{
            if(event.srcElement.checked){
                precioNuevo += precioIndividual
                precioActual += precioIndividual
            }else{
                precioNuevo -= precioIndividual
                precioActual -= precioIndividual
            }
            document.getElementById("newSubPrice").innerHTML = precioNuevo
            if(precioNuevo != 0){
                document.getElementById("inputSubmit").disabled = false
            }else{
                document.getElementById("inputSubmit").disabled = true
            }
        }
        document.getElementById("actualSubPrice").innerHTML = precioActual
    }


    const enabledPayed = async() => {
        id = event.srcElement.name
        form = new FormData();
        form.append("id",id)
        let resul = await axios.post(URL_API+'/modulos/EnableModulePayed', form);
        if(resul.data == true){
            input = document.getElementsByName(id)[0].onchange = disablePayed 
            document.getElementById(id+"_h3").style.color = "black"
        }
    }

    const disablePayed = async() => {
        id = event.srcElement.name
        form = new FormData();
        form.append("id",id)
        let resul = await axios.post(URL_API+'/modulos/disableModule', form);
        if(resul.data == true){
            input = document.getElementsByName(id)[0].onchange = enabledPayed 
            document.getElementById(id+"_h3").style.color = "red"
        }
    }
  </script>

</x-app-layout>
