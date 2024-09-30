<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->unsignedBigInteger('views')->default(0); // Agregar la columna 'views'
        });
    }
    
    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn('views'); // Eliminar la columna si la migración se revierte
        });
    }
};
