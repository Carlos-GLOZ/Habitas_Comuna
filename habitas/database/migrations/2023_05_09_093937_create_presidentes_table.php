<?php

use Carbon\Carbon;
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
        Schema::create('presidentes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo',['presidente','vicepresidente'])->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('comunidad_id')->constrained('comunidads')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->date('fecha_ini')->default(Carbon::now()->format('Y-m-d'))->nullable();
            $table->date('fecha_fin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presidentes');
    }
};
