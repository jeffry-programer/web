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
        Schema::table('renovations', function (Blueprint $table) {
            $table->string('comment_admin')->nullable();
            $table->string('status_renovation')->nullable()->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('renovations', function (Blueprint $table) {
            $table->dropColumn('comment_admin');
            $table->dropColumn('status_renovation');
        });
    }
};
