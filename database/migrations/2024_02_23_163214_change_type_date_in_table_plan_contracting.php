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
        Schema::table('plan_contractings', function (Blueprint $table) {
            $table->date('date_init')->change();
            $table->date('date_end')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_contractings', function (Blueprint $table) {
            $table->timestamp('date_init')->change();
            $table->timestamp('date_end')->change();
        });
    }
};
