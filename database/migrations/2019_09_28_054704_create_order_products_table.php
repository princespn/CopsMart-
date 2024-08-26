<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->index();
            $table->bigInteger('product_id')->index();
            $table->bigInteger('vendor_product_id');
            $table->string('name');
            $table->integer('qty');
            $table->float('price');
            $table->float('mrp')->nullable();
            $table->float('cost_price')->nullable();
            $table->float('discount')->nullable();
            $table->float('tax')->nullable();
            $table->float('final_price')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
