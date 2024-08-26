<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewFieldsInDeliveryPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_people', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->bigInteger('mobile')->after('name');
            $table->string('email')->after('mobile')->nullable();
            $table->dateTime('last_activity')->after('email')->nullable();
            $table->boolean('is_active')->after('last_activity');
            $table->string('driving_license_no')->after('last_activity')->nullable();
            $table->string('aadhar_no')->after('last_activity')->nullable();
            $table->integer('service_area_id')->after('last_activity')->nullable();
            $table->double('lat')->after('last_activity')->nullable();
            $table->double('long')->after('last_activity')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_people', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('mobile');
            $table->dropColumn('email');
            $table->dropColumn('last_activity');
            $table->dropColumn('is_active');
            $table->dropColumn('driving_license_no');
            $table->dropColumn('aadhar_no');
            $table->dropColumn('service_area_id');
            $table->dropColumn('deleted_at');
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
    }
}
