<?php

namespace App\Http\Controllers\API\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\User;
use App\UserAddress;
use App\Order;
use App\OrderProduct;
use App\UserDeviceToken;
use App\VerificationCode;
use App\CustomerAppVersionHash;
use Fcm;
use App\Utilities\SmsUtility;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
class ProfileController  extends Controller
{
    public function logon(Request $request)
    {
        $user = User::where(['mobile'=>$request->mobile])->first();
        if(isset($user))
        {
            $validator = \Validator::make($request->all(), ['mobile'=>'required|numeric|min:5','app_version'=>'sometimes|numeric']);
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
        
                $hash = CustomerAppVersionHash::where('version_code', $appVersion)->first();
                
                // if(isset($hash))
                // {
                //     $message.= ' '.$hash->hash;
                // }
                $data = [
                        'mobile' => $request->mobile,
                        'code' => $otp
                    ];
                    $id = VerificationCode::create($data)->id;
                    $sms = new SmsUtility;
                    $sms->sendmessage([$request->mobile], $message, 1);
                    return [
                        'verification_id'=>$id,
                        'message' => 'OTP generated successfully'
                    ];
        
        }
        else
        {
            return [
                'message' => 'User not found please register!!'
            ];
        }
        
        
    }

    public function verifyLogonOTP(Request $request)
    {
        $this->validate($request, [
            'verification_id' => 'required|numeric',
            'otp' => 'required|numeric|min:5',
        ]);
        $verification = VerificationCode::where(['id' => $request->verification_id, 'code'=>$request->otp])->first();
        $response =[];
        $response['success'] = false;
        if($verification){
            $response["registered"] = false;
            $response['success'] = true;
            $user = User::where(['mobile'=>$verification->mobile])->first();
            if($user){
               
                $response['user'] = $user;
                $response['registered'] = true;
            }
        }
        return $response;
    }

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'=> 'required',
            'email' => 'required|email|unique:users,email,'.$request->email,
            'mobile' => 'required|numeric|digits:10|unique:users,mobile,' . $request->mobile,
            'gender' => 'required|alpha']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['user_id'=>0,'message'=>$value], 422);
                }
            }
        }
        $data = $request->only('mobile','email','gender','name');
        $data['type'] = 'user';
        $id = User::create($data)->id;
        return [
            'user_id'=>$id,
            'message' => 'Registered successfully'
        ];
    }


    public function GetData($id)
    {
        $query = User::select('*')->where('mobile',$id)->get();
        $cnt=count($query);
        if($cnt==0)
        {
                return [
                    'resid' => 201,
                    'message' => 'Unsuccessful'
                ];
        }
        else
        {
            return [
                    'resid' => 200,
                    'message' => 'successful'
                ];
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrfail($id);
        $this->validate($request, [
            'name'=> 'required',
            'email' => 'required|email',
            'mobile' => 'required|numeric|digits:10',
            'gender' => 'required|alpha',
        ]);
        $data = $request->only('mobile','email','gender','name');
        $data['type'] = 'user';
        return [
            'success' => $user->update($data),
            'message' => 'Profile Updated successfully'
        ];
    }

    public function UpdateCustomer(Request $request, $id)
    {
        
     //  print_r( $user);exit;
        $this->validate($request, [
        'is_active' => 'sometimes',
        'images' => 'sometimes',
        'email' => 'required',
        'dob' => 'required',
        'date_of_retirement' => 'required',
        'date_of_joining' => 'required',
        'blood_group' => 'required',
        'state' => 'required',
        'district' => 'required',
        'pincode' => 'required|numeric',
        'employee_post' => 'required',
        'bukkle_no' => 'sometimes',
        'card_no' => 'sometimes',
        'mobile' => 'required|max:10|unique:users,mobile'. ',' .$id.',id',
        'name' => 'required',
        'vendor_id' => 'required',
        ]);
        $user = User::findOrfail($id);
        $oldImage = $user->image;   
       
        if($request->images){
            $name = time().'.' . explode('/', explode(':', substr($request->images, 0, strpos($request->images, ';')))[1])[1];
            \Image::make($request->images)->save(public_path('uploads/images/customer/').$name);
            $request->merge(['image' => $name]);
            $oldImage = public_path('uploads/images/customer/').$oldImage;
            if(file_exists($oldImage)){
                @unlink($oldImage);
            }
        }else{
            $request->merge(['image' => $oldImage]);
        }
       //  print_r($oldImage);exit;
       // print_r($request->image);exit;  
        // $data['type'] = 'user';
        $data = $request->only('email','type','vendor_id','name','mobile','card_no','bukkle_no','employee_post','pincode','district','state','blood_group','date_of_joining','date_of_retirement','image','identification_mark','dob','is_active');
      //  print_r($data);exit;
        return [
            'success' => $user->update($data),
            'message' => 'Profile Updated successfully'
        ];
    }
     
    public function DeletCustomer($id)
    {
        $product = User::findOrFail($id);
        $product->delete();
        // delete the product
        return [
            'message' => 'user Deleted !'
        ];
    }
    public function profile($id)
    {
        return User::findOrfail($id);
    }
     
    public function viewprofile($id)
    {
        $user=User::where('id',$id)->first();
        $useradd= UserAddress::where(['user_id'=>$id, 'is_active'=>1])->groupBy('title')->limit(3)->get();
        $orders=Order::where('user_id',$id)->join('order_statuses','orders.order_status_id','=','order_statuses.id')->where('amount','!=',0)->select(['orders.*','order_statuses.name as orderstatus'])->orderBy('orders.created_at', 'DESC')->get();
        $dataor=[];
        foreach ($orders as $key)
        {
            $orpro=OrderProduct::where('order_id',$key->id)->get();
             $count=count($orpro);
            
            if($count>1)
            {
                $proname=$orpro[0]->name.' ('.($count-1).'+ items )';
            }
            else
            {   
                if($count==0){
                    $proname='';
                 }else
                 {
                $proname=$orpro[0]->name;}
            }
            $key->proname=$proname;
            $dataor[]=$key;
        }

       return $array=['user'=>$user,'useraddress'=>$useradd,'orders'=>$dataor];
    }

    public function fcm(){
        // fcm()
        // ->to([]) // $recipients must an array
        // ->priority('high')
        // ->notification([
        //     'title' => 'Test FCM',
        //     'body' => 'This is a test of FCM',
        // ])
        // ->send();
        $sms = new SmsUtility;
        // $sms->sendMessage(['9827641944'], 'Laravel', 0);
        return[ 'success' => true, 'sms'=> $sms->sendMessage(['9827641944'], 'Laravel', 1) ];
    }

    function customersList(Request $request,$id)
    {
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        // echo $id;exit;
        if($id=="undefined")
        {
          $id=null;
        }
        
        $query = User::where('vendor_id',$id);
        if($searchValue!=''){
            $query->where('name', "LIKE", "%$searchValue%")->orWhere('mobile', "LIKE", "%$searchValue%")->orWhere('bukkle_no', "LIKE", "%$searchValue%");
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

    function customerlistdata(Request $request,$id)
    {
      
        $query = User::where('type','user')->where('vendor_id',$id)->whereNull('deleted_at');
        // if($searchValue!=''){
        //     $query->where('name', "LIKE", "%$searchValue%");
        // }
        return $query->get();
    }
    
    
    public function fetchcustomer(Request $request)
    {
       $user=User::where('is_active',1);
       $cno=$request->canteen;
       $bno=$request->bukkle;
       if(isset($request->customer_name))
       {
       $cnanem=$request->customer_name['id'];
       }
       else
       {
           $cnanem='';
       }
       $mo=$request->mobile;

       if($cno!='' || $bno!='' || $cnanem!='' || $mo!='')
       {

       if($cno!=''){
           $user->where('card_no',$cno);
       }
      
       if($bno!=''){
           $user->where('bukkle_no',$bno);
       }
 
       if($cnanem!=''){
        $user->where('id',$cnanem);
       }
       
       if($mo!=''){
           $user->where('mobile',$mo);
       }
       return $user->first();
      }
      else
      {
        $this->validate($request, [
            'user_id'=> 'required',
        ]);
      }
    }
   
    public function UpdateCustomerr()
    {
        $users=User::where('type','user')->get();
        foreach($users as $key)
        {
            $user = User::findOrfail($key->id);
            $data['password']=Hash::make($key->bukkle_no);
            $user->update($data);
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
        $user = User::where('mobile',$request->mobile)->first();
       // print_r($user);
        if(Hash::check($request->password,$user->password))
        { 
           if($request->only('device_token'))
           {
                UserDeviceToken::updateOrCreate(['user_id' =>$user->id, 'token' => $request->device_token]);
           }
                $response['user'] = $user;
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

    public function getCustomer($id)
    {
       return $user = User::where('id',$id)->first();
    }
    
    function addVcustomer(Request $request)
    { 
       $this->validate($request, [
        'is_active' => 'required',
        'email' => 'required',
        'dob' => 'required',
        'date_of_retirement' => 'required',
        'date_of_joining' => 'required',
        'blood_group' => 'required',
        'state' => 'required',
        'district' => 'required',
        'pincode' => 'required|numeric',
        'employee_post' => 'required',
        'bukkle_no' => 'required|numeric',
        'card_no' => 'required|numeric',
        'mobile' => 'required|max:10|unique:users,mobile',
        'name' => 'required',
        'vendor_id' => 'required',
      ]);
        if($request->image){
                $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                \Image::make($request->image)->save(public_path('uploads/images/customer/').$name);
                $request->merge(['image' => $name]);
        }
            $hashedPassword = Hash::make($request->bukkle_no);
            $product = new User();
            $product->vendor_id = $request->vendor_id;
            $product->name = ucwords($request->name);
            $product->email = $request->email;
            $product->mobile = $request->mobile;
            $product->card_no = $request->card_no;
            $product->bukkle_no = $request->bukkle_no;
            $product->employee_post = ucwords($request->employee_post);
            $product->pincode = $request->pincode;
            $product->district = ucwords($request->district);
            $product->state = ucwords($request->state);
            $product->blood_group = $request->blood_group;
            $product->date_of_joining = $request->date_of_joining;
            $product->date_of_retirement = $request->date_of_retirement;
            $product->dob = $request->dob;
            $product->identification_mark = ucwords($request->identification_mark);
            $product->image = $request->image;
            $product->password = $hashedPassword;
            $product->type = 'user';
            $product->is_active =1;
            $product->save();
    }

    public function forgetpassword(Request $request)
    {
        $user = User::where(['mobile'=>$request->mobile])->first();
        if(isset($user))
        {
            $validator = \Validator::make($request->all(), ['mobile'=>'required|numeric|min:10']);
                if ($validator->fails()) {
                   foreach($validator->errors()->toArray() as $errorvalue){
                        foreach ($errorvalue as $key => $value) {
                            return response()->json(['error_message'=>$value], 422);
                        }
                    }
                }
                $randno=rand('1111','9999');
                $data = [
                        'mobile' => $request->mobile,
                        'code' => $randno
                    ];
                    $id = VerificationCode::create($data)->id;
                    $sms = new SmsUtility;
                    $sms->forgetpassword($request->mobile,$randno);
                    return [
                        'verification_id'=>$id,
                        'message' => 'OTP Generated Successfully',
                        'status_code' => 200
                    ];
        
        }
        else
        {
            return [
                'message' => 'User not found please register!!',
                'status_code' => 402
            ];
        }
        
        
    }
    public function changepass(Request $request)
    {   
        $this->validate($request, [
            'user_id' => 'required',
            'password' => 'required',
        ]);
        $user = User::findOrfail($request->user_id);
        $hashedPassword = Hash::make($request->password);
        $request->merge(['password' => $hashedPassword]);
        $data = $request->only('password');
        
        return [
            'success' => $user->update($data),
            'message' => 'Password Updated successfully'
        ];
    }
    
    public function Logouttoken(Request $request)
    {   
        $this->validate($request, [
            'user_id' => 'required',
            'device_token' => 'required',
        ]);
        $user = UserDeviceToken::where('user_id',$request->user_id)->where('token',$request->device_token)->first();
             $user1 = UserDeviceToken::findOrfail($user->id);
        if($user1->delete())
        {
            return [
                'resid' => 200,
                'message' => 'Logout successfully'
            ];
        }
        else
        {
             return [
                'resid' => 201 ,
                'message' => 'Error'
            ];
        }
    }

    public function customercsv(Request $request)
    {
      $file = $request->file('csv');
      // File Details 
      $filename = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      $tempPath = $file->getRealPath();
    //   print_r($tempPath);
      $fileSize = $file->getSize();
      $mimeType = $file->getMimeType();
      $location = 'uploads';
      // Upload file
      $file->move(public_path($location),$filename);

      // Import CSV to Database
      $filepath = public_path($location."/".$filename);
      
      $file = fopen($filepath,"r");

          $importData_arr = array();
          $i = 0;

          while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
             $num = count($filedata );
             
             // Skip first row (Remove below comment if you want to skip the first row)
             /*if($i == 0){
                $i++;
                continue; 
             }*/
             for ($c=0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata [$c];
             }
             $i++;
          }
            fclose($file);
            if(count($importData_arr)>0)
            {
            for($i=1; $i < count($importData_arr);$i++)
            {
                        $product = new User();
                        $hashedPassword = Hash::make($importData_arr[$i][5]);
                        $product->vendor_id = $request->vendor_id;
                        $product->name = ucwords($importData_arr[$i][1]);
                        $product->email = $importData_arr[$i][2];
                        $product->mobile = $importData_arr[$i][3];
                        $product->card_no = $importData_arr[$i][4];
                        $product->bukkle_no = $importData_arr[$i][5];
                        $product->employee_post = ucwords($importData_arr[$i][6]);
                        $product->pincode = $importData_arr[$i][7];
                        $product->district = ucwords($importData_arr[$i][8]);
                        $product->state = ucwords($importData_arr[$i][9]);
                        $product->blood_group = $importData_arr[$i][10];
                        $product->date_of_joining = $importData_arr[$i][11];
                        $product->date_of_retirement = $importData_arr[$i][12];
                        $product->dob = $importData_arr[$i][14];
                        $product->identification_mark = ucwords($importData_arr[$i][13]);
                        $product->password = $hashedPassword;
                        $product->type = 'user';
                        $product->is_active =1;
                        $pp=$product->save();
            }
            }
        if($pp){
            $ddtc= [
                'message' => 'Customers Added Successfully',
                'resid'=>200
            ];
        }else{
            $ddtc=  [
                'message' => 'Error Occured',
                'resid'=>202
            ];
        }
       return json_encode($ddtc);
    }


}
