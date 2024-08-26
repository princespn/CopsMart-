<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_payment_id')->index();
            $table->bigInteger('payment_method_id')->index();
            $table->bigInteger('payment_status_id');
            $table->float('amount');
            $table->string('transaction_uid');
            $table->boolean('status')->nullable()->default(null);
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
        Schema::dropIfExists('order_payment_methods');
    }
}
