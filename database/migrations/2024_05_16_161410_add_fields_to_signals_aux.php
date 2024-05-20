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
        Schema::table('signals_aux', function (Blueprint $table) {
            $table->boolean('status')->after('detail');
            $table->boolean('read')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signals_aux', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('read');
        });
    }
};
