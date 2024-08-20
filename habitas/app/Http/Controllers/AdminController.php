<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Filesystem\Filesystem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\CurrentTeam;

class AdminController extends Controller
{
    public function index(){
        $filesystem = new Filesystem;
        $folderPrivate = 0;
        $folderStorage = 0;

        $eActivas = Encuesta::where('estado',1)->count();
        $eEspera = Encuesta::where('estado',0)->count();
        $eFinalizadas = Encuesta::where('estado',2)->count();

        $usuarios = User::count();
        $usuariosNoVal = User::whereNull('email_verified_at')->count();
        $usuariosVal = User::whereNotNull('email_verified_at')->count();

        $modulos = Modulo::count();


        return view('super_admin.admin_view', compact('eActivas', 'eEspera', 'eFinalizadas','usuarios','usuariosNoVal','usuariosVal','folderPrivate','folderStorage','modulos'));
    }

    public function reboot(){

        if(env('APP_ENV') =='local'){


            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $table_array = get_object_vars($table);
                DB::statement('DROP TABLE IF EXISTS '.$table_array[key($table_array)]);
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            Artisan::call('migrate');
            Artisan::call('db:seed');
            Artisan::call('storage:link');

            return redirect('menu');

        }else{
            return abort('500');
        }
   }


   public function start(){

    // if(env('APP_ENV') =='local'){


        Artisan::call('migrate', [
            '--force' => true
         ]);
        Artisan::call('db:seed', [
            '--force' => true
         ]);
        Artisan::call('storage:link', [
            '--force' => true
         ]);

        return 'ok';


    }
}
