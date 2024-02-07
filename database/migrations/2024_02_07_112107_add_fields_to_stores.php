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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('capacidad', 20)->nullable();
            $table->string('tipo', 50)->nullable();
            $table->string('dimensiones', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('capacidad');
            $table->dropColumn('tipo');
            $table->dropColumn('dimensiones');
        });
    }
};
