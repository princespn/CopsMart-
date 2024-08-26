<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VendorProduct;
use App\ProductImage;
use App\Product;
use DB;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class VendorProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listByVendor($vendorId){
        return VendorProduct::with('product')->where(['vendor_id'=>$vendorId])->get();
    }



    function dataTable(Request $request, $vendorId){
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');

        $query = VendorProduct::leftJoin('products', 'products.id','=', 'vendor_products.product_id')->leftJoin('packages', 'packages.id', '=', 'vendor_products.package_id')->where(['vendor_id'=>$vendorId])->select(['vendor_products.*', 'products.name', 'products.package', 'products.image','packages.name as package_name','vendor_products.package_id'])->dataTableQuery($column, $orderBy, $searchValue);
        if($searchValue!=''){
            $query->where('products.name', "LIKE", "%$searchValue%");
        }

        $data = $query->paginate($length);

        return new DataTableCollectionResource($data);
    }


    public function index($vendorid)
    {
        return ['message'=> 'Hello '.$vendorid];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' =>  'required|numeric|unique:vendor_products,product_id,NULL,NULL,vendor_id,' . $request->vendor_id,
            'vendor_id' => 'required|numeric|unique:vendor_products,vendor_id,NULL,NULL,product_id,' . $request->product_id,
            'price' => 'required|numeric',
            'offer_price' => 'numeric',
            'mrp' => 'nullable|numeric',
            'cost_price' => 'required|numeric',
        ]);
        $data = $request->only('product_id', 'vendor_id', 'price', 'mrp', 'cost_price','offer_price','offer_status');
        return VendorProduct::create($data);
    }

    // mfor adding multiple products at once
    public function createMultipleVendorProducts(Request $request, $id)
    {
        $rules =[];
        // foreach ($request->items as $key => $value) {
        //     $rules['items.'.$key.'.product_id'] = 'required|numeric|unique:vendor_products,product_id,NULL,NULL,vendor_id,' . $value['vendor_id'];
        //     $rules['items.'.$key.'.vendor_id'] = 'required|numeric|unique:vendor_products,vendor_id,NULL,NULL,product_id,' . $value['product_id'];
        //     $rules['items.'.$key.'.price'] = 'required|numeric';
        //     $rules['items.'.$key.'.mrp'] = 'nullable|numeric';
        //     $rules['items.'.$key.'.cost_price'] = 'required|numeric';
        // }
        // $this->validate($request, $rules);
        // foreach ($request->items as $key => $value) {
        //     $value['is_active'] = 1;
        //     VendorProduct::create($value);
        // }
        $product_array = [];
        $vendor_product_list = VendorProduct::where('vendor_id',$id)->get();
        foreach ($vendor_product_list as $key => $value) {
            $product_array[$value->product_id][$value->package_id] = $value->id;
        }
        foreach ($request->items as $key => $value) {
           if(isset($product_array[$value['product_id']][$value['package_id']])){
            $vendor_product =  new VendorProduct();
            $vendor_product =  $vendor_product->find($product_array[$value['product_id']][$value['package_id']]);
            $vendor_product->vendor_id = $id;
            $vendor_product->product_id = $value['product_id'];
            $vendor_product->package_id = $value['package_id'];
            $vendor_product->price = $value['price'];
            $vendor_product->mrp = $value['mrp'];
            $vendor_product->cost_price = $value['cost_price'];
            $vendor_product->offer_price = '0';
            $vendor_product->offer_status = '0';
            $vendor_product->update();
            
           }else{
            $vendor_product =  new VendorProduct();
            $vendor_product->vendor_id = $id;
            $vendor_product->product_id = $value['product_id'];
            $vendor_product->package_id = $value['package_id'];
            $vendor_product->price = $value['price'];
            $vendor_product->mrp = $value['mrp'];
            $vendor_product->cost_price = $value['cost_price'];
            $vendor_product->offer_price = '0';
            $vendor_product->offer_status = '0';
            $vendor_product->save();

           }
        }
        return $request->items;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return VendorProduct::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'product_id' =>  'sometimes|numeric',
            'vendor_id' => 'sometimes|numeric',
            'price' => 'sometimes|numeric',
            'mrp' => 'nullable|numeric',
            'cost_price' => 'sometimes|numeric',
            'offer_price' => 'sometimes|numeric',
            'is_active'=> 'sometimes|nullable|boolean'
        ]);
        $request->daily_needs = isset($request->daily_needs)?'1':'0';
        $data = $request->only('product_id', 'vendor_id', 'price', 'mrp', 'cost_price','offer_price','offer_status','daily_needs', 'is_active','package_id');
       
        $vendorProduct = VendorProduct::findorFail($id);

        return [ 'success' => $vendorProduct->update($data) ? true : false ];
    }

    public function updateForVendor(Request $request, $vendorId, $id){
        $vendorProduct = VendorProduct::findOrFail($id);

        if($vendorProduct->vendor_id != $vendorId){
            return [ 'success' => false, 'message' => 'product does not belong to this vendor' ];
        }

        $this->validate($request, [
            'price' => 'sometimes|numeric',
            'mrp' => 'sometimes|numeric',
            'cost_price' => 'sometimes|numeric',
            'offer_price' => 'sometimes|numeric',
            'is_active'=> 'sometimes|nullable|boolean',
            'is_featured'=> 'sometimes|nullable|boolean'
        ]);
        $data = $request->only( 'price', 'mrp', 'cost_price','offer_status', 'offer_price','is_active', 'is_featured');

        return [ 'success' => $vendorProduct->update($data) ? true : false, 'message' => 'product updated'  ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendorProduct = VendorProduct::findOrFail($id);
        return [
            'success' => $vendorProduct->delete()
        ];
    }

    public function getVendorByCategoryAndLocation(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|numeric',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
        ]);

        $getRange = $this->getCoordinatesRange($request->lat,$request->long, 12000);

        $vendor = DB::table('vendors')
                ->where('vendors.category_id', $request->category_id)
                ->where('vendors.open', 1)
                ->where('vendors.is_active', 1)
                ->select('vendors.*')
                ->whereBetween('latitude', [$getRange['min_lat'], $getRange['max_lat']])
                ->whereBetween('longitude', [$getRange['min_long'], $getRange['max_long']])
                ->first();
        $sub_category=null;
        if(isset($vendor->id)){
            $sub_category= DB::table('vendor_products')
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->join('sub_categories', 'sub_categories.id','=', 'products.sub_category_id')
            ->where('vendor_products.vendor_id', $vendor->id)
            ->where('sub_categories.category_id', $request->category_id)
            ->where('sub_categories.is_active', 1)
            ->select('sub_categories.*')
            ->groupBy('sub_categories.id')
            ->get();
        }


        $data['vendor'] = $vendor;
        $data['subCategories'] = $sub_category;
        return $data;

    }

    private function getCoordinatesRange($lat,$long, $diameter){
        // number of km per degree = ~111km (111.32 in google maps, but range varies
        //between 110.567km at the equator and 111.699km at the poles)
        // 1km in degree = 1 / 111.32km = 0.0089
        // 1m in degree = 0.0089 / 1000 = 0.0000089
        $coef = ($diameter/2) * 0.0000089;

        $min_lat = $lat - $coef;
        $max_lat = $lat + $coef;

        // pi / 180 = 0.018
        $min_long = $long - $coef / cos($lat * 0.018);
        $max_long = $long + $coef / cos($lat * 0.018);

        return [
            'min_lat' =>$min_lat,
            'max_lat' =>$max_lat,
            'min_long'=>$min_long,
            'max_long' =>$max_long
        ];
    }

    public function productsByVendor(Request $request,$vendor){

        $validator = \Validator::make($request->all(), ['sup_sub_category_id'=>'required|numeric','start_limit'=>'required|numeric','end_limit'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
     
        $sup_sub_category_id = $request->sup_sub_category_id;
        $sub_category_id = $request->sub_category_id;
        $co_sub_category_id  = $request->co_sub_category_id;
        $start_limit= $request->start_limit;
        $end_limit= $request->end_limit;

        $products = VendorProduct::with(['product_package'=> function($query) use ($vendor){
              return $query->where('vendor_products.vendor_id',$vendor);
        }])
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->leftjoin('packages','vendor_products.package_id','=','packages.id')
            ->where('products.is_active', 1)
            ->where('vendor_products.vendor_id', $vendor)
            ->select(['*','vendor_products.id as vendor_product_id','products.id as product_id','products.name as product_name','vendor_products.is_active as is_active', 'vendor_products.is_featured as is_featured','packages.name as package_name'])
            ->groupBy('vendor_products.product_id');
       
        $qparam = [];
        if($sup_sub_category_id){
            array_push($qparam, ['products.sup_sub_category_id','=',$sup_sub_category_id]);
        }
        if($sub_category_id){
            array_push($qparam, ['products.sub_category_id','=',$sub_category_id]);
        }
        if($co_sub_category_id){
            array_push($qparam, ['products.co_sub_category_id','=',$co_sub_category_id]);
        }
        
        $products = $products->where($qparam)->skip($start_limit)->take($end_limit)->get();
        return $products;
    }


    /*public function productsByVendorAll($vendor){
       
        $sub_category = isset($_GET['sub_cat']) &&is_numeric($_GET['sub_cat']) ? $_GET['sub_cat'] :null;
        
        $products = VendorProduct::with(['product_package'=> function($query) use ($vendor){
              return $query->where('vendor_products.vendor_id',$vendor);
            }])
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->leftjoin('packages','vendor_products.package_id','=','packages.id')
            ->where('products.is_active', 1)
            ->where('vendor_products.vendor_id', $vendor)
            ->select(['*','vendor_products.id as vendor_product_id','products.id as product_id','products.name as product_name','vendor_products.is_active as is_active', 'vendor_products.is_featured as is_featured','packages.name as package_name'])
            ->groupBy('vendor_products.product_id');
       
        $qparam = [];
        if($sub_category){
            array_push($qparam, ['products.sub_category_id','=',$sub_category]);
        }
        
        $products = $products->where($qparam)->get();
        return $products;
       
    }*/
    
    public function productsByVendorAll($vendor){
       
        $sub_category = isset($_GET['sub_cat']) &&is_numeric($_GET['sub_cat']) ? $_GET['sub_cat'] :null;
        
       // print_r("hi");exit;
        $products = VendorProduct::leftjoin('products', 'products.id', '=', 'vendor_products.product_id')
            ->join('packages','packages.id','=','vendor_products.package_id')
            ->where('products.is_active', 1)
            ->where('vendor_products.vendor_id', $vendor)
            ->select(['*','vendor_products.id as vendor_product_id','products.id as product_id','products.name as product_name','vendor_products.is_active as is_active', 'vendor_products.is_featured as is_featured','packages.name as package_name'])
            //->groupBy('vendor_products.package_id')
            ->orderBy('products.name');
       
        $qparam = [];
        if($sub_category){
            array_push($qparam, ['products.sub_category_id','=',$sub_category]);
        }
        
        $products = $products->where($qparam)->get();
        return $products;
       
    }

    public function VendorSubCategories($vendorId){
        return $sub_category= DB::table('vendor_products')
        ->join('products', 'products.id', '=', 'vendor_products.product_id')
        ->join('sub_categories', 'sub_categories.id','=', 'products.sub_category_id')
        ->where('vendor_products.vendor_id', $vendorId)
        ->where('sub_categories.is_active', 1)
        // ->where('sub_categories.category_id', $request->category_id)
        ->select('sub_categories.*')
        ->groupBy('sub_categories.id')
        ->get();
    }

    function productDetailsByVendorProductId($vendor, $productId){
        if($vendor>0){
            $product = DB::table('vendor_products')
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->join('sub_categories', 'sub_categories.id','=', 'products.sub_category_id')
            ->where('sub_categories.is_active', 1)
            ->where('vendor_products.vendor_id', $vendor)
            ->where('vendor_products.id', $productId)
            ->where('products.is_active', 1)
            ->where('vendor_products.is_active', 1)
            ->select(['products.*', 'vendor_products.*', 'vendor_products.id as vendor_product_id','products.id as product_id', 'vendor_products.is_active as is_active' ])->first();
            $images = ProductImage::where('product_id', $product->product_id)->select('name')->get();
            return [
                'product' => $product,
                'images' => $images,
            ];
        }
        else{

            $product = Product::where('id',$productId)->first();
            $images = ProductImage::where('product_id', $productId)->select('name')->get();
            
            //$images = ['name' => $product->image];
            return [
                'product' => $product,
                //'images' => array($images),
                'images' => $images,
            ];
        }
        
    }

    public function featuredProductsByVendor(Request $request,$vendor){
        /*$products = DB::table('vendor_products')
        ->join('products', 'products.id', '=', 'vendor_products.product_id')
        ->where('products.is_active', 1)
        ->where('vendor_products.vendor_id', $vendor)
        ->where('vendor_products.is_active', 1)
        ->where('vendor_products.is_featured', 1)
        ->select(['*', 'vendor_products.id as vendor_product_id','products.id as product_id', 'vendor_products.is_active as is_active' ]);

        return $products->get();*/
        
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
              return $query->where('vendor_products.vendor_id',$vendor);
        }])
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->leftjoin('packages','vendor_products.package_id','=','packages.id')
            ->where('products.is_active', 1)
            ->where('vendor_products.vendor_id', $vendor)
            ->where('vendor_products.is_active', 1)
            ->where('vendor_products.is_featured', 1)
            ->select(['*','vendor_products.id as vendor_product_id','products.id as product_id','products.name as product_name','vendor_products.is_active as is_active', 'vendor_products.is_featured as is_featured','packages.name as package_name'])
            ->groupBy('vendor_products.product_id');
       
        
        $products = $products->skip($start_limit)->take($end_limit)->get();
        return $products;
    }
}
