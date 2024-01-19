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
        Schema::create('publicities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stores_id');
            $table->foreignId('type_publicities_id');
            $table->string('image');
            $table->string('description', 45);
            $table->string('link');
            $table->boolean('status');
            $table->timestamp('date_init')->nullable();
            $table->timestamp('date_end')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicities');
    }
};
