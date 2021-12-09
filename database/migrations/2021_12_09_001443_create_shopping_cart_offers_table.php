<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_cart_offers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('quantity')->unsigned()->default(1);
            $table->foreignId('offer_id')
                ->references('id')->on('offers')->onDelete('cascade');
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
        Schema::dropIfExists('shopping_cart_offers');
    }
}
