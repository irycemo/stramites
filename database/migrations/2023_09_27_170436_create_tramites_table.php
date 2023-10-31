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
        Schema::create('tramites', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->unsignedInteger('numero_control')->unique();
            $table->foreignId("id_servicio")->references('id')->on('servicios');
            $table->string("solicitante");
            $table->string("nombre_solicitante")->nullable();
            $table->string("numero_oficio")->nullable();
            $table->string("folio_real")->nullable();
            $table->string("tomo")->nullable();
            $table->string("tomo_gravamen")->nullable();
            $table->boolean("tomo_bis")->nullable();
            $table->string("registro")->nullable();
            $table->string("registro_gravamen")->nullable();
            $table->boolean("registro_bis")->nullable();
            $table->string("distrito")->nullable();
            $table->string("seccion")->nullable();
            $table->unsignedDecimal("monto", 18, 2);
            $table->string("tipo_servicio");
            $table->unsignedInteger('numero_paginas')->nullable();
            $table->unsignedInteger('numero_inmuebles')->nullable();
            $table->string("numero_propiedad")->nullable();
            $table->string("numero_escritura")->nullable();
            $table->string("numero_notaria")->nullable();
            $table->string("nombre_notario")->nullable();
            $table->string("valor_propiedad")->nullable();
            $table->boolean("foraneo")->default(false)->nullable();
            $table->timestamp('reingreso')->nullable();
            $table->timestamp('fecha_prelacion')->nullable();
            $table->date("fecha_entrega");
            $table->date("fecha_pago")->nullable();
            $table->date("limite_de_pago");
            $table->string('orden_de_pago');
            $table->string('documento_de_pago')->nullable();
            $table->string('linea_de_captura');
            $table->unsignedBigInteger('movimiento_registral')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('adiciona')->nullable()->references('id')->on('tramites');
            $table->foreignId('creado_por')->nullable()->references('id')->on('users');
            $table->foreignId('actualizado_por')->nullable()->references('id')->on('users');
            $table->foreignId('recibido_por')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramites');
    }
};
