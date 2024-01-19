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
        Schema::create('plan_contractings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plans_id');
            $table->foreignId('stores_id');
            $table->timestamp('date_init')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_contractings');
    }
};
