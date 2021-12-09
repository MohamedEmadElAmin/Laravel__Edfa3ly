<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->unique();
            $table->decimal('price')->unsigned();
            $table->decimal('weight_kg')->unsigned();
            $table->decimal('shipping_fees')->unsigned();
            $table->foreignId('shipped_from_country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('product_categories')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
