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
        Schema::create('transicions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tomo');
            $table->unsignedInteger('registro');
            $table->unsignedInteger('numero_propiedad');
            $table->unsignedInteger('distrito');
            $table->string('seccion');
            $table->unsignedInteger('numero_control');
            $table->string('servicio');
            $table->text('observaciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transicions');
    }
};
