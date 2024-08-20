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
        Schema::create('delegars', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion');
            $table->string('nombre_receptor');

            $table->foreignId('receptor_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('emisor_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('encuesta_id')->constrained('encuestas')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegars');
    }
};
