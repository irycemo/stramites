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
            $table->unsignedBigInteger('asiento_registral')->nullable()->after('movimiento_registral');
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
