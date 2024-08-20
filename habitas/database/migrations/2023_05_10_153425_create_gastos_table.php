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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comunidad_id')->constrained('comunidads')->onDelete('cascade')->onUpdate('cascade');
            $table->text('descripcion');
            $table->enum('tipo',['luz','gas','agua','mantenimiento','otros']);
            $table->float('gasto',20,2);
            $table->float('cantidad',20,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
