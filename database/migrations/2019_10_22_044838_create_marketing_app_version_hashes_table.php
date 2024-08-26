<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketingAppVersionHashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_app_version_hashes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('version_code')->unique();
            $table->string('hash');
            $table->timestamps();
        });

        DB::table('marketing_app_version_hashes')->insert([
            'version_code' => '1',
            'hash' => '1-Afdsa',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketing_app_version_hashes');
    }
}
