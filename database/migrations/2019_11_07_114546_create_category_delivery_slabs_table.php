<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryDeliverySlabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_delivery_slabs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category_id')->index()->nullable();
            $table->string('type')->nullable();
            $table->float('limit_start')->nullable()->default(0);
            $table->float('limit_end')->nullable();
            $table->float('charges')->nullable()->default(0);
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
        Schema::dropIfExists('category_delivery_slabs');
    }
}
