<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->decimal('amount' ,8,2);
            $table->date('date');
            $table->integer('order_status_id');
            $table->dateTime('status_updated');
            $table->boolean('payment_status')->nullable();
            $table->dateTime('scheduled_delivery_date');
            $table->integer('vendor_id');
            $table->integer('vendor_status_id')->nullable();
            $table->integer('delivery_person_id')->nullable();
            $table->integer('delivery_status_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
