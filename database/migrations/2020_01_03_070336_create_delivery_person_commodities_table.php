<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryPersonCommoditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_person_commodities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('delivery_person_id')->index();
            $table->integer('commodity_type_id')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        $deliveryPersons = DB::table('delivery_people')->get();
        if(count($deliveryPersons) > 0){
            $insert_data = [];
            foreach ($deliveryPersons as $key => $dp) {
                $insert_data[] = [
                    'delivery_person_id' => $dp->id,
                    'commodity_type_id' => $dp->commodity_type_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
            DB::table('delivery_person_commodities')->insert($insert_data);
        }

        Schema::table('delivery_people', function (Blueprint $table) {
            $table->dropColumn('commodity_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_person_commodities');
        // recreating commodity type field in delivery_people
        Schema::table('delivery_people', function (Blueprint $table) {
            $table->integer('commodity_type_id')->nullable();
        });
    }
}
