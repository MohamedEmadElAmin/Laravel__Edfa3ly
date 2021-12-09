<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_discounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal('discount_percentage')->unsigned()->nullable();
            $table->decimal('discount_fixed')->unsigned()->nullable();
            $table->string('discount_form')->nullable();
            $table->foreignId('discount_category_id')->nullable()->references('id')
                ->on('product_categories')->onDelete('set null');
            $table->foreignId('discount_product_id')->nullable()->references('id')
                ->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_discounts');
    }
}
