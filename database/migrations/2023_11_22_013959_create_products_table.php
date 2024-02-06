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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_categories_id');
            $table->foreignId('cylinder_capacities_id');
            $table->foreignId('models_id');
            $table->foreignId('boxes_id');
            $table->foreignId('type_products_id');
            $table->foreignId('brands_id');
            $table->string('name');
            $table->string('description', 100);
            $table->string('code', 45);
            $table->string('image');
            $table->integer('count');
            $table->string('link');
            $table->string('reference', 30);
            $table->text('detail');
            $table->timestamps();
        });

        DB::statement('
            create fulltext index products_name_fulltext
            on products(name);
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
