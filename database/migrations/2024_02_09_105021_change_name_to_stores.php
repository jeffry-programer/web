<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stores', function () {
            DB::statement('
                create fulltext index stores_name_fulltext
                on stores(name);
            ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
