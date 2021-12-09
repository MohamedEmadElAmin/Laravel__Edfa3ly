<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_conditions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('can_be_activated_multiple_times');
            $table->integer('min_bought_items')->unsigned();
            $table->foreignId('bought_category_id')->nullable()->references('id')->on('product_categories')->onDelete('set null');
            $table->foreignId('bought_product_id')->nullable()->references('id')->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_conditions');
    }
}
