<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    
</head>
<style>
    table{
        padding-top: 10px
    }
    table,th,td{
        border-collapse:collapse; border: 1px solid;
    }
    th,td{
        padding: 10px
    }
    .tdPrecio{
        text-align: right;
    }
</style>
    <body style="font-family: sans-serif">
        <div style="float: left;">
            <h3 style="align-items:flex-start; font-size:35px;">{{__("Receipt")}}</h3>
        </div>
        <div style="margin-left: 580px; margin-top:15px;">
                <img width="120px" height="80px" src="{{env("LOGO_FILE_PATH")}}">
        </div>
        <hr>
        <p>{{__("Purchase email")}}: {{$email}}</p>
        <p>{{__("Use date")}}: {{__("From")}} {{$fechaHoy}} {{__("until")}} {{$fechaNueva}}</p>
        <hr>
        <table style="width: 100%;  black;">
            <thead style="background-color: #D8D8D8">
                <tr>
                    <th>{{__("Module")}}</th>
                    <th>{{__("Price")}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($arrayStripe as $item)
                <tr>
                    <td>{{__($item->nombre)}}</td>
                    <td class="tdPrecio">{{$item->precio}}€/{{__("month")}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>{{__("Total")}}</td>
                    <td class="tdPrecio">{{$precio}}€/{{__("month")}}</td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>