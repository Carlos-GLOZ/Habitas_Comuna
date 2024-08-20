<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Contabilidad_modulo;
use App\Models\Modulo;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;




class ModuloController extends Controller
{


    public function moduloview(Request $request)
    {
        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));

        $modulos = Contabilidad_modulo::where('activo',1)->where("comunidad_id", $comunidad->id)->pluck('modulo_id')->toArray();
        $modulosDisabledPayed = Contabilidad_modulo::where("activo",0)->where("comunidad_id", $comunidad->id)->where("fecha_fin", "!=", null)->pluck('modulo_id')->toArray();
        // dd($comunidad);
        return view("user.modulos", compact('modulos'), compact('modulosDisabledPayed'));

    }

    public function PayForm(Request $request){
        $array = ["1" => $request["1"], "2" => $request["2"], "3" => $request["3"], "4" => $request["4"]];
        foreach ($array as $key => $value) {
            if($value == null){
                unset($array[$key]);
            }
        }
        $deleteFromArrayIfIsPayed = Contabilidad_modulo::where("comunidad_id", Session::get("comunidad"))->where("fecha_fin","!=",NULL)->get();
        foreach ($deleteFromArrayIfIsPayed as $modulo){
            unset($array[$modulo->modulo_id]);
        }
        return view("user.formulario_pago", compact('array'));
    }

    public function payModuleFirstMonth(Request $request){
        $array = ["1" => $request["1"], "2" => $request["2"], "3" => $request["3"], "4" => $request["4"]];
        foreach ($array as $key => $value) {
            if($value == null){
                unset($array[$key]);
            }
        }
        $deleteFromArrayIfIsPayed = Contabilidad_modulo::where("comunidad_id", Session::get("comunidad"))->where("fecha_fin","!=",NULL)->get();
        foreach ($deleteFromArrayIfIsPayed as $modulo){
            unset($array[$modulo->modulo_id]);
        }
        $comunidad = comunidad::where("id",Session::get("comunidad"))->first();

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $token = $_POST["stripeToken"];


        $email = $_POST["email"];

        if($comunidad->stripe_user == null){
            $customer = \Stripe\Customer::create([
                'email' => $email,
                'source'  => $token,
            ]);

            $customerId = $customer->id;

            comunidad::where("id",Session::get("comunidad"))->update(["stripe_user" => $customerId, 'correo' => $email]);
        }else{

            $customerId = $comunidad->stripe_user;

            if($comunidad->correo != $email){
                $customer = \Stripe\Customer::update($customerId,[
                    'email' => $email,
                    'source'  => $token,
                ]);

                comunidad::where("id",Session::get("comunidad"))->update(['correo' => $email]);
            }
        }


        $fechaHoy = date("Y-m-d");

        $precio = 0;

        $arrayStripe = [];

        $fechaNueva = null;



        foreach($array as $modulo => $status ){

            $moduloStripe = Modulo::where("id",$modulo)->first();

            array_push($arrayStripe,$moduloStripe);

            $precio = $precio + $moduloStripe->precio;

            $sub = \Stripe\Subscription::create(
                [
                    'customer' => $customerId,
                    'items' => [["price" => $moduloStripe->stripe_id]],
                ]
            );
            $fechaNueva = date('Y-m-d',$sub->current_period_end);
            $infoModule = Contabilidad_modulo::where("comunidad_id", Session::get("comunidad"))->where("modulo_id",$modulo)->count();
            if($infoModule == 1){
                Contabilidad_modulo::where("comunidad_id", Session::get("comunidad"))->where("modulo_id",$modulo)->update(["activo" => 1, "fecha_fin" => $fechaNueva, 'stripe_id' => $sub->id]);
            }else{
                $insertNewModule = new Contabilidad_modulo();
                $insertNewModule->fecha_ini = $fechaHoy;
                $insertNewModule->fecha_fin = $fechaNueva;
                $insertNewModule->activo = 1;
                $insertNewModule->comunidad_id = Session::get("comunidad");
                $insertNewModule->modulo_id = $modulo;
                $insertNewModule->stripe_id = $sub->id;
                $insertNewModule->save();
            }
        }
        if($precio == 0){
            return redirect()->route("modulos2");
        }
        $rutaCarpetaTemp = env("TEMP_FILES_PATH");
        $pdf = Pdf::loadView('user.recibo',[ "arrayStripe" => $arrayStripe, "fechaHoy" => $fechaHoy, "fechaNueva" => $fechaNueva, "email" => $email, "precio" => $precio]);
        $rutaRecibo = $rutaCarpetaTemp.'/recibo_'.$comunidad->meet.'.pdf';
        file_put_contents($rutaRecibo, $pdf->output());
        $this->send_mail($email,$comunidad->meet,$rutaRecibo);
        $IsPay = true;
        return redirect()->route("modulos2",compact("IsPay"));
    }

    public function disableModuloNextMonth(Request $request){


        if(!isset($request["id"]))
            return false;
        $modulo = $request["id"];
        $checkIfIsTrue = Contabilidad_modulo::where("modulo_id",$modulo)->where("comunidad_id","=", Session::get("comunidad"))->first();
        if($checkIfIsTrue->activo==1 && $checkIfIsTrue->fecha_fin != null){
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $subscription = \Stripe\Subscription::retrieve($checkIfIsTrue->stripe_id);
            \Stripe\Subscription::update(
                $subscription->id,
                [
                  'cancel_at_period_end' => true,
                ]
              );
            Contabilidad_modulo::where("modulo_id",$modulo)->where("comunidad_id","=", Session::get("comunidad"))->update(["activo"=>0]);
            return true;
        }else{
            return false;
        }
    }
    public function EnableModulePayed(Request $request){
        if(!isset($request["id"]))
            return false;
        $modulo = $request["id"];
        $checkIfIsTrue = Contabilidad_modulo::where("modulo_id",$modulo)->where("comunidad_id","=", Session::get("comunidad"))->first();
        if($checkIfIsTrue->activo==0 && $checkIfIsTrue->fecha_fin != null){
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $subscription = \Stripe\Subscription::retrieve($checkIfIsTrue->stripe_id);
            \Stripe\Subscription::update(
                $subscription->id,
                [
                  'cancel_at_period_end' => false,
                ]
              );
            Contabilidad_modulo::where("modulo_id",$modulo)->where("comunidad_id","=", Session::get("comunidad"))->update(["activo"=>1]);
            return true;
        }else{
            return false;
        }
    }


    public function RefreshPay(){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $rutaCarpetaTemp = env("TEMP_FILES_PATH");
        $handle = opendir($rutaCarpetaTemp);
        if ($handle) {
            while (($entry = readdir($handle)) !== FALSE) {
                $fileToDelete = $rutaCarpetaTemp."/".$entry;
                try{
                    unlink($fileToDelete);
                }catch(Exception $e){
                    $a = $e;
                }
            }
        }
        $comunidades = comunidad::get();
        $fechaHoy = date("Y-m-d");
        foreach ($comunidades as $comunidad) {
            $presidente = User::find($comunidad->presidente_id);
            App::setLocale($presidente->language);
            $modulosAlmacenados = Contabilidad_modulo::where("comunidad_id", $comunidad->id)->where("fecha_fin",$fechaHoy)->get();
            $arrayStripe = [];
            $email = $comunidad->correo;
            $precio = 0;
            $fechaNueva = null;
            foreach($modulosAlmacenados as $modulo){
                if($modulo->activo == 0){
                    Contabilidad_modulo::where("id",$modulo->id)->update(["fecha_fin" => null, "stripe_id" => null]);
                }else{
                    if(count($arrayStripe) == 0){
                        $subscription = \Stripe\Subscription::retrieve($modulo->stripe_id);
                        $fechaNueva = date("Y-m-d",$subscription->current_period_end);
                    }
                    $moduloASubir = modulo::where("id",$modulo->modulo_id)->first();
                    array_push($arrayStripe,$moduloASubir);
                    $precio += $moduloASubir->precio;
                    Contabilidad_modulo::where("id",$modulo->id)->update(["fecha_fin" => $fechaNueva]);
                }
            }
            if(count($arrayStripe) != 0){
                $presidente =
                $pdf = Pdf::loadView('user.recibo',[ "arrayStripe" => $arrayStripe, "fechaHoy" => $fechaHoy, "fechaNueva" => $fechaNueva, "email" => $email, "precio" => $precio]);
                $rutaRecibo = $rutaCarpetaTemp.'/recibo_'.$comunidad->meet.'.pdf';
                file_put_contents($rutaRecibo, $pdf->output());
                $this->send_mail($email,$comunidad->meet,$rutaRecibo);
            }
        }
        App::setLocale("es");
    }

    public function send_mail($correo,$id,$ruta)
    {
        $data["email"] = $correo;
        $data["title"] = "Recibo ".$id;
        $data["body"] = "Recibo Habitas Comuna";


        Mail::send('user.recibo_email', $data, function($message)use($data, $ruta) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
            $message->attach($ruta);
        });
    }
}
