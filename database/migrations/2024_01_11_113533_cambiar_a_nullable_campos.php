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
            $table->string('fecha_entrega')->nullable()->change();
            $table->dateTime('limite_de_pago')->nullable()->change();
            $table->string('linea_de_captura')->nullable()->change();
            $table->string('orden_de_pago')->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
