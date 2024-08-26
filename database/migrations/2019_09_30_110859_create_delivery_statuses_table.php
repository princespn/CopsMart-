<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateDeliveryStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_statuses', function (Blueprint $table) {
           
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('description');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('delivery_statuses')->insert([
            [
                'name' => 'Accept',
                'code' => 'accepted',
                'description' => 'Delivery person Accepted Order'
            ],
            [
                'name' => 'Picked up',
                'code' => 'picked',
                'description' => 'Order Pickup complete'
            ],
            [
                'name' => 'Delivered',
                'code' => 'delivered',
                'description' => 'Order Delivery complete'
            ],
            [
                'name' => 'Cancel Delivery',
                'code' => 'cancel',
                'description' => 'Delivery Cancel Requested'
            ],
            [
                'name' => 'Cancelled',
                'code' => 'canceled',
                'description' => 'Delivery Cancelled'
            ],
           
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_statuses');
    }
}
