<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal('shipping_fees')->nullable()->unsigned()->default(0);
            $table->decimal('subtotal')->nullable()->unsigned()->default(0);
            $table->decimal('vat')->nullable()->unsigned()->default(0);
            $table->decimal('total')->nullable()->unsigned()->default(0);
            $table->decimal('discount_shipping_fees')->nullable()->default(0);

            $table->decimal('shipping_fees_wo_offers')->nullable()->unsigned()->default(0);
            $table->decimal('subtotal_wo_offers')->nullable()->unsigned()->default(0);
            $table->decimal('vat_wo_offers')->nullable()->unsigned()->default(0);
            $table->decimal('total_wo_offers')->nullable()->unsigned()->default(0);

            $table->foreignId('user_id')
                ->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_carts');
    }
}
