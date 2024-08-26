<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketingPersonWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_person_wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount',8,2);
            $table->string('description');
            $table->boolean('is_collectable')->nullable()->default(false);
            $table->boolean('is_adjustment')->nullable()->default(false);
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
        Schema::dropIfExists('marketing_person_wallets');
    }
}
