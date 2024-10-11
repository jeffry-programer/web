<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRatesTable extends Migration
{
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->decimal('rate', 10, 4); // Almacenar el tipo de cambio con 4 decimales
            $table->timestamps(); // Esto crea las columnas created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('exchange_rates');
    }
}
