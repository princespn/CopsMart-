<?php

namespace App\Http\Controllers\API\Common;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\User;
use App\VerificationCode;
use App\VendorDeviceToken; 
use App\VendorAppVersionHash;
use App\Utilities\SmsUtility;
use App\VendorProduct;
use App\ProductImage;
use App\ProductPackage;
use App\Product;
use App\TopBanner;
use App\Order;
use App\Category;
use App\SubCategory;
use Illuminate\Support\Facades\Hash;
use App\BottomBanner;
use App\Brand;
use Image;
use Storage;
use DataTables;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        
       if(isset($_GET['ad']))
       {
           $id=$_GET['ad'];
           if($id!=1)
           {
             $data= Vendor::where('deleted_at',NULL)->where('admin_id',$id);
           }
           else{
              $data= Vendor::where('deleted_at',NULL);
           }
       }
       else
       {
           $data= Vendor::where('deleted_at',NULL);
       }

       $searchValue = $_GET['table'];
       if($searchValue!=''){
           //echo  $searchValue 
           return $data=$data->where('name', "LIKE", "%$searchValue%")->get();
       }
       else
       {
       return $data->get();
      }
    }
    
    public function TopVendors()
    {
        return DB::table('vendors')->where('is_top',1)->where('is_active',1)->orderBy('top','asc')->get();
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
            'name' => 'required|string',
            'business_name' => 'required|string',
            'gstin' => 'required',
            'address' => 'required',
            'pincode' => 'required',
            'district' =>'required',
            'state' => 'required',
            'latitude' => 'required',
            'longitude' =>  'required',
            'contact_person_name' =>  'required',
            'contact_person_mobile' =>  'required',
            'email' =>  'required',
            'contact_no' =>  'required',
            'app_name' =>  'required',
            'shop_image' =>  'required',
            'work_order' =>  'required',
            'gstin_certificate' =>  'required',
            'shop_act' =>  'required',
            'app_icon' => 'required',
            'bank_document' => 'required',
            'bank_account_name' =>  'required',
            'bank_ifsc' => 'required|string',
            'ifscverify' => 'required|string',
            'bank_account_number' =>  'required',
            'account_verification' =>  'required',
            'delivery_for_base_city' =>  'required',
            'delivery_service_for_district' =>  'required',
            'online' =>  'required',
            'offline' =>  'required',
            'sales_percent' => 'required',
            'pg_charges' => 'required',
            'pickup_charges' => 'required',
            'is_active' =>  'required',
        ]);
        if($request->shop_image){
            $name = time().'_shop_'.'.' . explode('/', explode(':', substr($request->shop_image, 0, strpos($request->shop_image, ';')))[1])[1];
            \Image::make($request->shop_image)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['shop_image' => $name]);
        }

        if($request->work_order){
            $name = time().'_workorder_'.'.' . explode('/', explode(':', substr($request->work_order, 0, strpos($request->work_order, ';')))[1])[1];
            \Image::make($request->work_order)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['work_order' => $name]);
        }

        if($request->gstin_certificate){
            $name = time().'_gstc_'.'.' . explode('/', explode(':', substr($request->gstin_certificate, 0, strpos($request->gstin_certificate, ';')))[1])[1];
            \Image::make($request->gstin_certificate)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['gstin_certificate' => $name]);
        }

        if($request->shop_act){
            $name = time().'_shopact_'.'.' . explode('/', explode(':', substr($request->shop_act, 0, strpos($request->shop_act, ';')))[1])[1];
            \Image::make($request->shop_act)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['shop_act' => $name]);
        }

        if($request->app_icon){
            $name = time().'_appicon_'.'.' . explode('/', explode(':', substr($request->app_icon, 0, strpos($request->app_icon, ';')))[1])[1];
            \Image::make($request->app_icon)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['app_icon' => $name]);
        }

        if($request->bank_document){
            $name = time().'_bankac_'.'.' . explode('/', explode(':', substr($request->bank_document, 0, strpos($request->bank_document, ';')))[1])[1];
            \Image::make($request->bank_document)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['bank_document' => $name]);
        }
        
        $vendor = new Vendor();
        $vendor->name = $request->name;
        $vendor->admin_id=$request->admin_id;
        $vendor->business_name = $request->business_name;
        $vendor->gstin = $request->gstin;
        $vendor->address = $request->address;
        $vendor->pincode = $request->pincode;
        $vendor->district = $request->district;
        $vendor->state = $request->state;
        $vendor->latitude = $request->latitude;
        $vendor->longitude = $request->longitude;
        $vendor->contact_person_name = $request->contact_person_name;
        $vendor->contact_person_mobile = $request->contact_person_mobile;
        $vendor->email = $request->email;
        $vendor->contact_no = $request->contact_no;
        $vendor->app_name = $request->app_name;
        $vendor->shop_image = $request->shop_image;
        $vendor->work_order = $request->work_order;
        $vendor->gstin_certificate = $request->gstin_certificate;
        $vendor->shop_act = $request->shop_act;
        $vendor->app_icon = $request->app_icon;
        $vendor->bank_document = $request->bank_document;
        $vendor->bank_account_name = $request->bank_account_name;
        $vendor->bank_ifsc = $request->bank_ifsc;
        $vendor->ifscverify = $request->ifscverify;
        $vendor->ifscverifybank = $request->ifscverifybank;
        $vendor->account_v_name = $request->account_v_name;
        $vendor->bank_account_number = $request->bank_account_number;
        $vendor->account_verification = $request->account_verification;
        $vendor->delivery_for_base_city = $request->delivery_for_base_city;
        $vendor->delivery_service_for_district = $request->delivery_service_for_district;
        $vendor->online = $request->online;
        $vendor->offline = $request->offline;
        $vendor->sales_percent = $request->sales_percent;
        $vendor->pg_charges = $request->pg_charges;
        $vendor->pickup_charges = $request->pickup_charges;
        $vendor->is_active = $request->is_active;
        $vendor->save(); 
        $id = $vendor->id;
        $user = new User();
        $user->admin_id = $id;
        $user->email = $request->email;
        $hashedPassword = Hash::make($request->password);
        $user->password = $hashedPassword;
        $user->type='customer';
        $user->save(); 
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
        $idx=User::where('id',$id)->first();
        $de=Vendor::where('id',$idx->admin_id)->get();
        return $vendor = Vendor::findOrFail($de[0]->id);
    }

    public function getvendor($id)
    {   

        return $vendor = Vendor::join('users','users.admin_id','=','vendors.id')->where('vendors.id',$id)->select(['vendors.*'])->first();
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
            'is_active' =>  'sometimes',
        ]);

        $vendor->is_active = $request->is_active;
        $vendor->update();
        return [
            'message' => 'Updated Successfully'
        ];
    } 

    public function updatevendor(Request $request, $id)
    {

        $vendor = Vendor::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string',
            'business_name' => 'required|string',
            'gstin' => 'required',
            'address' => 'sometimes',
            'pincode' => 'sometimes',
            'district' =>'sometimes',
            'state' => 'sometimes',
            'latitude' => 'sometimes',
            'longitude' =>  'sometimes',
            'contact_person_name' =>  'sometimes',
            'contact_person_mobile' =>  'sometimes',
            'email' =>  'sometimes',
            'contact_no' =>  'sometimes',
            'app_name' =>  'sometimes',
            'shop_image' =>  'sometimes',
            'work_order' =>  'sometimes',
            'gstin_certificate' =>  'sometimes',
            'shop_act' =>  'sometimes',
            'app_icon' => 'sometimes',
            'bank_document' => 'sometimes',
            'bank_account_name' =>  'sometimes',
            'bank_ifsc' => 'sometimes',
            'ifscverify' => 'sometimes',
            'bank_account_number' =>  'sometimes',
            'account_verification' =>  'sometimes',
            'delivery_for_base_city' =>  'sometimes',
            'delivery_service_for_district' =>  'sometimes',
            'online' =>  'sometimes',
            'offline' =>  'sometimes',
            'sales_percent' => 'sometimes',
            'pg_charges' => 'sometimes',
            'pickup_charges' => 'sometimes',
            'is_active' =>  'sometimes',
        ]);
        $oldImage = $vendor->shop_image;
        if($request->shop_images){
            $name = time().'_shop_'.'.' . explode('/', explode(':', substr($request->shop_images, 0, strpos($request->shop_images, ';')))[1])[1];
            \Image::make($request->shop_images)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['shop_image' => $name]);
            $oldImage = public_path('uploads/images/vendor/').$oldImage;
            if(file_exists($oldImage)){
                @unlink($oldImage);
            }
        }
        else{
            $request->merge(['shop_image' => $oldImage]);
        }

        $oldImagewk = $vendor->work_order;
        if($request->work_orders){
            $name = time().'_workorder_'.'.' . explode('/', explode(':', substr($request->work_orders, 0, strpos($request->work_orders, ';')))[1])[1];
            \Image::make($request->work_orders)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['work_order' => $name]);
            $oldImagewk = public_path('uploads/images/vendor/').$oldImagewk;
            if(file_exists($oldImagewk)){
                @unlink($oldImagewk);
            }
        }
        else{
            $request->merge(['work_order' => $oldImagewk]);
        }

        $oldImagegc = $vendor->gstin_certificate;
        if($request->gstin_certificates){
            $name = time().'_gstc_'.'.' . explode('/', explode(':', substr($request->gstin_certificates, 0, strpos($request->gstin_certificates, ';')))[1])[1];
            \Image::make($request->gstin_certificates)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['gstin_certificate' => $name]);
            $oldImagegc = public_path('uploads/images/vendor/').$oldImagegc;
            if(file_exists($oldImagegc)){
                @unlink($oldImagegc);
            }
        }
        else{
            $request->merge(['gstin_certificate' => $oldImagegc]);
        }
        
        $oldImagesa = $vendor->shop_act;
        if($request->shop_acts){
            $name = time().'_shopact_'.'.' . explode('/', explode(':', substr($request->shop_acts, 0, strpos($request->shop_acts, ';')))[1])[1];
            \Image::make($request->shop_acts)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['shop_act' => $name]);
            $oldImagesa = public_path('uploads/images/vendor/').$oldImagesa;
            if(file_exists($oldImagesa)){
                @unlink($oldImagesa);
            }
        }
        else{
            $request->merge(['shop_act' => $oldImagesa]);
        }


        
        $oldImageai = $vendor->app_icon;
        if($request->app_icons){
            $name = time().'_appicon_'.'.' . explode('/', explode(':', substr($request->app_icons, 0, strpos($request->app_icons, ';')))[1])[1];
            \Image::make($request->app_icons)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['app_icon' => $name]);
            $oldImageai = public_path('uploads/images/vendor/').$oldImageai;
            if(file_exists($oldImageai)){
                @unlink($oldImageai);
            }
        }
        else{
            $request->merge(['app_icon' => $oldImageai]);
        }



        $oldImagebd = $vendor->bank_document;
        if($request->bank_documents){
            $name = time().'_bankac_'.'.' . explode('/', explode(':', substr($request->bank_documents, 0, strpos($request->bank_documents, ';')))[1])[1];
            \Image::make($request->bank_documents)->save(public_path('uploads/images/vendor/').$name);
            $request->merge(['bank_document' => $name]);
            $oldImage = public_path('uploads/images/vendor/').$oldImagebd;
            if(file_exists($oldImagebd)){
                @unlink($oldImagebd);
            }
        }
        else{
            $request->merge(['bank_document' => $oldImagebd]);
        }

        $vendor->name = $request->name;
        $vendor->admin_id=$request->admin_id;
        $vendor->business_name = $request->business_name;
        $vendor->gstin = $request->gstin;
        $vendor->address = $request->address;
        $vendor->pincode = $request->pincode;
        $vendor->district = $request->district;
        $vendor->state = $request->state;
        $vendor->latitude = $request->latitude;
        $vendor->longitude = $request->longitude;
        $vendor->contact_person_name = $request->contact_person_name;
        $vendor->contact_person_mobile = $request->contact_person_mobile;
        $vendor->email = $request->email;
        $vendor->contact_no = $request->contact_no;
        $vendor->app_name = $request->app_name;
        $vendor->shop_image = $request->shop_image;
        $vendor->work_order = $request->work_order;
        $vendor->gstin_certificate = $request->gstin_certificate;
        $vendor->shop_act = $request->shop_act;
        $vendor->app_icon = $request->app_icon;
        $vendor->bank_document = $request->bank_document;
        $vendor->bank_account_name = $request->bank_account_name;
        $vendor->bank_ifsc = $request->bank_ifsc;
        $vendor->ifscverify = $request->ifscverify;
        $vendor->ifscverifybank = $request->ifscverifybank;
        $vendor->account_v_name = $request->account_v_name;
        $vendor->bank_account_number = $request->bank_account_number;
        $vendor->account_verification = $request->account_verification;
        $vendor->delivery_for_base_city = $request->delivery_for_base_city;
        $vendor->delivery_service_for_district = $request->delivery_service_for_district;
        $vendor->online = $request->online;
        $vendor->offline = $request->offline;
        $vendor->sales_percent = $request->sales_percent;
        $vendor->pg_charges = $request->pg_charges;
        $vendor->pickup_charges = $request->pickup_charges;
        $vendor->is_active = $request->is_active;
        $vendor->update();
        $user=User::where('admin_id',$id)->first();
        $user->email = $request->email;
        $user->update();
        return [
            'message' => 'Updated Successfully'
        ];
    } 
    
    
    public function VendorUpdatetime(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        $this->validate($request, [
            'open_time'=> 'required',
            'close_time'=> 'required',
        ]);
        $vendor->open_time = date('H:i',strtotime($request->open_time));
        $vendor->close_time = date('H:i',strtotime($request->close_time));
        //exit;
        $vendor->update();
        return [
            'success' => 1,
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
            'verification_id' => 'required',
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

        $validator = \Validator::make($request->all(), ['latitude'=>'required|numeric','longitude'=>'required|numeric','category_id'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
       
        $latitude = $request->latitude;
        $longitude= $request->longitude;
        //$start_limit= $request->start_limit;
        //$end_limit= $request->end_limit;
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
            //->skip($start_limit)->take($end_limit)
            ->get();

        return $vendor;
    }
    
    public function getActiveVendorsPagination(Request $request){

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
            ->where('force_off', '=', 1)
            ->havingRaw('distance <= service_range/1000')
            ->orderBy("distance",'asc')
            ->skip($start_limit)->take($end_limit)
            ->get();

        return $vendor;
    }
    
    public function deleteVendorProduct($vendorProductId)
    {
        
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
    
    public function vendorgetonlineoffline($id)
    {  
       $idx=User::where('id',$id)->first();
       $de=Vendor::where('id',$idx->admin_id)->get();
       $online=0;
       $offline=0;
       $sal=0;
       $pg=0;
       $pick=0;
       $opn='';
       $close='';
       $closed='';
       if(count($de)>0)
       {  
        $online=$de[0]->online;
        $sal=$de[0]->sales_percent;
        $pg=$de[0]->pg_charges;
        $offline=$de[0]->offline;
        $pick=$de[0]->pickup_charges;
        $opn=$de[0]->open_time;
        $close=$de[0]->close_time;
        $closed=$de[0]->closed;
       }
       
       return $ar= array('offline'=>$offline, 'online'=>$online, 'sales'=>$sal,'pg_charges'=>$pg,'pickup'=>$pick,'open_time'=>$opn,'close_time'=>$close,'closed'=>$closed);
    }

    public function storeOnline(Request $request)
    {  
        $idx=User::where('id',$request->vendor_id)->first();
        // $de=Vendor::where('id',$idx->admin_id)->get();
        $vendor = Vendor::findOrFail($idx->admin_id);
        $this->validate($request, [
            'online'=> 'required',
            'offline'=> 'required',
            'sales_percent'=>'required',
            'pickup_charges'=>'required',
            'open_time'=>'required',
            'close_time'=>'required',
            'closed'=>'required',
        ]);
        $vendor->online = $request->online;
        $vendor->offline = $request->offline;
        $vendor->sales_percent=$request->sales_percent;
        $vendor->pickup_charges=$request->pickup_charges;
        $vendor->open_time=$request->open_time;
        $vendor->close_time=$request->close_time;
        $vendor->closed=$request->closed;
        //exit;
        $vendor->update();
        return [
            'success' => 1,
            'message' => 'Updated Successfully'
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
        $vendor = Vendor::selectRaw("id,name,is_active,open_time,close_time")->where('id',$vendorId)->get();
        if(!empty($vendor))
        {
             foreach($vendor as $vend)
             {
                 $vend->open_time=date('h:i A',strtotime($vend->open_time));
                 $vend->close_time=date('h:i A',strtotime($vend->close_time));
                 $data=$vend;
             }
             return $data;
            
        }else{
            return [
            'message' => 'Invalid vendor Id'
            ];
        }
    }

    public function BannerImage($vendorId)
    {
         $top=TopBanner::where('vendor_id',$vendorId)->get();
         $bot=BottomBanner::where('vendor_id',$vendorId)->get();
        // print_r($top);
        $dxtx=[];
        $dxt=[];
         $data['topbanner']=[];
         foreach($top as $key)
         {   
             $key->brand_name='';
             $key->product_name='';
             $key->sub_sub_category_name='';
             $key->sub_category_name='';
             $key->category_name='';
             if($key->type=='Product')
             {
                $dt='productPage';
             }elseif($key->type=='Category' &&  $key->category_id!='0' && $key->sub_category_id=='' && $key->sub_sub_category_id=='')
             {
                  $dt='categoryPage';
             }
             elseif($key->type=='Category' && $key->category_id!=''&& $key->sub_category_id!='' && $key->sub_sub_category_id=='')
             {
                  $dt='allSubSubCategoryPage';
             }
             elseif($key->type=='Category' && $key->category_id!=''&& $key->sub_category_id!='' && $key->sub_sub_category_id!='')
             {
                  $dt='subCategoryPage';
                  
             }
             elseif($key->type=='Brand')
             {
                 $dt='brandPage';
             }
            $bnm=Category::where('id',$key->category_id)->first();
            if(isset($bnm))
            {
                $key->category_name=$bnm->name;
            }
            $bnm1=Category::where('id',$key->sub_category_id)->first();
            if(isset($bnm1))
            {
                $key->sub_category_name=$bnm1->name;
            }
            $bnm2=SubCategory::where('id',$key->sub_sub_category_id)->first();
            if(isset($bnm2))
            {
                $key->sub_sub_category_name=$bnm2->name;
            }
            $bnm3=Product::where('id',$key->product_id)->first();
            if(isset($bnm3))
            {
                $key->product_name=$bnm3->name;
            }
            $bnm4=Brand::where('id',$key->brand_id)->first();
            if(isset($bnm4))
            {
                $key->brand_name=$bnm4->name;
            }
             $key->pro_type=$dt;
             $dxt[]=$key;
         }
          foreach($bot as $keyx)
          {  
              
             $keyx->brand_name='';
             $keyx->product_name='';
             $keyx->sub_sub_category_name='';
             $keyx->sub_category_name='';
             $keyx->category_name='';
             if($keyx->type=='Product')
             {
                $dt='productPage';
                
             }
             elseif($keyx->type=='Category' &&  $keyx->category_id!='' && $keyx->sub_category_id=='' && $keyx->sub_sub_category_id=='')
             {
                  $dt='categoryPage';
             }
             elseif($keyx->type=='Category' && $keyx->category_id!=''&& $keyx->sub_category_id!='' && $keyx->sub_sub_category_id=='')
             {
                 $dt='allSubSubCategoryPage';
             }
             elseif($keyx->type=='Category' && $keyx->category_id!=''&& $keyx->sub_category_id!='' && $keyx->sub_sub_category_id!='')
             {
                   $dt='subCategoryPage';
             }
             elseif($keyx->type=='Brand')
             {
                 $dt='brandPage';
             }
             
            $bnm=Category::where('id',$keyx->category_id)->first();
            if(isset($bnm))
            {
                $keyx->category_name=$bnm->name;
            }
            $bnm1=Category::where('id',$keyx->sub_category_id)->first();
            if(isset($bnm1))
            {
                $keyx->sub_category_name=$bnm1->name;
            }
            $bnm2=SubCategory::where('id',$keyx->sub_sub_category_id)->first();
            if(isset($bnm2))
            {
                $keyx->sub_sub_category_name=$bnm2->name;
            }
            $bnm3=Product::where('id',$keyx->product_id)->first();
            if(isset($bnm3))
            {
                $keyx->product_name=$bnm3->name;
            }
            $bnm4=Brand::where('id',$keyx->brand_id)->first();
            if(isset($bnm4))
            {
                $keyx->brand_name=$bnm4->name;
            }
            
            
            $keyx->pro_type=$dt;
            $dxtx[]=$keyx;
         }
         $data['topbanner']=$dxt;
         
         $data['bottombanner']=$dxtx;
         
        $cata= DB::table('categories as c')->select("c.*",
        \DB::raw("(SELECT count(*) FROM categories
                WHERE parent_id = c.id
              ) as count"))->where('c.parent_id','0')->where('c.deleted_at',NULL)->whereNotIn('c.id',[0])->get();
              $dbdata=[];
        foreach ($cata as $keyxx)
       {  
           $keyxx->count=count(Product::where('category_id',$keyxx->id)->get());
           $dbdata[]=$keyxx;
       }
       //return $dbdata;
         $data['categories']=$dbdata;
         $brand=Brand::where('deleted_at',NULL)->get();
        $dbdatax1=[];
        foreach ($brand as $keyxx1)
       {  
           $keyxx1->count=count(Product::where('brand_id',$keyxx1->id)->get());
           $dbdatax1[]=$keyxx1;
       }
         $data['brands']=$dbdatax1;
         return $data;
    }
    function vendorlist(Request $request,$id)
      {
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        // echo $id;exit;
        $query = Vendor::where('admin_id',$id);
        if($searchValue!=''){
            $query->where('name', "LIKE", "%$searchValue%");
        }
        $data = $query->paginate($length);
        $dt=[];
        if(count($data)>0){
            $dt=[];
            $i=1;
            foreach($data as $key)
            {   
                $user=User::where('admin_id',$key->id)->first();
                $order=Order::where('vendor_id',$user->id)->where('order_status_id','!=',3)->get()->count();
                $key->srno=$i;
                $key->total_order=$order;
                $orderpending=Order::where('vendor_id',$user->id)->whereNotIn('order_status_id',[3,9])->get()->count();
                $key->total_pending=$orderpending;
                $orderamount=Order::where('vendor_id',$user->id)->where('order_status_id','!=',3)->sum('amount');
                $key->total_amount=$orderamount;
                $dt[]=$key;
                $i++;
            }
        }
            $dt=$data;

        return new DataTableCollectionResource($dt);
    }

    function fetch_vendorlist($id)
    {

       return $query = Vendor::where('vendors.admin_id',$id)->join('users','users.admin_id','=','vendors.id')->select(['vendors.*','users.id as vendor_ad_id'])->get();
    }

}
