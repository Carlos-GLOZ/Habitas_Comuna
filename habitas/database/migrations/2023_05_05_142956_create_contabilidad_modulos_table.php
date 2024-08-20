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
        Schema::create('contabilidad_modulos', function (Blueprint $table) {
            $table->id();
            $table->date("fecha_ini");
            $table->date("fecha_fin")->nullable();
            $table->boolean("activo");
            $table->string("stripe_id")->nullable();

            $table->foreignId('comunidad_id')->constrained('comunidads')->onDelete('no action')->onUpdate('cascade');
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('no action')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Contabilidad_modulos');
    }
};
