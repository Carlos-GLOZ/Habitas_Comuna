<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\Comunidad;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use TheSeer\Tokenizer\Exception;

class ArchivoController extends Controller
{
    public function index()
    {
        if(Session::has("comunidad")){
            $archivos = Archivo::where("comunidad_id",Session::get("comunidad"))->get();
            return view("user.adjuntos", compact('archivos'));
        }else{
            return back();
        }
    }

    public function getMenuAdmin(){
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                $archivos = Archivo::where("comunidad_id",Session::get("comunidad"))->get();
                return view("presidente.gestion_adjuntos", compact('archivos'));
            }
        }else{
            return redirect("dashboard");
        }
    }

    public function addNewAdjunto(Request $request){
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                if(!empty($request["nombre"]) && !empty($request->file("archivo"))){
                    $extension = $request->file('archivo')->getClientOriginalExtension();
                    $type = $request->file("archivo")->getMimeType();
                    $typeCheck = explode("/", $type);
                    if($extension == "pdf" || $extension == "docx" || $extension == "pptx" || $extension == "xlsx" || $typeCheck[0] == "image" || $typeCheck[0] == "video"){
                        try{
                            DB::beginTransaction();
                            $archivo = Archivo::create(['nombre' => $request["nombre"],'tipo' => $type, 'extension' => $extension, 'comunidad_id' => Session::get("comunidad")]);
                            $ruta = "/".'comunidades/'.Session::get("comunidad");
                            $archivo = $archivo->id."_".Session::get("comunidad").".".$extension;
                            $request->file("archivo")->storeAs($ruta,$archivo);
                            DB::commit();
                            return "OK";
                        }catch(Exception $e){
                            DB::rollBack();
                            return "error";
                        }
                    }else{
                        return "TypeError";
                    }
                }
                //Vacio;
                return back();
            }
            //No presi;
            return false;
        }
        return false;
        //No sesion;
    }

    public function deleteAdjunto(Request $request){
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                if(!empty($request["id"]) && !empty($request["archivo"])){
                    try{
                        DB::beginTransaction();
                        $ruta = env("PRIVATE_FILES_PATH")."/".'comunidades/'.Session::get("comunidad").$request["archivo"];
                        Archivo::where("id", $request["id"])->delete();
                        unlink($ruta);
                        DB::commit();
                        return true;
                    }catch(Exception $e){
                        DB::rollBack();
                    }
                }
                return back();
                //Vacio
            }
            return false;
            //No presi
        }
        //No sesion
        return false;
    }

    public function getDataAdjunto(Request $request, $id)
    {
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                if(!empty($request["id"])){
                    return Archivo::find($id);
                }
            }
            //No presi
            return false;
        }
        //No sesion
        return null;
    }

    public function updateDataAdjunto(Request $request){
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                if(!empty($request["nombre"]) && !empty($request["id"])){
                    if(!empty($request->file("archivo"))){
                        $extension = $request->file('archivo')->getClientOriginalExtension();
                        $type = $request->file("archivo")->getMimeType();
                        $typeCheck = explode("/", $type);
                        if($extension == "pdf" || $extension == "docx" || $extension == "pptx" || $extension == "xlsx" || $typeCheck[0] == "image" || $typeCheck[0] == "video"){
                            try{
                                DB::beginTransaction();
                                $rutaOld = env("PRIVATE_FILES_PATH")."/".'comunidades/'.Session::get("comunidad").$request["archivoOld"];
                                unlink($rutaOld);
                                Archivo::where("id",$request["id"])->update(['nombre' => $request["nombre"],'tipo' => $type,'extension' => $extension]);
                                $ruta = "/".'comunidades/'.Session::get("comunidad");
                                $archivo = $request["id"]."_".Session::get("comunidad").".".$extension;
                                $request->file('archivo')->storeAs($ruta,$archivo);
                                DB::commit();
                                return true;
                            }catch(Exception $e){
                                DB::rollBack();
                                echo $e;
                                return false;
                            }
                        }else{

                        }
                    }else{
                        Archivo::where("id",$request["id"])->update(['nombre' => $request["nombre"]]);
                        return true;
                    }
                }else{
                    //Vacio;
                    return false;
                }
            }
            //No presi;
            return false;
        }
        return false;
        //No sesion;
    }

    public function getShowDataAdjunto(){
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                $archivos = Archivo::where("comunidad_id",Session::get("comunidad"))->get();
                $table = "";
                foreach($archivos as $archivo){
                    $tipoArchivo = "";
                    $verArchivo  = "";
                    if($archivo->extension == "pdf" || explode("/", $archivo->tipo)[0] == "image" || explode("/", $archivo->tipo)[0] == "video"){
                        $verArchivo = "<a type='button' class='ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500 margin-responsive-button' href='".asset("adjuntos/seeArchiveInNavigator/$archivo->id"."_"."$archivo->comunidad_id".".$archivo->extension/$archivo->id")."' target='_blank'><i class='fa-solid fa-eye' style='color: #000000;'></i></a>";
                    }
                    if($archivo->extension == "pdf"){
                        $tipoArchivo = "<img class='icon_img' src='../images/icons/pdf.png' alt='pdf'>";
                    }
                    elseif($archivo->extension == "xlsx"){
                        $tipoArchivo = "<img class='icon_img' src='../images/icons/excel.png' alt='excel'>";
                    }
                    elseif($archivo->extension == "docx"){
                        $tipoArchivo = "<img class='icon_img' src='../images/icons/word.png' alt='docx'>";
                    }
                    elseif($archivo->extension == "pptx"){
                        $tipoArchivo = "<img class='icon_img' src='../images/icons/pwp.png' alt='pptx'>";
                    }
                    elseif(explode("/", $archivo->tipo)[0] == "image"){
                    $tipoArchivo = "<img class='icon_img' src='../images/icons/image.webp' alt='image'>";
                    }
                    elseif(explode("/", $archivo->tipo)[0] == "video"){
                        $tipoArchivo = "<img class='icon_img' src='../images/icons/video.webp' alt='video'>";
                    }
                    $ruta = "/".$archivo->id."_".$archivo->comunidad_id.".".$archivo->extension;
                    $table = $table."<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'><td class='px-10 py-4 text-center'><div class='flex justify-center flex-wrap'>".
                    $tipoArchivo."&nbsp;".$archivo->nombre."</div></td><td class='px-6 py-4 text-center'>".
                    $verArchivo."<a type='button' class='ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500' href='".asset("adjuntos/descargarArchivoPrivado/$archivo->id"."_"."$archivo->comunidad_id".".$archivo->extension")."'><i class='fa-solid fa-download' style='color: #000000;'></i></a>
                    <td class='px-6 py-4 text-center'>
                    <button class='ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500 margin-responsive-button' onclick=modificaradjunto(".$archivo->id.",'".$ruta."',this)><i class='fa-solid fa-pen-to-square' style='color: #000000;'></i></button>
                    <button class='ml-2 px-5 py-2 bg-slate-200 rounded text-white transition-all duration-200 hover:bg-slate-500' onclick=eliminaradjunto($archivo->id,'".$ruta."')><i class='fa-solid fa-trash' style='color: #000000;'></i></button>
                    </td></tr>";
                }
                return $table;
            }
            //No presi
            return false;
        }
        //No sesion
        return null;
    }

    public function descargarArchivoPrivado(Request $request,$archivo)
    {
        if(!file_exists(env("PRIVATE_FILES_PATH")."/comunidades/".Session::get("comunidad")."/".$archivo)){
            abort(404);
        }
        return Storage::disk('private')->download("comunidades/".Session::get("comunidad")."/".$archivo);
    }

    public function seeArchiveInNavigator(Request $request, $archivo, $id){
        $nombreArchivo = $archivo;
        $archivoInfo = Archivo::find($id);
        $tipo = $archivoInfo->tipo;
        $rutaArchivo = env("PRIVATE_FILES_PATH")."/comunidades/".Session::get("comunidad")."/".$archivo;
        if (file_exists($rutaArchivo)) {
            header('Content-Type: '.$tipo);
            header('Content-Disposition: inline; filename="' . $nombreArchivo . '"');
            header('Content-Length: ' . filesize($rutaArchivo));

            readfile($rutaArchivo);
            exit;
        } else {
            echo 'El archivo no existe.';
        }
    }
}
