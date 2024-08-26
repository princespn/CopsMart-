<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use DB;
class CartController extends Controller
{
    public function sync(Request $request){
        $rules =[];
        $rules['vendor_id'] = 'required|numeric';
        $rules ['items'] = 'required';
        $this->validate($request, $rules);

        $items= json_decode($request->items);
        $dat=[];
        if($request->vendor_id>0)
        {
            $products = DB::table('vendor_products')
            ->join('vendors', 'vendors.id', '=', 'vendor_products.vendor_id')
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->join('product_packages', 'product_packages.product_id', '=', 'vendor_products.product_id')
            ->join('packages', 'packages.id', '=', 'product_packages.package_id')
            ->where('vendor_products.vendor_id', $request->vendor_id)
            //->where('vendor_products.is_active', 1)
            ->where('products.is_active', 1)
            //->where('vendors.is_active', 1)
            ->select(['*','vendors.is_active as vendor_status', 'vendor_products.id as vendor_product_id','packages.name as package_name','products.id as product_id','vendor_products.is_active as product_status','products.name as product_name' ])
            ->where(function($query) use ($items, $request)
            {
                $query->where([['vendor_products.id','=',$items[0]->vendor_product_id],['packages.name','=',$items[0]->pckg_name]]);
                foreach ($items as $key => $value) {
                    if($key > 0){
                        $query->orWhere([['vendor_products.id','=',$value->vendor_product_id],['packages.name','=',$value->pckg_name]]);
                        
                    }
                }

            })->get();
            $i=0;
            foreach($products as $keey)
            {    
                // echo 
                // echo $items[$i]->is_selected_size;
                if(isset($items[$i]->is_selected_size))
                {
                 $keey->is_selected_size=$items[$i]->is_selected_size;
                }
                else
                {
                    $keey->is_selected_size='';
                }
                
                $dat[]=$keey;
                 $i++;
            }
           
        }
        else{

            $products_val = Product::join('packages', 'packages.id', '=', 'products.package')->select('products.*','packages.name as package_name','products.name as product_name')
            ->where('products.is_active', 1)
            ->where(function($query) use ($items, $request)
            {
                $inIds = [];
                foreach ($items as $key => $value) {
                    $inIds[] = $value->product_id;
                }

                $query->whereIn('products.id',$inIds);

            })->get();

            $products = [];
             $i=0;
             $dat=[];
            foreach($products_val as $value){
                $value['vendor_id'] = 0;
                $value['product_id'] = $value->id;
                $value['package_id'] = $value->package;
                $value['price'] = $value->product_selling_price;
                $value['mrp'] = $value->product_cost_price;
                $value['cost_price'] = $value->product_cost_price;
                $value['offer_price'] = 0;
                $value['offer_status'] = 0;
                $value['daily_needs'] = 0;
                $value['deleted_at'] = date('Y-m-d H:i:s');
                $value['vendor_product_id'] = 0;
                if(isset($items[$i]->is_selected_size))
                {
                 $value['is_selected_size']=$items[$i]->is_selected_size;
                }
                else
                {
                    $value['is_selected_size']='';
                }
                array_push($dat,$value);
            }

        }
        return $dat;
    }
    
    public function admin_sync(Request $request){
        $rules =[];
        $rules['admin_service_area_id'] = 'required|numeric';
        $rules ['items'] = 'required';
        $this->validate($request, $rules);

        $items= json_decode($request->items);
        
            $products = DB::table('admin_service_area_products')
            ->join('admin_service_areas', 'admin_service_areas.id', '=', 'admin_service_area_products.admin_service_area_id')
            ->join('admin_products', 'admin_products.id', '=', 'admin_service_area_products.product_id')
            ->join('admin_product_packages', 'admin_product_packages.product_id', '=', 'admin_service_area_products.product_id')
            ->join('packages', 'packages.id', '=', 'admin_product_packages.package_id')
            ->where('admin_service_area_products.admin_service_area_id', $request->admin_service_area_id)
            ->where('admin_products.is_active', 1)
            ->select(['*','admin_service_areas.is_active as service_area_status', 'admin_service_area_products.id as service_area_product_id','packages.name as package_name','admin_products.id as product_id','admin_service_area_products.is_active as product_status','admin_products.name as product_name' ])
            ->where(function($query) use ($items, $request)
            {
                $query->where([['admin_service_area_products.id','=',$items[0]->admin_service_area_product_id],['packages.name','=',$items[0]->pckg_name]]);
                foreach ($items as $key => $value) {
                    if($key > 0){
                        $query->orWhere([['admin_service_area_products.id','=',$value->admin_service_area_product_id],['packages.name','=',$value->pckg_name]]);
                        
                    }
                }

            })->get();

        

            /*$products = [];
            foreach($products_val as $value){
                $value['vendor_id'] = 0;
                $value['product_id'] = $value->id;
                $value['package_id'] = $value->package;
                $value['price'] = $value->product_selling_price;
                $value['mrp'] = $value->product_cost_price;
                $value['cost_price'] = $value->product_cost_price;
                $value['offer_price'] = 0;
                $value['offer_status'] = 0;
                $value['daily_needs'] = 0;
                $value['deleted_at'] = date('Y-m-d H:i:s');
                $value['vendor_product_id'] = 0;
                array_push($products,$value);
            }*/
        return $products;
    }
}
