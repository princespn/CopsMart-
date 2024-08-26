<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommodityTypeFieldInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('commodity_type_id')->nullable();
        });

        $products = DB::table('products')->join('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
                                ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                                ->select(['products.id', 'categories.commodity_type_id'])->get();
        if(count($products) > 0){
            foreach ($products as $key => $dp) {

                DB::table('products')->where('id', $dp->id)->update(['commodity_type_id' => $dp->commodity_type_id]);
            }
        }

        Schema::table('categories', function (Blueprint $table) {
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
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('commodity_type_id')->nullable();
        });

        $categories = DB::table('products')->join('sub_categoies', 'products.sub_category_id', '=', 'sub_categories.id')
                                ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                                ->select(['categories.id', 'products.commodity_type_id'])->groupBy('categories.id')->get();
        if(count($categories) > 0){
            foreach ($categories as $key => $cat) {

                DB::table('categories')->where('id', $cat->id)->update(['commodity_type_id' => $cat->commodity_type_id]);
            }
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('commodity_type_id');
        });
    }
}
