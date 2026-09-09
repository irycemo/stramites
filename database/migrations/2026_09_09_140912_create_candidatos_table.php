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
        Schema::create('candidatos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('distrito');
            $table->unsignedInteger('tomo');
            $table->string('tomo_bis')->nullable();
            $table->unsignedInteger('registro');
            $table->string('registro_bis')->nullable();
            $table->unsignedInteger('numero_propiedad');
            $table->timestamps();

            $table->index(['distrito', 'tomo', 'tomo_bis', 'registro', 'registro_bis', 'numero_propiedad'], 'candidato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatos');
    }
};
