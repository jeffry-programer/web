<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('from', 600)->change(); // Cambia a 255 caracteres
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('from', 255)->change(); // Reemplaza 100 con el tamaño anterior si era diferente
        });
    }
};
