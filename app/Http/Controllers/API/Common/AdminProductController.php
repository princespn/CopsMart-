<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AdminProduct;
use App\AdminProductImage;
use App\Package;
use App\AdminCategory;
use App\AdminProductPackage;
use App\AdminServiceAreaProduct;
use App\ServiceArea;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use Hash;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AdminProduct::latest()->with('subCategory')->paginate(10);
    }

    public function all()
    {
        return AdminProduct::leftJoin('admin_categories as ad_cat','ad_cat.id','=','admin_products.category_id')->leftJoin('admin_categories', 'admin_categories.id', '=', 'admin_products.sub_category_id')->leftJoin('admin_product_packages', 'admin_product_packages.product_id', '=', 'admin_products.id')->leftJoin('packages', 'packages.id', '=', 'admin_product_packages.package_id')->select(['admin_products.*','admin_products.id as product_id','ad_cat.name as c_name', 'admin_categories.name as sc_name','packages.name as package_name','admin_product_packages.package_id','admin_product_packages.id as pack_row_id'])->get();
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
            'name' => 'required|string|max:191',
            'description' => 'max:300',
            'package' => 'required',
            'category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            
        ]);
        $package_array = explode(',',$request->package);
        
            $product = new AdminProduct();
            $product->name = $request->name;
            $product->package = $package_array[0];
            $product->description = $request->description;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->save();
            $productId = $product->id;
    

        $productImages = [];
        if(isset($request->images)):
            foreach ($request->images as  $image) {
                if($image->extension() == 'png' || $image->extension() == 'jpg' || $image->extension() == 'jpeg'){
                    //$image->file('thumb');
                    // $image->move("/uploads/images/product", $image->hashname());

                    $image->store('/images/admin_product', ['disk' => 'uploads']);
                    $productImages[] = [
                        'name' => $image->hashname(),
                        'product_id' => $productId
                    ];
                }
            }
            
            AdminProductImage::insert($productImages);
            
            AdminProduct::findOrFail($productId)->update(['image' => $productImages[0]['name']]);
        endif;

        
        foreach ($package_array as $key => $value) {
            $product_packages = new AdminProductPackage;
            $product_packages->product_id = $productId;
            $product_packages->package_id = $value;
            $product_packages->save();
        }    
        return [
            'message' => 'created Successfully',
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        // return [$request, 'id'=>$id];
        $product = AdminProduct::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'description' => 'max:300',
            'package' => 'required',
            'category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        $oldImage = $product->image;
        $productImages = [];
        if(isset($request->images)):
            foreach ($request->images as  $image) {
                if($image->extension() == 'png' || $image->extension() == 'jpg' || $image->extension() == 'jpeg'){
                    //$image->file('thumb');
                    // $image->move("/uploads/images/product", $image->hashname());

                    $image->store('/images/admin_product', ['disk' => 'uploads']);
                    $productImages[] = [
                        'name' => $image->hashname(),
                        'product_id' => $id
                    ];
                }
            }
            AdminProductImage::insert($productImages);
            AdminProduct::findOrFail($id)->update(['image' => $productImages[0]['name']]);
        endif;
        $data = $request->only('name', 'image','package', 'description','category_id','sub_category_id', 'is_active', 'is_featured');
    
        $product_packages = new AdminProductPackage;
        //check
        if($request->pack_row_id){
            $check = $product_packages->where([['product_id',$id],['package_id',$request->package]])->first();
            if(!$check){
            $product_packages = new AdminProductPackage;
                $data1 = $product_packages->find($request->pack_row_id);
                if($data1){
                   $data1->package_id = $request->package;
                   $data1->update();
                }
            }
            
        }else{

            $product_packages = new AdminProductPackage;
            $product_packages->product_id = $id;
            $product_packages->package_id = $request->package;
            $product_packages->save();

        }
        
        if($product->update($data)){
            return [
                'message' => 'Updated Successfully',
                'images' => $productImages
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = AdminProduct::findOrFail($id);
        $product->delete();
        // delete the product
        return [
            'message' => 'Product Deleted !'
        ];
    }

    function dataTable(Request $request){
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');

        $query = AdminProduct::leftJoin('admin_categories as ad_cat','ad_cat.id','=','admin_products.category_id')->leftJoin('admin_categories', 'admin_categories.id', '=', 'admin_products.sub_category_id')->leftJoin('admin_product_packages', 'admin_product_packages.product_id', '=', 'admin_products.id')->leftJoin('packages', 'packages.id', '=', 'admin_product_packages.package_id')->select(['admin_products.*', 'ad_cat.name as c_name','admin_categories.name as sc_name','packages.name as package_name','admin_product_packages.package_id','admin_product_packages.id as pack_row_id'])->dataTableQuery($column, $orderBy, $searchValue)->orWhere('admin_categories.name', "LIKE", "%$searchValue%");
        $data = $query->paginate($length);

        return new DataTableCollectionResource($data);
    }

    function package(Request $request){
        $product_package = new AdminProductPackage();
        $product_package = $product_package->where('product_id',$request->product_id)->get();

        return ['product_package'=>$product_package];
    }

    public function category(Request $request){
        $category = new AdminCategory();
        $category = AdminCategory::where([['is_active',1],['parent_id','=',0]])->get();
        return ['pro_category'=>$category];
    }

    public function service_area(Request $request){
        $category = new ServiceArea();
        $category = ServiceArea::where([['is_active',1]])->get();
        return ['service_area'=>$category];
    }

    public function sub_category(Request $request){
        
        $subcategory = new AdminCategory();
        $subcategory = AdminCategory::where([['is_active',1],['parent_id',$request->category_id]])->get();
        return ['sup_sub_category'=>$subcategory];
    }

    public function category_by_id(Request $request){
        $Product = new AdminProduct();
        $category_id = $Product->pluck('category_id');
        return AdminCategory::whereIn('id',$category_id)->where([['parent_id',$request->category_id]])->where('is_active',1)->get();
    }

    public function package_list(Request $request){
        
        return ['package'=>Package::get()];
    }
    
    public function adminProductDetail($serviceAreaProductId){
       
        $product = AdminServiceAreaProduct::join('admin_products', 'admin_products.id', '=', 'admin_service_area_products.product_id')
            ->leftjoin('packages','admin_service_area_products.package_id','=','packages.id')
            ->where([['admin_service_area_products.id', $serviceAreaProductId],['admin_products.is_active', 1],['admin_service_area_products.is_active',1]])
            ->select(['admin_service_area_products.*','admin_products.sub_category_id','admin_products.image','admin_products.description','admin_products.name as product_name','admin_service_area_products.is_active as is_active', 'packages.name as package_name'])->first();
            $images = AdminProductImage::where('product_id', $product->product_id)->select('name')->get();
            return [
                'product' => $product,
                'images' => $images,
            ];
        
        return $response;
          
    }

    public function activeProducts(Request $request){
       //  print_r(Hash::make('Qwerty@1234'));exit;
        $validator = \Validator::make($request->all(), ['category_id'=>'required|numeric','service_area_id'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        $sub_category_id = $request->sub_category_id;
        $service_area_id = $request->service_area_id;
        
        
        $products = AdminServiceAreaProduct::join('admin_products', 'admin_products.id', '=', 'admin_service_area_products.product_id')
            ->leftjoin('packages','admin_service_area_products.package_id','=','packages.id')
            ->leftjoin('admin_categories','admin_categories.id','=','admin_service_area_products.category_id')
            ->leftjoin('admin_categories as sb_cat','sb_cat.id','=','admin_products.sub_category_id')
            ->where([['admin_service_area_products.service_area_id', $service_area_id],['admin_products.is_active', 1],['admin_service_area_products.is_active',1],['admin_service_area_products.category_id',$request->category_id]])
            ->select(['admin_service_area_products.*','admin_products.sub_category_id','sb_cat.name as sub_category_name','admin_products.image','admin_products.description','admin_products.id as product_id','admin_products.name as product_name','admin_service_area_products.is_active as is_active', 'packages.name as package_name','admin_categories.name as category_name'])
            ->groupBy('admin_service_area_products.product_id');
       
        $qparam = [];
        if($sub_category_id){
            array_push($qparam, ['admin_products.sub_category_id','=',$sub_category_id]);
        }
        $products = $products->where($qparam)
                    ->get();


        $response = [];
        foreach ($products as $row) {
            $packages = AdminServiceAreaProduct::leftjoin('packages','admin_service_area_products.package_id','=','packages.id')
            ->leftjoin('admin_products','admin_products.id','=','admin_service_area_products.product_id')
            ->select(['admin_service_area_products.id','admin_service_area_products.admin_service_area_id','admin_service_area_products.service_area_id','admin_service_area_products.delivery_charges','packages.id as package_id','admin_products.image','admin_service_area_products.product_id','packages.name as package_name','admin_service_area_products.mrp','admin_service_area_products.sell_price','admin_service_area_products.is_active','admin_products.name as product_name','admin_products.description','admin_products.sub_category_id','admin_service_area_products.category_id','admin_service_area_products.delivery_in_days','admin_service_area_products.replacement_in_days','admin_service_area_products.available_finance'])
            ->where([['admin_service_area_products.admin_service_area_id',$row->admin_service_area_id],['admin_service_area_products.product_id',$row->product_id]])->get();

           $product_array = ['id'=>$row->id,'category_id'=>$row->category_id,'sub_category_id'=>$row->sub_category_id,'sub_category_name'=>$row->sub_category_name,'image'=>$row->image,'description'=>$row->description,'admin_service_area_id'=>$row->admin_service_area_id,'service_area_id'=>$row->service_area_id,'product_id'=>$row->product_id,'package_id'=>$row->package_id,'mrp'=>$row->mrp,'sell_price'=> $row->sell_price,'delivery_in_days' => $row->delivery_in_days,'delivery_charges' => $row->delivery_charges,'replacement_in_days'=>$row->replacement_in_days,'available_finance'=>$row->available_finance,'is_active'=>$row->is_active,'product_name'=>$row->product_name,'package_name'=>$row->package_name,'category_name'=>$row->category_name,'product_packages'=>$packages];
            array_push($response,$product_array);
          
            }
        return $response;
     }
     
     public function activeProductsPagination(Request $request){
        
        $validator = \Validator::make($request->all(), ['category_id'=>'required|numeric','service_area_id'=>'required|numeric','start_limit'=>'required|numeric','end_limit'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        $sub_category_id = $request->sub_category_id;
        $service_area_id = $request->service_area_id;
        $start_limit= $request->start_limit;
        $end_limit= $request->end_limit;
        
        
        $products = AdminServiceAreaProduct::join('admin_products', 'admin_products.id', '=', 'admin_service_area_products.product_id')
            ->leftjoin('packages','admin_service_area_products.package_id','=','packages.id')
            ->leftjoin('admin_categories','admin_categories.id','=','admin_service_area_products.category_id')
            ->leftjoin('admin_categories as sb_cat','sb_cat.id','=','admin_products.sub_category_id')
            ->where([['admin_service_area_products.service_area_id', $service_area_id],['admin_products.is_active', 1],['admin_service_area_products.is_active',1],['admin_service_area_products.category_id',$request->category_id]])
            ->select(['admin_service_area_products.*','admin_products.sub_category_id','sb_cat.name as sub_category_name','admin_products.image','admin_products.description','admin_products.id as product_id','admin_products.name as product_name','admin_service_area_products.is_active as is_active', 'packages.name as package_name','admin_categories.name as category_name'])
            ->groupBy('admin_service_area_products.product_id');
       
        $qparam = [];
        if($sub_category_id){
            array_push($qparam, ['admin_products.sub_category_id','=',$sub_category_id]);
        }
        $products = $products->where($qparam)
                    ->skip($start_limit)->take($end_limit)
                    ->get();


        $response = [];
        foreach ($products as $row) {
            $packages = AdminServiceAreaProduct::leftjoin('packages','admin_service_area_products.package_id','=','packages.id')
            ->leftjoin('admin_products','admin_products.id','=','admin_service_area_products.product_id')
            ->select(['admin_service_area_products.id','admin_service_area_products.admin_service_area_id','admin_service_area_products.service_area_id','admin_service_area_products.delivery_charges','packages.id as package_id','admin_products.image','admin_service_area_products.product_id','packages.name as package_name','admin_service_area_products.mrp','admin_service_area_products.sell_price','admin_service_area_products.is_active','admin_products.name as product_name','admin_products.description','admin_products.sub_category_id','admin_service_area_products.category_id','admin_service_area_products.delivery_in_days','admin_service_area_products.replacement_in_days','admin_service_area_products.available_finance'])
            ->where([['admin_service_area_products.admin_service_area_id',$row->admin_service_area_id],['admin_service_area_products.product_id',$row->product_id]])->get();

           $product_array = ['id'=>$row->id,'category_id'=>$row->category_id,'sub_category_id'=>$row->sub_category_id,'sub_category_name'=>$row->sub_category_name,'image'=>$row->image,'description'=>$row->description,'admin_service_area_id'=>$row->admin_service_area_id,'service_area_id'=>$row->service_area_id,'product_id'=>$row->product_id,'package_id'=>$row->package_id,'mrp'=>$row->mrp,'sell_price'=> $row->sell_price,'delivery_in_days' => $row->delivery_in_days,'delivery_charges' => $row->delivery_charges,'replacement_in_days'=>$row->replacement_in_days,'available_finance'=>$row->available_finance,'is_active'=>$row->is_active,'product_name'=>$row->product_name,'package_name'=>$row->package_name,'category_name'=>$row->category_name,'product_packages'=>$packages];
            array_push($response,$product_array);
          
            }
        return $response;
     }
     
     function adminSearchPagination(Request $request){
        $input = $request->input();
        if(!isset($input['query']) || !isset($input['admin_service_area_id'])){
            return [];
        }
        $vendor = $input['admin_service_area_id'];
        
        
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
       
            $products = AdminServiceAreaProduct::with(['admin_product_package'=> function($query) use ($vendor){
              
                return $query->where('admin_service_area_products.admin_service_area_id',$vendor);
                
            }])
            ->join('admin_products', 'admin_products.id', '=', 'admin_service_area_products.product_id')
            ->leftjoin('packages','admin_service_area_products.package_id','=','packages.id')
            ->where('admin_products.is_active', 1)
            ->where('admin_products.name', 'like' , '%' .$input['query'] . '%')
            ->select(['*','admin_service_area_products.id as admin_product_id','admin_products.id as product_id','admin_products.name as product_name','admin_service_area_products.is_active as is_active','packages.name as package_name'])
            //->groupBy('admin_service_area_products.product_id')
            ->skip($start_limit)->take($end_limit)
            ->get();
        
        return $products;
    }

     
}
