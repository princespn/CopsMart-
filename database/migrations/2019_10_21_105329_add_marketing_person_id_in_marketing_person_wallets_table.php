<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarketingPersonIdInMarketingPersonWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketing_person_wallets', function (Blueprint $table) {
            $table->integer('marketing_person_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketing_person_wallets', function (Blueprint $table) {
            $table->dropColumn('marketing_person_id');
        });
    }
}
