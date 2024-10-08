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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profiles_id')->default(1);
            $table->string('name', 100);
            $table->string('last_name', 45)->nullable();
            $table->string('email')->unique();
            $table->foreignId('cities_id')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 14)->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('image', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
