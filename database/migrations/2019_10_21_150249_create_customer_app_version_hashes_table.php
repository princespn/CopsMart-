<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAppVersionHashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_app_version_hashes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('version_code')->unique();
            $table->string('hash');
            $table->timestamps();
        });

        DB::table('customer_app_version_hashes')->insert([
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
        Schema::dropIfExists('customer_app_version_hashes');
    }
}
