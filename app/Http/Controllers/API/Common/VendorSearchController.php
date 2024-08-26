<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class VendorSearchController extends Controller
{
    function suggestion(Request $request, $vendorId){
        $input = $request->input();
        if(!isset($input['query']) ){
            return [];
        }
        $query = $this->buildSearchQuery($vendorId, $input['query']);
        return $query->limit(3)->get();
    }

    function search(Request $request, $vendorId){
        $input = $request->input();
        if(!isset($input['query'])){
            return [];
        }
        $query = $this->buildSearchQuery($vendorId, $input['query']);
        return $query->get();
    }

    private function buildSearchQuery($vendorId, $query){
        return  DB::table('products')->where('products.name', 'like' , '%' . $query. '%')
                    ->join('vendor_products', 'products.id', '=', 'product_id')
                    ->join('packages', 'packages.id', '=', 'package_id')
                    ->join('sub_categories', 'sub_categories.id','=', 'products.sub_category_id')
                    ->where('sub_categories.is_active', 1)
                    ->where('products.is_active',1)
                    ->where('vendor_id', $vendorId)
                    ->select(['vendor_products.*', 'products.*','packages.name as package' ,'vendor_products.id as vendor_product_id']);
    }
}
