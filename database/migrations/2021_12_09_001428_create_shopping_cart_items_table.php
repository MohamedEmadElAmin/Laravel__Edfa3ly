<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_cart_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('quantity')->unsigned()->default(1);
            $table->decimal('original_price')->unsigned()->default(0);
            $table->decimal('discount_price')->unsigned()->default(0);
            $table->decimal('original_shipping_fees')->unsigned()->default(0);


            $table->foreignId('product_id')
                ->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('shopping_cart_id')
                ->references('id')->on('shopping_carts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_cart_items');
    }
}
