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
        Schema::table('comments_services', function (Blueprint $table) {
            $table->unsignedBigInteger('rate')->nullable()->default(0); // Agregar la columna 'views'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments_services', function (Blueprint $table) {
            $table->unsignedBigInteger('rate')->nullable()->default(0); // Agregar la columna 'views'
        });
    }
};
