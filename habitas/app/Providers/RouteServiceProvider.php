<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/menu';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {



        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));


            Route::middleware('web')
                ->prefix('google')
                ->group(base_path('routes/custom/Google.php'));

            Route::middleware('web')
                ->prefix('error')
                ->group(base_path('routes/custom/Error.php'));


            Route::middleware(['web','auth','back'])
                ->prefix('menu')
                ->group(base_path('routes/custom/Menu.php'));

            Route::middleware(['web','auth'],'back')
                ->prefix('user')
                ->group(base_path('routes/custom/User.php'));

            Route::middleware(['web','admin','auth','back'])
                ->prefix('admin')
                ->group(base_path('routes/custom/Admin.php'));


            Route::middleware(['web','auth','verifyComunidad','emailCheck','Meet','back'])
                ->prefix('meeting')
                ->group(base_path('routes/custom/Meeting.php'));

            Route::middleware(['web','auth','verifyComunidad','emailCheck','Votacion','back'])
                ->prefix('votacion')
                ->group(base_path('routes/custom/Votaciones.php'));

            Route::middleware(['web','auth','verifyComunidad','emailCheck','back'])
                ->prefix('modulos')
                ->group(base_path('routes/custom/Modulos.php'));
            Route::middleware(['api','back'])
                ->prefix('apiPagos')
                ->group(base_path('routes/custom/ApiPagos.php'));

            Route::middleware(['web','auth','verifyComunidad','emailCheck','back'])
                ->prefix('anuncios')
                ->group(base_path('routes/custom/Anuncios.php'));

            Route::middleware(['web','auth','verifyComunidad','emailCheck','back'])
                ->prefix('incidencias')
                ->group(base_path('routes/custom/Incidencias.php'));

            Route::middleware(['web','auth','verifyComunidad','emailCheck','ChatPresi','back'])
                ->prefix('chatPresidente')
                ->group(base_path('routes/custom/ChatPresidente.php'));

            Route::middleware(['web','auth','verifyComunidad','emailCheck','back'])
                ->prefix('vecinos')
                ->group(base_path('routes/custom/Vecinos.php'));

            Route::middleware(['web','auth','verifyComunidad','emailCheck','back'])
                ->prefix('presidente')
                ->group(base_path('routes/custom/Presidente.php'));

            Route::middleware(['web', 'auth','verifyComunidad','emailCheck','back'])
                ->prefix('calendario')
                ->group(base_path('routes/custom/Calendario.php'));

            Route::middleware(['web', 'auth','verifyComunidad','emailCheck'])
                ->prefix('adjuntos')
                ->group(base_path('routes/custom/Adjuntos.php'));

            Route::middleware(['web', 'auth','verifyComunidad','emailCheck','back'])
                ->prefix('servicios')
                ->group(base_path('routes/custom/Servicios.php'));

            Route::middleware(['web', 'auth','verifyComunidad','emailCheck', 'Pagos','back'])
                ->prefix('gastos')
                ->group(base_path('routes/custom/Gastos.php'));

            Route::middleware(['web', 'auth','verifyComunidad','emailCheck','back'])
                ->prefix('seguros')
                ->group(base_path('routes/custom/Seguros.php'));
        });

    }
}
