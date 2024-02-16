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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_stores_id');
            $table->foreignId('users_id');
            $table->foreignId('cities_id');
            $table->string('name', 100)->unique();
            $table->string('description');
            $table->string('email', 45)->unique();
            $table->string('address');
            $table->string('image')->nullable();
            $table->string('image2')->nullable();
            $table->string('RIF', 45);
            $table->string('link');
            $table->boolean('status');
            $table->string('score_store', 10);
            $table->string('phone', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
