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
        Schema::table('tramites', function (Blueprint $table) {
            $table->foreignId('documento_recibido_por')->nullable()->references('id')->on('users')->after('recibido_por');
            $table->date("fecha_documento_recibido")->nullable()->after('fecha_entrega');
            $table->unsignedInteger('usuario')->after('numero_control');

            $table->dropUnique('tramites_año_numero_control_unique');

            $table->unique(['año', 'numero_control', 'usuario']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            //
        });
    }
};
