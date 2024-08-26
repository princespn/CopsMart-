<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('description');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('vendor_statuses')->insert([
            [
            'name' => 'Accepted',
            'code' => 'accepted',
            'description' => 'Vendor Accepted Order'
            ],
            [
            'name' => 'Rejected',
            'code' => 'rejected',
            'description' => 'Vendor Accepted Order'
            ],
            [
            'name' => 'Ready',
            'code' => 'ready',
            'description' => 'Order Ready for Pickup'
            ],
            [
            'name' => 'Picked up',
            'code' => 'picked',
            'description' => 'Order Pickup complete'
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
        Schema::dropIfExists('vendor_statuses');
    }
}
