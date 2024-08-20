<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comunidad;
use App\Models\Modulo;
use App\Models\User;
use App\Models\Usuario_comunidad;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // Super Admins

        User::create([
            'name' => 'Sergio',
            'apellidos' => 'TEST T',
            'password' => Hash::make('qweQWE123'),
            'email' => 'sergio.comsace@gmail.com',
            'super_admin' => true,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);
        User::create([
            'name' => 'Erik',
            'apellidos' => 'TEST T',
            'password' => Hash::make('qweQWE123'),
            'email' => 'erikrm02@gmail.com',
            'super_admin' => true,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);
        User::create([
            'name' => 'Carlos',
            'apellidos' => 'TEST T',
            'password' => Hash::make('qweQWE123'),
            'email' => 'carlos@gmail.com',
            'super_admin' => true,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);

        User::create([
            'name' => 'fabrica',
            'apellidos' => 'TEST T',
            'password' => Hash::make('qweQWE123'),
            'email' => 'fabrizio282002@gmail.com',
            'super_admin' => true,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);

        User::create([
            'name' => 'alex',
            'apellidos' => 'TEST T',
            'password' => Hash::make('qweQWE123'),
            'email' => '100007906.joan23@fje.edu',
            'super_admin' => true,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);

        // LQSA

        User::create([
            'name' => 'Antonio',
            'apellidos' => 'Recio',
            'password' => Hash::make('qweQWE123'),
            'email' => 'antonio_recio@gmail.com',
            'super_admin' => false,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);

        User::create([
            'name' => 'Amador',
            'apellidos' => 'Rivas',
            'password' => Hash::make('qweQWE123'),
            'email' => 'amador_rivas@gmail.com',
            'super_admin' => false,
            'language' => 'en',
            'email_verified_at' => str(Carbon::now())
        ]);

        User::create([
            'name' => 'Enrique',
            'apellidos' => 'Pastor',
            'password' => Hash::make('qweQWE123'),
            'email' => 'enrique_pastor@gmail.com',
            'super_admin' => false,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);

        User::create([
            'name' => 'Berta',
            'apellidos' => 'Escobar',
            'password' => Hash::make('qweQWE123'),
            'email' => 'berta_escobar@gmail.com',
            'super_admin' => false,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);

        User::create([
            'name' => 'Maite',
            'apellidos' => 'Figueroa',
            'password' => Hash::make('qweQWE123'),
            'email' => 'maite_figueroa@gmail.com',
            'super_admin' => false,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);

        User::create([
            'name' => 'Judith',
            'apellidos' => 'Becker',
            'password' => Hash::make('qweQWE123'),
            'email' => 'judith_becker@gmail.com',
            'super_admin' => false,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);
        User::create([
            'name' => 'Javier',
            'apellidos' => 'Maroto',
            'password' => Hash::make('qweQWE123'),
            'email' => 'javier_maroto@gmail.com',
            'super_admin' => false,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);
        User::create([
            'name' => 'Lola',
            'apellidos' => 'Trujillo',
            'password' => Hash::make('qweQWE123'),
            'email' => 'lola_trujillo@gmail.com',
            'super_admin' => false,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);
        User::create([
            'name' => 'Fermín',
            'apellidos' => 'Trujillo',
            'password' => Hash::make('qweQWE123'),
            'email' => 'fermin_trujillo@gmail.com',
            'super_admin' => false,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);
        User::create([
            'name' => 'Bruno',
            'apellidos' => 'Quiroga',
            'password' => Hash::make('qweQWE123'),
            'email' => 'bruno_quiroga@gmail.com',
            'super_admin' => false,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);
        User::create([
            'name' => 'Vicente',
            'apellidos' => 'Maroto',
            'password' => Hash::make('qweQWE123'),
            'email' => 'vicente_maroto@gmail.com',
            'super_admin' => false,
            'language' => 'es',
            'email_verified_at' => str(Carbon::now())
        ]);

        // Comunidad

        Comunidad::create([
            "nombre" => "Mirador de Montepinar",
            "codigo" => "LQSA",
            "correo" => "100007906.joan23@fje.edu",
            "meet" => "611d7ebb-3b6c-41cd-8b2d-2cbb3876ee11",
            "presidente_id" => 6,
            "vicepresidente_id" => 8
        ]);

        // Usuarios comunidad

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 6,
        ]);

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 7,
        ]);

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 8,
        ]);

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 9,
        ]);

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 10,
        ]);

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 11,
        ]);

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 12,
        ]);

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 13,
        ]);

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 14,
        ]);

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 15,
        ]);

        Usuario_comunidad::create([
            "comunidad_id" => 1,
            "user_id" => 16,
        ]);




        // Modulos

        Modulo::create([
            'nombre' => 'Meeting',
            'precio' => 5,
            'stripe_id' => 'price_1N5V1WFqR3vB4XhGQw02cBSG',
        ]);

        Modulo::create([
            'nombre' => 'Payments',
            'precio' => 5,
            'stripe_id' => 'price_1N5UlaFqR3vB4XhGfcalmdhy',
        ]);

        Modulo::create([
            'nombre' => 'President chat',
            'precio' => 5,
            'stripe_id' => 'price_1N5V2LFqR3vB4XhGPMyIgNKE',
        ]);

        Modulo::create([
            'nombre' => 'Polls',
            'precio' => 5,
            'stripe_id' => 'price_1N5V25FqR3vB4XhGXxW6nHoJ',
        ]);
    }
}
