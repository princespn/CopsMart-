<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackedRecordInDeliveryStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('delivery_statuses')->insert([
            [
                'name' => 'Packed',
                'code' => 'packed',
                'description' => 'Order Ready to be picked up'
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
        DB::table('delivery_statuses')->where('code', 'packed')->delete();
    }
}
