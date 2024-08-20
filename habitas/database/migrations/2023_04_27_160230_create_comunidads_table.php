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
        Schema::create('comunidads', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo');
            $table->uuid('meet');
            $table->string("correo")->nullable();
            $table->string("stripe_user")->nullable();

            // $table->unsignedBigInteger('creador_id')->nullable();
            $table->unsignedBigInteger('presidente_id');
            $table->unsignedBigInteger('vicepresidente_id')->nullable();
            // $table->foreign('creador_id')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
            $table->foreign('presidente_id')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
            $table->foreign('vicepresidente_id')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');

            // $table->foreignId('creador_id')->constrained('users')->nullable()->onDelete('no action')->onUpdate('cascade');
            // $table->foreignId('presidente_id')->constrained('users')->nullable()->onDelete('no action')->onUpdate('cascade');
            // $table->foreignId('vicepresidente_id')->constrained('users')->nullable()->onDelete('no action')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunidads');
    }
};
