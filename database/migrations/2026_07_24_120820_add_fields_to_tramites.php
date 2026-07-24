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
            $table->string('numero_autoridad')->nullable()->after('numero_documento');
            $table->index(['año', 'numero_control', 'usuario']);
            $table->index('estado');
            $table->index('linea_de_captura');
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
