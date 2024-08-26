<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPaymentMethodResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payment_method_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_payment_method_id')->index();
            $table->bigInteger('payment_method_id')->index();
            $table->bigInteger('payment_status_id')->nullable();
            $table->float('amount');
            $table->string('transaction_uid')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_txn_id')->nullable();
            $table->string('response_object');
            $table->string('currency')->nullable();
            $table->string('gateway_name')->nullable();
            $table->string('response_msg')->nullable();
            $table->string('payment_mode')->nullable();
            $table->boolean('status')->nullable()->default(null);
            $table->string('status_code')->nullable()->default(null);
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
        Schema::dropIfExists('order_payment_method_responses');
    }
}
