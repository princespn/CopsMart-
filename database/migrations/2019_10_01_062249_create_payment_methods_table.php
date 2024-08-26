<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('is_postpaid')->nullable()->default(false);
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();
        });

        
        DB::table('payment_methods')->insert([
                'name' => 'Cash on Delivery',
                'code' => 'cod',
                'is_postpaid' => true
        ]);
        DB::table('payment_methods')->insert([
            'name' => 'PAYTM',
            'code' => 'paytm',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
