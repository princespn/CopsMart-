<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\VendorProduct;
use App\Vendor;
use App\SubCategory;
use DB;
class CustomerSearchController extends Controller
{
    function suggestion(Request $request){
        $input = $request->input();
        if(!isset($input['query']) || !isset($input['vendor'])){
           
            return [];
        }
        $vendor = $input['vendor'];
        $qparam = [];

        if(!empty($input['vendor'])){
            array_push($qparam, ['vendor_products.vendor_id','=',$input['vendor']]);
        }
        else{
            if($vendor>0)
            {
                array_push($qparam, ['vendor_products.is_active','=',1]);
            }
            array_push($qparam, ['products.is_active','=',1]);
        }


        
        /*$results = DB::table('products')->where('products.name', 'like' , '%' .$input['query'] . '%')
                    ->join('vendor_products', 'products.id', '=', 'product_id')
                    ->join('sub_categories', 'sub_categories.id','=', 'products.sub_category_id')
                    ->where($qparam)
                    ->limit(3)
                    ->select(['vendor_products.*', 'products.*', 'vendor_products.id as vendor_product_id'])->get();
        return $results;*/
       
        $products = VendorProduct::with(['product_package'=> function($query) use ($vendor){
            if($vendor>0)
            {
                return $query->where('vendor_products.vendor_id',$vendor)->where('vendor_products.is_active',1);
               
            }
            else{
                return $query->where('products.is_active',1);
            }
          
        }])
        ->join('products', 'products.id', '=', 'vendor_products.product_id')
        ->leftjoin('packages','vendor_products.package_id','=','packages.id')
        ->where('products.is_active', 1)
        ->where('products.name', 'like' , '%' .$input['query'] . '%')
        ->limit(20)
        ->select(['*','vendor_products.id as vendor_product_id','products.id as product_id','products.name as product_name','vendor_products.is_active as is_active', 'vendor_products.is_featured as is_featured','packages.name as package_name'])
        ->groupBy('vendor_products.product_id');
        
        $products = $products->where($qparam)->get();
        
        return $products;
    }

    function search(Request $request){
        $input = $request->input();
        if(!isset($input['query']) || !isset($input['vendor'])){
            return [];
        }
        $vendor = $input['vendor'];
        /*$results = DB::table('products')->where('products.name', 'like' , '%' .$input['query'] . '%')
                    ->join('vendor_products', 'products.id', '=', 'product_id')
                    ->join('sub_categories', 'sub_categories.id','=', 'products.sub_category_id')
                    ->where('sub_categories.is_active', 1)
                    ->where('vendor_products.is_active',1)
                    ->where('products.is_active',1)
                    ->where('vendor_id', $input['vendor'])
                    ->select(['vendor_products.*', 'products.*', 'vendor_products.id as vendor_product_id'])->get();
        return $results;*/
        
        $validator = \Validator::make($request->all(), ['start_limit'=>'required|numeric','end_limit'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }

        
        $start_limit= $request->start_limit;
        $end_limit= $request->end_limit;
       
            $products = VendorProduct::with(['product_package'=> function($query) use ($vendor){
              if($vendor>0)
                {
                    return $query->where('vendor_products.vendor_id',$vendor);
                }
                else{
                    return $query->where('vendor_products.is_active',1);
                }
            }])
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->leftjoin('packages','vendor_products.package_id','=','packages.id')
            ->where('products.is_active', 1)
            ->where('products.name', 'like' , '%' .$input['query'] . '%')
            ->select(['*','vendor_products.id as vendor_product_id','products.id as product_id','products.name as product_name','vendor_products.is_active as is_active', 'vendor_products.is_featured as is_featured','packages.name as package_name'])
            ->groupBy('vendor_products.product_id')
            ->skip($start_limit)->take($end_limit)
            ->get();
        
        return $products;
    }

    function globalSearch(Request $request){
        $input = $request->input();

        $validator = \Validator::make($request->all(), ['latitude'=>'required|numeric','longitude'=>'required|numeric','search_string'=>'required','start_limit'=>'required|numeric','end_limit'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }

        $latitude = $request->latitude;
        $longitude= $request->longitude;
        //$category_id= $request->category_id;
        $start_limit= $request->start_limit;
        $end_limit= $request->end_limit;

        $products = VendorProduct::selectRaw("*,vendors.name as vendor_name,vendors.service_range,vendor_products.id as vendor_product_id,products.id as product_id,products.name as product_name,vendors.is_active as is_active,vendor_products.is_featured as is_featured,
                         ( 6371 * acos( cos( radians(?) ) *
                           cos( radians( latitude ) )
                           * cos( radians( longitude) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( latitude ) ) )
                         ) AS distance", [$latitude, $longitude, $latitude])
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->join('vendors', 'vendors.id', '=', 'vendor_products.vendor_id')
            ->where('products.is_active', 1)
            //->where('products.category_id', $category_id)
            ->where('products.name', 'like' , '%' .$request->search_string . '%')
            ->groupBy('vendor_products.product_id')
            ->groupBy('vendor_products.vendor_id')
            ->havingRaw('distance <= vendors.service_range/1000')
            ->orderBy("distance",'asc')
            ->skip($start_limit)->take($end_limit)
            ->get();
        
        return $products;
        
        
    }
}
