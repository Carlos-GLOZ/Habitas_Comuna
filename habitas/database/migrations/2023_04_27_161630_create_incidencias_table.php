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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('estado')->default(1)->nullable();
            $table->string('titulo');
            $table->text('descripcion');
            
            $table->foreignId('comunidad_id')->constrained('comunidads')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('autor_id')->constrained('users')->nullable()->onDelete('no action')->onUpdate('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
