<?php

namespace App\Http\Controllers\API\Common;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DeliveryPerson;
use App\DeliveryCharges;
use App\DeliveryTiming;
use App\Order;
use App\User;
use App\Vendor;
use App\DeliveryPersonCommodity;
use App\VerificationCode;
use App\DeliveryAppVersionHash;
use App\DeliveryPersonDeviceToken;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Utilities\SmsUtility;
class DeliveryPersonController extends Controller
{
    var $rules = [
        'name'=>'required|string',
        'password'=>'required',
        'vendor_id'=>'required',
        'address'=>'required|string',
        'mobile'=>'required|string',
        'email'=>'required|string',
        'is_active'=>'required',
        'aadhar_no'=>'required',
        'acount_no'=>'required',
        'account_name'=>'required',
        'bank'=>'required|string',
        'ifsc'=>'required|string',
        'pincode'=>'required|string',
        'dob'=>'required|string',
        'district'=>'required|string',
        'state'=>'required|string',
        'blood_group'=>'required|string',
        'accverify'=>'required|string',
        'ifscverify'=>'required|string',
        'date_of_joining'=>'required|string',
        'identification_mark'=>'required|string',
        'delivery_boy_img'=>'required|string',
        'adhar_front_img'=>'required|string',
        'adhar_back_img'=>'required|string',
        'pan_img'=>'required|string',
        'pan_no'=>'required|string',
        'client_id'=>'required|string'
    ];
    
    var $fields = [ 'name','password','address','vendor_id','mobile', 'email','is_active','aadhar_no','acount_no','bank','ifsc','pincode','dob','district', 'state','blood_group', 'accverify','ifscverify','date_of_joining','identification_mark','delivery_boy_img','adhar_front_img','adhar_back_img','pan_img','pan_no','client_id','account_name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');

        $id=$_GET['ad'];
        $query= DeliveryPerson::where('vendor_id',$id);
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
                $key->srno=$i;
                $dt[]=$key;
                $i++;
            }
        }
            $dt=$data;

        return new DataTableCollectionResource($dt);
        
    }
    

    public function DeliveryList()
    { 
        $a=$_GET['a'];
        return DeliveryPerson::where('vendor_id',$a)->get();
    }
    


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  

        if($request->delivery_boy_img){
            $name = time().'_deli_.' . explode('/', explode(':', substr($request->delivery_boy_img, 0, strpos($request->delivery_boy_img, ';')))[1])[1];
            \Image::make($request->delivery_boy_img)->save(public_path('uploads/images/delivery/').$name);
            $request->merge(['delivery_boy_img' => $name]);
        }
        if($request->adhar_front_img){
            $name = time().'_af_.' . explode('/', explode(':', substr($request->adhar_front_img, 0, strpos($request->adhar_front_img, ';')))[1])[1];
            \Image::make($request->adhar_front_img)->save(public_path('uploads/images/delivery/').$name);
            $request->merge(['adhar_front_img' => $name]);
        }
        if($request->adhar_back_img){
            $name = time().'_ab_.' . explode('/', explode(':', substr($request->adhar_back_img, 0, strpos($request->adhar_back_img, ';')))[1])[1];
            \Image::make($request->adhar_back_img)->save(public_path('uploads/images/delivery/').$name);
            $request->merge(['adhar_back_img' => $name]);
        }
        if($request->pan_img){
            $name = time().'_pb_.' . explode('/', explode(':', substr($request->pan_img, 0, strpos($request->pan_img, ';')))[1])[1];
            \Image::make($request->pan_img)->save(public_path('uploads/images/delivery/').$name);
            $request->merge(['pan_img' => $name]);
        }
        $hashedPassword = Hash::make('123456');
        $request->merge(['password' => $hashedPassword]);
        $this->validate($request, $this->rules);
        $data = $request->only($this->fields);
        $deliveryPerson = DeliveryPerson::create($data)->id;
        return  ['success' =>  $deliveryPerson ? true : false ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DeliveryPerson::findOrFail($id);
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
        $deliveryPerson = DeliveryPerson::findOrFail($id);
        $this->validate($request, $this->rules);
        $data = $request->only($this->fields);
        return  ['success' => $deliveryPerson->update($data) ];
    }

    public function UpdateDeliveryBoy(Request $request, $id)
    {   
        // print_r($request->all());exit;
        $deliveryPerson = DeliveryPerson::findOrFail($id);
        $this->validate($request, $this->rules);
        $doldImage = $deliveryPerson->delivery_boy_img;
        if($request->delivery_boy_imgs){
            $name = time().'_deli_.' . explode('/', explode(':', substr($request->delivery_boy_imgs, 0, strpos($request->delivery_boy_imgs, ';')))[1])[1];
            \Image::make($request->delivery_boy_imgs)->save(public_path('uploads/images/delivery/').$name);
            $request->merge(['delivery_boy_img' => $name]);
            $doldImage = public_path('uploads/images/delivery/').$doldImage;
            if(file_exists($doldImage)){
                @unlink($doldImage);
            }
        }else{
            $request->merge(['delivery_boy_img' => $doldImage]);
        }
        // 
        $daoldImage = $deliveryPerson->adhar_front_img;
        if($request->adhar_front_imgs){
            $name = time().'.' . explode('/', explode(':', substr($request->adhar_front_imgs, 0, strpos($request->adhar_front_imgs, ';')))[1])[1];
            \Image::make($request->adhar_front_imgs)->save(public_path('uploads/images/delivery/').$name);
            $request->merge(['adhar_front_img' => $name]);
            $daoldImage = public_path('uploads/images/delivery/').$daoldImage;
            if(file_exists($daoldImage)){
                @unlink($daoldImage);
            }
        }else{
            $request->merge(['adhar_front_img' => $daoldImage]);
        }
        //
        $dsoldImage = $deliveryPerson->adhar_back_img;
        if($request->adhar_back_imgs){
            $name = time().'.' . explode('/', explode(':', substr($request->adhar_back_imgs, 0, strpos($request->adhar_back_imgs, ';')))[1])[1];
            \Image::make($request->adhar_back_imgs)->save(public_path('uploads/images/delivery/').$name);
            $request->merge(['adhar_back_img' => $name]);
            $dsoldImage = public_path('uploads/images/delivery/').$dsoldImage;
            if(file_exists($dsoldImage)){
                @unlink($dsoldImage);
            }
        }else{
            $request->merge(['adhar_back_img' => $dsoldImage]);
        }
        //
        $dssoldImage = $deliveryPerson->pan_img;
        if($request->delivery_boy_imgs){
            $name = time().'.' . explode('/', explode(':', substr($request->pan_imgs, 0, strpos($request->pan_imgs, ';')))[1])[1];
            \Image::make($request->pan_imgs)->save(public_path('uploads/images/delivery/').$name);
            $request->merge(['pan_img' => $name]);
            $doldImage = public_path('uploads/images/delivery/').$dssoldImage;
            if(file_exists($dssoldImage)){
                @unlink($dssoldImage);
            }
        }else{
            $request->merge(['pan_img' => $dssoldImage]);
        }
        $data = $request->only($this->fields);
        return  ['success' => $deliveryPerson->update($data) ];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deliveryPerson = DeliveryPerson::findOrFail($id);
        return  ['success' => $deliveryPerson->delete() ];
    }

    public function logon(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|numeric|min:5',
            'app_version' => 'sometimes|numeric',
        ]);
        $otp = rand(10000,99999);
        // $otp = 12345;
        $message = 'Your one time password to use in ocean app is '.$otp;

        $appVersion = isset($request->app_version) ? $request->app_version : 1;

        $hash = DeliveryAppVersionHash::where('version_code', $appVersion)->first();

        if($hash){
            $message.= ' '.$hash->hash;
        }
        $data = [
            'mobile' => $request->mobile,
            'code' => $otp
        ];
        $id = VerificationCode::create($data)->id;
        $sms = new SmsUtility;
        $sms->sendmessage([$request->mobile], $message, 1);
        $id = VerificationCode::create($data)->id;
        return [
            'verification_id'=>$id,
            'message' => 'OTP generated successfully'
        ];
    }

    public function verifyLogonOTP(Request $request)
    {
        $this->validate($request, [
            'verification_id' => 'required|numeric',
            'otp' => 'required|numeric|min:5',
            'device_token' => 'sometimes|string'
        ]);
        $verification = VerificationCode::where(['id' => $request->verification_id, 'code'=>$request->otp])->first();
        $response =[];
        $response['success'] = false;
        if($verification){
            $response["registered"] = false;
            $response['success'] = true;
            $deliveryPerson = DeliveryPerson::where(['mobile'=>$verification->mobile, 'is_active'=> 1])->first();
            // echo $verification->mobile;
            // print_r($deliveryPerson);
            // exit;
            if($deliveryPerson){
                if($request->only('device_token'))
                    DeliveryPersonDeviceToken::updateOrCreate(['delivery_person_id' =>$deliveryPerson->id, 'token' => $request->device_token]);
                $response['delivery_person'] = $deliveryPerson;
                $response['registered'] = true;
            }
        }
        return $response;
    }

    public function statusUpdate(Request $request, $id)
    {
        $deliveryPerson = DeliveryPerson::findOrFail($id);
        $this->validate($request, [
            'available'=> 'required|boolean'
        ]);
        $data = $request->only('available');
        $updated = $deliveryPerson->update($data);
        return [
            'success' => $updated ? true : false,
            'message' => $updated ? 'Updated Successfully' : 'Error while updating'
        ];
    }
    
    public function TopDeliveryBoys()
    {
        $query = DB::table('orders')
        ->join('delivery_people', 'delivery_people.id','=', 'orders.delivery_person_id')
        ->select('delivery_people.name')->groupBy('orders.delivery_person_id')->get();
        $i=10;
        foreach ($query as $ata)
        {
            $ata1['name']=$ata->name;
            $ata1['point']=$i*10;
             $ata1['img']='';
            $data[]=$ata1;
            $i--;
        }
        return $data;
    }
    
    public function LatLong(Request $request, $id)
    { 
        
        $deliveryPerson = DeliveryPerson::findOrFail($id);
        $this->validate($request, [
            'lat'=> 'required',
            'long'=> 'required'
        ]);
        $data['lat'] = $request->lat;
        $data['longi'] = $request->long;
        $updated = $deliveryPerson->update($data);
        return [
            'success' => $updated ? true : false,
            'message' => $updated ? 'Updated Successfully' : 'Error while updating'
        ];
    }
    
    public function getDeliverBoyStatus($deliveryBoyId)
    {
        $deliveryPerson = DeliveryPerson::selectRaw("id,name,is_active,available")->where('id',$deliveryBoyId)->first();
        if(!empty($deliveryPerson))
        {
            return $deliveryPerson;
            
        }else{
            return [
            'message' => 'Invalid delivery boy Id'
            ];
        }
        
    }
    
    function LoginCheck(Request $request)
    {   
       // $user = User::where(['mobile'=>$request->mobile])->first();
        $validator = \Validator::make($request->all(), ['mobile'=>'required','password'=>'required']);
        if ($validator->fails()) {
          foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
         $user = DeliveryPerson::where('delivery_people.mobile',$request->mobile)->join('users','users.id','=','delivery_people.vendor_id')->join('vendors','vendors.id','=','users.admin_id')->select('delivery_people.*','vendors.name as vendor_name')->first();

        if(Hash::check($request->password,$user->password))
        { 
           if($request->only('device_token'))
           {
                DeliveryPersonDeviceToken::updateOrCreate(['delivery_person_id' =>$user->id, 'token' => $request->device_token]);
           }
                $product = new DeliveryTiming();
                $product->delivery_person_id=$user->id;
                $product->date=date('Y-m-d');
                $product->start_time=date('Y-m-d H:i:s');;
                $product->	is_active=1;
                 $product->save();
                $ifdx = DeliveryTiming::where('delivery_person_id',$user->id)->where('is_active',1)->orderBy('id','desc')->get();
                $aorder=Order::where('delivery_person_id',$user->id)->whereRaw("delivery_status_id!=3")->get();
                $acnt=count($aorder);
                $corder=Order::where('delivery_person_id',$user->id)->where('delivery_status_id',3)->get();
                $ccnt=count($corder);
                $tt=$acnt+$ccnt;
                $response['delivered'] = $ccnt;
                $response['actived'] = $acnt;
                $response['total'] = $tt;
                $response['user'] = $user;
                $response['login_details'] = $ifdx;
                $response['msg'] = true;
         
           return $response;
        }
        else
        {
          return [
            'msg' =>'Please Check Credential',
            'redid' =>'201',
         ];
        }
         
    }
    
    function gerprofile($id)
    {            
                $response=[];
                $user = DeliveryPerson::where('delivery_people.id',$id)->join('users','users.id','=','delivery_people.vendor_id')->join('vendors','vendors.id','=','users.admin_id')->select('delivery_people.*','vendors.name as vendor_name')->first();
                $ifdx = DeliveryTiming::where('delivery_person_id',$user->id)->where('is_active',1)->orderBy('id','desc')->first();
                $aorder=Order::where('delivery_person_id',$user->id)->whereRaw("delivery_status_id!=3")->get();
                $acnt=count($aorder);
                $corder=Order::where('delivery_person_id',$user->id)->where('delivery_status_id',3)->get();
                $ccnt=count($corder);
                $tt=$acnt+$ccnt;
                $response['delivered'] = $ccnt;
                $response['actived'] = $acnt;
                $response['total'] = $tt;
                
                $response['user'] = $user;
                $response['login_details'] = $ifdx;
                $response['msg'] = true;
                 return $response;
    }
    
    function Getdeliverytype($id,$pin)
    {   
        
         $add= DeliveryCharges::where('pincode',$pin)->where('vendor_id',$id)->where('status',1)->get();
         $user=User::where('id',$id)->first();
         if(isset($user)){
           $vendor=Vendor::where('id',$user->admin_id)->first();
          }
              $dt=array(); 
              $data=array();
              foreach($add as $key)
              {   
              $abc['id']=$key->id;
              $abc['admin_id']=$key->admin_id;
              $abc['vendor_id']=$key->vendor_id;
              $abc['type']=$key->type;
              $abc['description']='आपको आपके घर या ऑफिस में डिलीवरी दी जाएगी। ';
               $abc['pincode']=$key->pincode;
              $abc['start_limit']=$key->start_limit;
              $abc['end_limit']=$key->end_limit;
              $abc['delivery_charge']=round($key->delivery_charge);
              $abc['extra_charges_per_km']=$key->extra_charges_per_km;
              $abc['status']=$key->status;
              $abc['created_at']=$key->created_at;
              $abc['updated_at']=$key->updated_at;
              $dt[]=$abc;
          }
          if(isset($user) && isset($vendor))
          {
               $data[]=array(
                    "id"=>0,
                    "admin_id"=> "0",
                    "vendor_id"=> $id,
                    "type"=>"Pickup",
                    "description"=>"आप घर से आर्डर करके , सामान लेने आ सकते हो। ",
                    "pincode"=> $vendor->pincode,
                    "start_limit"=> "",
                    "end_limit"=> "",
                    "delivery_charge"=>round($vendor->pickup_charges) ,
                    "extra_charges_per_km"=> "",
                    "status"=>"1",
                    "created_at"=>"0000-00-00 00:00:00",
                    "updated_at"=>"0000-00-00 00:00:00"
                );
                $data[]=array(
                    "id"=>0,
                    "admin_id"=> "0",
                    "vendor_id"=> $id,
                    "type"=>"Counter Billing",
                     "description"=>"जब आप कैंटीन में हो तब ही इस ऑप्शन का इस्तेमाल करे। ",
                    "pincode"=> $vendor->pincode,
                    "start_limit"=> "",
                    "end_limit"=> "",
                    "delivery_charge"=>0,
                    "extra_charges_per_km"=> "",
                    "status"=>"1",
                    "created_at"=>"0000-00-00 00:00:00",
                    "updated_at"=>"0000-00-00 00:00:00"
                );
          }
              // print_r($data);
              if(count($add)>0)
              {
                  $dax=array_merge($dt,$data);
              }
              else
              {
                  $dax=$data;
              }
              if(count($dax)>0)
              {
                   return $dax;
              }
              else
              {
                  return array(
                      'resid'=>201,
                      'msg'=>'No Data Found'
                      );
              }
             
//         $a2=array("admin_id"=>"0","vendor_id"=>$id,"type"=>'Pickup',"vendor_id"=>$id,'pincode');
// print_r();
    }
    
    public function GoOnline(Request $request, $id)
    {
        $deliveryPerson = DeliveryPerson::findOrFail($id);
        $this->validate($request, [
            'available'=> 'required|boolean'
        ]);
        $av=$request->available;
        $data = $request->only('available');
        $updated = $deliveryPerson->update($data);
        if($av==1)
        {  
             $ifd = DeliveryTiming::where('delivery_person_id',$id)->where('is_active',1)->whereRaw('end_time != NULL')->get();
             if(count($ifd)>0)
             {
                 foreach($ifd as $key)
                 {
                    DeliveryTiming::where(['id'=>$key->id])->update(['is_active'=>0]);
                 }
             }
            $datax=array(
                'delivery_person_id'=>$id,
                'date'=>date('Y-m-d'),
                'start_time'=>date('Y-m-d H:i:s'),
                'is_active'=>1,
                );
            $timing = DeliveryTiming::create($datax)->id;
        }
        else
        {
         $ifdx = DeliveryTiming::where('delivery_person_id',$id)->where('is_active',1)->orderBy('id','desc')->first();
         if($ifdx)
         {   
             $idp=$ifdx->id;
             $start=$ifdx->start_time;
              $cp1=strtotime($start);
              $op2  = strtotime(date('d-m-Y H:i:s'));
              $dt2=date('Y-m-d H:i:s');
             $min=round(abs($cp1-$op2),2);
             $min2=round($min/60,2);
              //exit;
            //exit;
             DeliveryTiming::where(['id'=>$idp])->update(['end_time'=>$dt2,'time'=>$min2]);
         }
           
        }
        return [
            'success' => $updated ? true : false,
            'message' => $updated ? 'Updated Successfully' : 'Error while updating'
        ];
    }
}
