<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seguros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('num_polizas');
            $table->date('fecha_fin');
            $table->string('web');
            $table->string('correo');
            $table->string('tel');
            $table->string('direccion');
            $table->string('cuota');
            
            $table->foreignId('comunidad_id')->constrained('comunidads')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguros');
    }
};
