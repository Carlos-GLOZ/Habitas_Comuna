<x-appNoNav-layout>
  <script src="https://js.stripe.com/v3/"></script>
  <style>
    @keyframes translate 
    {  
       0% {     transform: translate(0px);   }   
       50% {     transform: translate(-20px);   }   
       100% {     transform: translate(0px);   } 
    } 
    
    @keyframes rotate 
    {  
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    } 

    .btnReturnBack:hover .iconClassIcon {   animation: translate 1s infinite; }

    html,body{
      width: 100%;
      height: 100%;
      font-family: -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
    }
    body{
      margin: 0px;
    }
    .compraChecbox{
      display: none;
    }
    .divTotal{
      width: 100%;
      height: 100%;
      display: flex;
    }
    .divShowInfoPay{
      padding: 75px;
      background: rgb(255, 255, 255);
      border-radius: 10px;
    }
    .divShowInfoMargin{
      width: 300px;
      padding-bottom: 200px;
    }
    .divSubTotal{
      display: flex;
      align-items: center;
      justify-content: center;
      width: 50%;
    }
    .p-4{
      padding: 0 !important;
    }
    .labelClass{
      color:  hsla(0,0%,10%,.7);
      font-size: 13px;
    }
    .checkoutInput{
      width: 100% !important;
      border: 2px solid black !important;
      border-radius: 10px !important;
      border-color: rgba(211, 211, 211, 0.723) !important;
      padding: 10px;
    }
    .submitButton{
      width: 100%;
      border-radius: 10px !important;
      background: rgb(25, 37, 82);
      padding: 10px;
      color: white;
    }
    .centTitleForm{
      display: flex;
      width: 100%;
      text-align: center;
      padding-bottom: 10px;
      justify-content: center;
    }
    .submitButton:hover{
      background: rgb(39, 58, 126);
    }
    .submitButton:active{
      background: rgb(17, 26, 59);
    }
    .divReload{
            background: rgba(136, 136, 136, 0.575);
            width: 100%;
            height: 100%;
            position: absolute;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2;
    }
    .reloadIcon{
      font-size: 150px;
      animation-duration: 1s;
      animation-name: rotate;
      animation-timing-function: linear;
      animation-iteration-count: infinite;
    }
    @media screen and (max-width: 700px){
            .divSubTotal{
                width: 100%; 
            }
            .divTotal{
              flex-wrap: wrap;
            }
            .divShowInfoMargin{
              padding-bottom: 0px;
            }
        }
  </style>
<div id="divReload" class="divReload"><i class="fa-sharp fa-solid fa-circle-notch reloadIcon"></i></div>
<div class="divTotal">
  <div class="divSubTotal" >
    <div class="divShowInfoMargin">
      <form action="{{route("modulos2")}}">
        <button type="submit" class="btnReturnBack"><i class="fa-solid fa-arrow-left iconClassIcon"></i> <b>{{__("Back")}}</b></button>
      </form>
      <br>
      <br>
      <p style="font-size: 30px">{{count($array)*5}}€/{{__("Month")}}</p>
      <br>
      <h3 style="font-size: 22px">{{__("Content")}}</h3>
      <br>
      @foreach($array as $item => $value)
      @if ($item == "1")
          <div style="display:flex;"><div style="display: flex; width:50%;"><p>{{__("Meeting")}}</p></div><div style="display: flex; width:50%; justify-content:flex-end;"><p>5€/{{__("Month")}}</p></div></div>
      @endif
      @if ($item == "2")
      <div style="display:flex;"><div style="display: flex; width:50%;"><p>{{__("Payments")}}</p></div><div style="display: flex; width:50%; justify-content:flex-end;"><p>5€/{{__("Month")}}</p></div></div>
      @endif
      @if ($item == "3")
      <div style="display:flex;"><div style="display: flex; width:50%;"><p>{{__("President chat")}}</p></div><div style="display: flex; width:50%; justify-content:flex-end;"><p>5€/{{__("Month")}}</p></div></div>
      @endif
      @if ($item == "4")
      <div style="display:flex;"><div style="display: flex; width:50%;"><p>{{__("Polls")}}</p></div><div style="display: flex; width:50%; justify-content:flex-end;"><p>5€/{{__("Month")}}</p></div></div>
      @endif
      <br>
    @endforeach
    </div>
  </div>
  <div class="divSubTotal" style="background:white;">
    <form style="width: 300px" action="{{route("payModuleFirstMonth")}}" method="post" id="payment-form">
      <div class="centTitleForm">
        <h3 style="font-size: 22px">{{__("Payment form")}}</h3>
      </div>
      @foreach ($array as $item => $value)
          <input type="checkbox" name="{{$item}}" class="compraChecbox" checked>
      @endforeach
      @csrf
      <div>
        <label class="labelClass" for="email">{{__("Email")}}</label>
        <br>
        <input class="checkoutInput" type="email" id="email" name="email" required>
      </div>
      <br>
      <div>
        <label class="labelClass" for="card-element">{{__("Card information")}}</label>
        <br>
        <div class="checkoutInput" id="card-element">
        </div>
      </div>
      <div id="card-errors" role="alert"></div>
      <br>
      <button class="submitButton shadow-lg" type="submit">{{__("Pay")}}</button>
    </form>
  </div>
</div>

  <script>


    var stripe = Stripe('{{env("STRIPE_KEY")}}');

  
    var elements = stripe.elements({
      locale: '{{Auth::user()->language}}' // Cambia 'es' por el código de idioma que desees utilizar
    });
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');


    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      var email = document.getElementById('email').value;

      stripe.createToken(cardElement).then(function (result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                document.getElementById("divReload").style.display = "flex";
                // Agregar el token al formulario y enviarlo al controlador
                var tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = 'stripeToken';
                tokenInput.value = result.token.id;
                form.appendChild(tokenInput);

                form.submit();
            }
        });
    });
  </script>
</x-appNoNav-layout>
