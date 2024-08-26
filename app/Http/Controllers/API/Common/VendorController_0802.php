<?php

namespace App\Http\Controllers\API\Common;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\VerificationCode;
use App\VendorDeviceToken;
use App\VendorAppVersionHash;
use App\Utilities\SmsUtility;
use App\VendorProduct;
use App\ProductImage;
use App\ProductPackage;
use App\Product;
use Image;
use Storage;
class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vendor::get();
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
            'email' => 'nullable|email',
            'contact_no' => 'required|numeric|digits:10|unique:vendors,contact_no',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'service_range' => 'nullable|numeric',
            'category_id' => 'required|numeric',
            'super_category_id' => 'required|numeric',
            'address' => 'required|string',
            'about_vendor' => 'required|string',
        ]);
        $data = $request->only('name', 'email', 'contact_no', 'latitude', 'longitude', 'address','about_vendor', 'service_range', 'category_id','super_category_id');

        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['image' => $name]);
        }
        

        $vendor = new Vendor();
        $vendor->name = $request->name;
        $vendor->shop_image = $request->image;
        $vendor->email = $request->email;
        $vendor->contact_no = $request->contact_no;
        $vendor->return_replacement = $request->return_replacement;
        $vendor->latitude = $request->latitude;
        $vendor->longitude = $request->longitude;
        $vendor->service_range = $request->service_range;
        $vendor->is_active = $request->is_active;
        $vendor->category_id = $request->category_id;
        $vendor->super_category_id = $request->super_category_id;
        $vendor->address = $request->address;
        $vendor->about_vendor = $request->about_vendor;
        $vendor->save(); 
        $id = $vendor->id;

        


        return [
            'success' => true,
            'id' => $id,
            // 'products' => $this->addCategoryProductsToVendor($id, $request->category_id)

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
        return $vendor = Vendor::findOrFail($id);
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

        $vendor = Vendor::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'nullable|email',
            'contact_no' => 'nullable|numeric|digits:10|unique:vendors,contact_no'. ',' .$id.',id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'service_range' => 'nullable|numeric',
            'super_category_id' => 'nullable|numeric',
            'category_id' => 'required|numeric',
            'address' => 'nullable|string',
            'about_vendor' => 'nullable|string',
            'is_active'=> 'boolean'
        ]);

        $oldImage = $vendor->shop_image;
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['image' => $name]);
            $oldImage = public_path('uploads/images/vendor/').$oldImage;
            if(file_exists($oldImage)){
                @unlink($oldImage);
            }
        }else{
            $request->merge(['image' => $oldImage]);
        }

        $data = $request->only('name', 'email', 'contact_no', 'latitude', 'longitude', 'address','about_vendor', 'service_range', 'is_active', 'category_id','super_category_id');
        $vendor->name = $request->name;
        $vendor->shop_image= $request->image;
        $vendor->email = $request->email;
        $vendor->contact_no = $request->contact_no;
        $vendor->return_replacement = $request->return_replacement;
        $vendor->latitude = $request->latitude;
        $vendor->longitude = $request->longitude;
        $vendor->service_range = $request->service_range;
        $vendor->is_active = $request->is_active;
        $vendor->category_id = $request->category_id;
        $vendor->super_category_id = $request->super_category_id;
        $vendor->address = $request->address;
        $vendor->about_vendor = $request->about_vendor;
        $vendor->update();
      
        return [
            'message' => 'Updated Successfully'
        ];
    }

    public function statusUpdate(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        $validator = \Validator::make($request->all(), ['open'=>'required|boolean']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        
        $vendor->is_active = $request->open;
        $vendor->update();
        $message = ($request->open==0)?'Vendor is deactivated':'Vendor is activated';
        return [
            'success' => 1,
            'message' => $message
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        // delete the vendor
        return [
            'message' => 'Vendor Deleted !'
        ];
    }


    public function logon(Request $request){
        $validator = \Validator::make($request->all(), [
            'contact_no'=>'required|numeric|min:5',
            'app_version' => 'sometimes|numeric',
        ]);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        $otp = rand(10000,99999);
        // $otp = 12345;
        $message = 'Your one time password to use in ocean app is '.$otp;

        $appVersion = isset($request->app_version) ? $request->app_version : 1;

        $hash = VendorAppVersionHash::where('version_code', $appVersion)->first();

        if($hash){
            $message.= ' '.$hash->hash;
        }
        $data = [
            'mobile' => $request->contact_no,
            'code' => $otp
        ];
        $id = VerificationCode::create($data)->id;
        $sms = new SmsUtility;
        $sms->sendmessage([$request->contact_no], $message, 1);
        $id = VerificationCode::create($data)->id;
        return [
            'verification_id'=>$id,
            'message' => 'OTP generated successfully'
        ];
    }

    public function verifyLogonOTP(Request $request){
        
        $validator = \Validator::make($request->all(), [
            'verification_id' => 'required|numeric',
            'otp' => 'required|numeric|min:5',
            'device_token' => 'sometimes|string'
        ]);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        $verification = VerificationCode::where(['id' => $request->verification_id, 'code'=>$request->otp])->first();
        $response =[];
        $response['success'] = false;
        if($verification){
            $response["registered"] = false;
            $response['success'] = true;
            $vendor = Vendor::with('vendorCategory')->where(['contact_no'=>$verification->mobile])->first();
            if($vendor){
                if($request->only('device_token'))
                    VendorDeviceToken::updateOrCreate(['vendor_id' =>$vendor->id, 'token' => $request->device_token]);
                $response['vendor'] = $vendor;
                $response['registered'] = true;
            }
        }
        return $response;
    }

    private function addCategoryProductsToVendor($vendorId, $categoryId){
        $products = DB::table('products')->join('sub_categories', 'sub_categories.id', '=', 'products.sub_category_id')
            ->join('categories', 'categories.id', '=', 'sub_categories.category_id')->where(['categories.id' => $categoryId])->select(['*', 'products.id as id' ])->get();
        if(!empty($products)){
            $vendorProducts = [];
            foreach ($products as $key => $product) {
                $vp =[
                    'vendor_id'=>$vendorId,
                    'product_id'=>$product->id,
                    'price'=>1,
                    'cost_price'=>1,
                    'mrp'=>1,
                    'is_active' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $vendorProducts [] = $vp;
            }
            return DB::table('vendor_products')->insert($vendorProducts);
        }
        return false;
    }

    public function getActiveVendors(Request $request){

        $validator = \Validator::make($request->all(), ['latitude'=>'required|numeric','longitude'=>'required|numeric','category_id'=>'required|numeric','start_limit'=>'required|numeric','end_limit'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
       
        $latitude = $request->latitude;
        $longitude= $request->longitude;
        $start_limit= $request->start_limit;
        $end_limit= $request->end_limit;
        $radius   = '5';
        

        $vendor = Vendor::selectRaw("id, name,shop_image, address,about_vendor, latitude, longitude, service_range, email ,contact_no,return_replacement,super_category_id,category_id,is_active,
                         ( 6371 * acos( cos( radians(?) ) *
                           cos( radians( latitude ) )
                           * cos( radians( longitude) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( latitude ) ) )
                         ) AS distance", [$latitude, $longitude, $latitude])
            /*->where('is_active', '=', 1)*/
            ->where('category_id', '=', $request->category_id)
            ->havingRaw('distance <= service_range/1000')
            ->orderBy("distance",'asc')
             ->skip($start_limit)->take($end_limit)
            ->get();

        return $vendor;
    }
    
    
    
    public function deleteVendorProduct($vendorProductId){
        
        $vendor = VendorProduct::find($vendorProductId);
        if($vendor)
        {
            $vendor->delete();
            $message = 'Product Deleted Successfully';
        }
        else{
            $message = 'No such product is available to delete';
        }
        // delete the vendor
        return [
            'message' => $message
        ];    
    }
    
    
    public function saveVendorProduct(Request $request){
        
        $validator = \Validator::make($request->all(), ['vendor_id'=>'required|numeric','name'=>'required|string|max:191','description'=>'max:300','long_description'=>'max:2000','package'=>'required','category_id'=>'required|numeric','sup_sub_category_id' => 'required|numeric','sub_category_id' => 'required|numeric','price'=>'required']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->long_description = $request->long_description;
        $product->category_id = $request->category_id;
        $product->sup_sub_category_id = $request->sup_sub_category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->co_sub_category_id = $request->co_sub_category_id;
        $product->commodity_type_id = '1';
        $product->save();
        $productId = $product->id;
        $package_array = explode(',',$request->package);
        
        $productImages = [];
        if(isset($request->images)):
           
            foreach (json_decode($request->images) as $key => $image) {
                
                    $file_name = rand(000,999).'.png';
                    $file_path = '/uploads/images/product/'.$file_name;
                    $storagepath = '/public'.$file_path;
                    //Storage::put($storagepath, base64_decode($image));
                    file_put_contents(public_path().$file_path, base64_decode($image));
                    $file_name1 = '/storage'.$file_path;
     
                    $productImages[] = [
                        'name' => $file_name,
                        'product_id' => $productId
                    ];
                
            }
            ProductImage::insert($productImages);
            
            
            $updateProduct = new Product();
            $updateProduct = $updateProduct->where([['id',$productId]])->first();
            $updateProduct->image = $productImages[0]['name'];
            $updateProduct->package = $package_array[0];
            $updateProduct->update();
        endif;

        
       // print_r($package_array);exit;
        foreach ($package_array as $key => $value) {
            $product_packages = new ProductPackage;
            $product_packages->product_id = $productId;
            $product_packages->package_id = $value;
            $product_packages->save();
            
            $vendor_product = new VendorProduct;
            $vendor_product->vendor_id = $request->vendor_id;
            $vendor_product->product_id = $productId;
            $vendor_product->price = $request->price;
            $vendor_product->mrp = $request->price;
            $vendor_product->cost_price = $request->price;
            $vendor_product->package_id = $value;
            $vendor_product->save();
        }
        
        return [
            'success' => 1,
            'message' => 'Product added Successfully',
        ];
    }
    
    public function getVendorStatus($vendorId){
        $vendor = Vendor::selectRaw("id,name,is_active")->where('id',$vendorId)->first();
        if(!empty($vendor))
        {
            return $vendor;
            
        }else{
            return [
            'message' => 'Invalid vendor Id'
            ];
        }
        
        
    }
}
