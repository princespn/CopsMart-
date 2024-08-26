<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('admin_contact_numbers')->nullable();
            $table->string('customer_support_email')->nullable();
            $table->string('orders_email')->nullable();
            $table->string('vendor_support_email')->nullable();
            $table->string('delivery_support_email')->nullable();
            $table->string('marketing_support_email')->nullable();
            $table->string('customer_app_min_version')->nullable();
            $table->string('vendor_app_min_version')->nullable();
            $table->string('delivery_app_min_version')->nullable();
            $table->string('marketing_app_min_version')->nullable();
            $table->timestamps();
        });

        DB::table('global_settings')->insert([
            'admin_contact_numbers' => '9827641944',
            'vendor_app_min_version' => 1,
            'customer_app_min_version' => 1,
            'delivery_app_min_version' => 1,
            'marketing_app_min_version' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_settings');
    }
}
