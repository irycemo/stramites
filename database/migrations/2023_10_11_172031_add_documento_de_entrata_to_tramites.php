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
            $table->string('procedencia')->nullable()->after('recibido_por');
            $table->dateTime('fecha_emision')->nullable()->after('recibido_por');
            $table->string('numero_documento')->nullable()->after('recibido_por');
            $table->string('nombre_autoridad')->nullable()->after('recibido_por');
            $table->string('autoridad_cargo')->nullable()->after('recibido_por');
            $table->string('tipo_documento')->nullable()->after('recibido_por');
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
