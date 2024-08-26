<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('description');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('order_statuses')->insert([
            [
            'name' => 'Pending',
            'code' => 'created',
            'description' => 'Pending for payment'
            ],
            [
            'name' => 'Payment failed',
            'code' => 'failed',
            'description' => 'Payment failed'
            ],
            [
            'name' => 'Cancelled',
            'code' => 'cancelled',
            'description' => 'Order Cancelled'
            ],
            [
            'name' => 'Placed',
            'code' => 'placed',
            'description' => 'Order Placed'
            ],
            [
            'name' => 'Order Confirmed',
            'code' => 'confirmed',
            'description' => 'Order confirmed by partner'
            ],
            [
            'name' => 'Order Ready',
            'code' => 'ready',
            'description' => 'Order ready to be delivered'
            ],
            [
            'name' => 'Out For Delivery',
            'code' => 'out_for_delivery',
            'description' => 'Order out for delivery'
            ],
            [
            'name' => 'Delivery Reattempt Requested',
            'code' => 'reattempt',
            'description' => 'Order delivery will be reattempted'
            ],
            [
            'name' => 'Delivered',
            'code' => 'delivered',
            'description' => 'Order delivered successfully'
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
        Schema::dropIfExists('order_statuses');
    }
}
