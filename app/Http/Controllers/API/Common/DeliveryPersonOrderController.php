<?php
namespace App\Http\Controllers\API\Common;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\DeliveryPerson;
use App\DeliveryStatus;
use App\DeliveryCharges;
use App\VendorStatus;
use App\OrderStatus;
use App\OrderProduct;
use App\ProductImage;
use App\OrderAddress;
use App\UserDeviceToken;
use App\VendorDeviceToken;
use App\DeliveryPersonDeviceToken;
use App\DeliveryPersonWallet;
use App\MarketingPersonWallet;
use App\VendorWallet;
use App\Vendor;
use App\VerificationCode;
use App\User;
use App\FranchiseeReport;
use App\FranchiseeWallet;
use DB;
use Fcm;
use App\Utilities\SmsUtility;
use App\Utilities\FirebaseCloudMessagingUtility;
use App\OrderPayment;
use App\OrderMethod;
use App\PaymentMethod;
//error_reporting(1);
use PDF;
use Mail;
class DeliveryPersonOrderController extends Controller
{
    public function index( int $deliveryPersonId)
    {
        $pro=[];
        $query = DB::table('orders')
        ->join('users', 'user_id','=', 'users.id')
        ->join('order_payments', 'order_payments.order_id', '=', 'orders.id')
        ->join('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
        //->join('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
        ->join('order_payment_methods', 'order_payment_methods.order_payment_id', '=', 'order_payments.id')
        ->join('order_payment_method_responses', 'order_payment_method_responses.order_payment_method_id', '=', 'order_payment_methods.id')
        ->join('payment_methods', 'order_payment_methods.payment_method_id', '=', 'payment_methods.id')
        ->join('order_addresses', 'order_addresses.order_id', '=', 'orders.id')
        ->where('payment_status', 1)
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*','users.name as user_name','order_statuses.name as order_status_name','users.mobile as user_mobile', 'payment_methods.name as pg_name','payment_methods.name as pg_name','order_payment_method_responses.payment_mode as method_pay','order_payment_method_responses.transaction_uid as transaction_id','order_addresses.address as delivery_address','order_addresses.mobile as recipient_mobile','order_addresses.name as recipient_name','order_addresses.title as delivery_type','order_addresses.lat as latitude','order_addresses.long as longitude','order_addresses.state as state','order_addresses.district as district','order_addresses.pincode as pincode');

        

        $type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : 'all';
        switch ($type) {
            case 'active':
                $query->where('delivery_person_id',$deliveryPersonId)->whereRaw("delivery_status_id != 3");
                break;
            case 'delivered':
                $deliveredStatusId = DeliveryStatus::where('code', 'delivered')->pluck('id')->first();
                //echo $deliveryPersonId;
                $query->where(['delivery_person_id' => $deliveryPersonId, 'delivery_status_id' => $deliveredStatusId ]);
            default:
                $query->where('delivery_person_id',$deliveryPersonId);
            break;
        }
         
       $pro= $query->get();
       $ppro=[];
      
       $spro=[];
       foreach($pro as $key)
       {
          $key->invoice='INV'.$key->id; 
          $orpo=OrderProduct::join('colors','colors.id', '=', 'order_products.color')->join('sizes','sizes.id', '=', 'order_products.size')->where('order_products.order_id',$key->id)->select(['order_products.*','colors.name as color_name','sizes.name as size_name'])->get();
          $spro=[];
          foreach($orpo as $kky)
          {
             $prro=ProductImage::select(['name'])->where('product_id',$kky->product_id)->orderBy('id','desc')->first();
             $proname=$prro->name;
             $kky->pro_image=$proname;
             $spro[]=$kky;
          }
          $vendors=Vendor::join('users','vendors.id','=','users.admin_id')->where('users.id',$key->vendor_id)->first();
          $key->vendor_name=$vendors->name;
          $key->products=$spro;
          $ppro[]=$key;
       }
       
       if(count($ppro)>0)
       {
       return $ppro;
       }
       else
       {
             $response['msg'] = 'No Data Found';
             return json_encode($response);
       }
 
    }
    
    
    public function OrderDetails(int $id)
    {
       
        $query = DB::table('orders')
        ->join('users', 'user_id','=', 'users.id')
        ->join('order_payments', 'order_payments.order_id', '=', 'orders.id')
        ->join('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
        ->join('order_payment_methods', 'order_payment_methods.order_payment_id', '=', 'order_payments.id')
        ->join('order_payment_method_responses', 'order_payment_method_responses.order_payment_method_id', '=', 'order_payment_methods.id')
        ->join('payment_methods', 'order_payment_methods.payment_method_id', '=', 'payment_methods.id')
        ->join('order_addresses', 'order_addresses.order_id', '=', 'orders.id')
        ->where('payment_status', 1)
        ->where('orders.id',$id)
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*','users.name as user_name','order_statuses.name as order_status_name','users.mobile as user_mobile', 'payment_methods.name as pg_name','payment_methods.name as pg_name','order_payment_method_responses.payment_mode as method_pay','order_payment_method_responses.transaction_uid as transaction_id','order_addresses.address as delivery_address','order_addresses.mobile as recipient_mobile','order_addresses.name as recipient_name','order_addresses.title as delivery_type','order_addresses.lat as latitude','order_addresses.long as longitude','order_addresses.state as state','order_addresses.district as district','order_addresses.pincode as pincode');

        

        // $type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : 'all';
        // switch ($type) {
        //     case 'active':
        //         $query->where('delivery_person_id',$deliveryPersonId)->whereRaw("delivery_status_id = 1");
        //         break;
        //     case 'delivered':
        //         $deliveredStatusId = DeliveryStatus::where('code', 'delivered')->pluck('id')->first();
        //         $query->where(['delivery_person_id' => $deliveryPersonId, 'delivery_status_id' => $deliveredStatusId ]);
        //     default:
        //         $query->where('delivery_person_id',$deliveryPersonId);
        //     break;
        // }
         
       $pro= $query->get();
       $ppro=[];
       foreach($pro as $key)
       {
          $key->invoice='INV'.$key->id; 
          $orpo=OrderProduct::join('colors','colors.id', '=', 'order_products.color')->join('sizes','sizes.id', '=', 'order_products.size')->where('order_products.order_id',$key->id)->select(['order_products.*','colors.name as color_name','sizes.name as size_name'])->get();
          $spro=[];
          foreach($orpo as $kky)
          {
             $prro=ProductImage::select(['name'])->where('product_id',$kky->product_id)->first();
             $proname=$prro->name;
             $kky->pro_image=$proname;
             $spro[]=$kky;
          }
          $key->products=$spro;
          $vendors=Vendor::join('users','vendors.id','=','users.admin_id')->where('users.id',$key->vendor_id)->first();
          $key->vendor_name=$vendors->name;
          $ppro[]=$key;
       }
       
       if(count($ppro)>0)
       {
       return $ppro;
       }
       else
       {
             $response['msg'] = 'No Data Found';
             return json_encode($response);
       }
 
    }

    public function deliverytime($deliveryPersonId, $id)
    {   
      $queryx = DB::table('orders')->where('delivery_person_id',$deliveryPersonId)
        ->join('users', 'user_id','=', 'users.id')
        ->where('payment_status', 1)
        ->where('delivery_status_id', 2)
        ->where('orders.id',$id)
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*','users.name', 'users.email', 'users.mobile');

        $datax=$queryx->get();
        
        if(count($datax)>0)
        {
          if($datax[0]->delivery_slab_time=='')
          {
          $order = Order::findOrFail($id);
          $queryData['delivery_slot'] = $datax[0]->delivery_slot;
          $selectedTime = date('Y-m-d H:i:s');
          $endTime = strtotime("+".$datax[0]->delivery_slot." minutes", strtotime($selectedTime));
          $queryData['delivery_slab_time'] = date('Y-m-d H:i:s', $endTime);
          $updated = $order->update($queryData);
          if($updated)
          {
              $query = DB::table('orders')->where('delivery_person_id',$deliveryPersonId)
            ->join('users', 'user_id','=', 'users.id')
            ->where('payment_status', 1)
            ->where('delivery_status_id', 2)
            ->where('orders.id',$id)
            ->orderBy('orders.id', 'DESC')
            ->select('orders.*','users.name', 'users.email', 'users.mobile');
             $data=$query->get();
            $time=$data[0]->delivery_slab_time;
            $ttime=strtotime($time);
            $ctime=strtotime(date('Y-m-d H:i:s'));
            $min=$ttime-$ctime;
             $fmin=gmdate("i:s", $min);
            $minutes = $min/60;
            if($minutes<0){$fmin=0;} 
            $dataa['id']=$data[0]->id;
            $dataa['min']=$fmin;
          }
          }
          else
          {
            $time=$datax[0]->delivery_slab_time;
            $ttime=strtotime($time);
            $ctime=strtotime(date('Y-m-d H:i:s'));
            $min=$ttime-$ctime;
             $fmin=gmdate("i:s", $min);
            $minutes = $min/60;
            if($minutes<0){$fmin=0;} 
            $dataa['id']=$datax[0]->id;
            $dataa['min']=$fmin;
          }
        } 
        else
        {
            $dataa['id']=$id;
            $dataa['min']=.0; 
        }
           
        return $dataa;
    } 
   
    public function show($deliveryPersonId, $id)
    {
        $order = DB::table('orders')
        ->join('users', 'user_id','=', 'users.id')
        ->where('payment_status', 1)
        ->whereRaw('orders.id = '.$id)
        ->select('orders.*','users.name as user_name', 'users.email as user_email', 'users.mobile as user_mobile')->first();
        if(!$order){
            return [
                'success' => false,
                'message' => "invalid Request",
            ];
        }

        $status = DeliveryStatus::where('id', $order->delivery_status_id);
        $vendor = Vendor::where('id', $order->vendor_id)->first();
        $address = OrderAddress::where('order_id',$id)->first();
        $products = DB::table('order_products')->where('order_id',$id)
        ->join('products', 'products.id', '=', 'order_products.product_id')->get();
        //delivery charge
        $latitude = $address->lat;
        $longitude= $address->long;
        
        $get_distance = Vendor::selectRaw("latitude, longitude,
                     ( 6371 * acos( cos( radians(?) ) *
                       cos( radians( latitude ) )
                       * cos( radians( longitude) - radians(?)
                       ) + sin( radians(?) ) *
                       sin( radians( latitude ) ) )
                     ) AS distance", [$latitude, $longitude, $latitude])
        ->where('id',$order->vendor_id)
        ->first();
        $delivery_distance = ceil($get_distance->distance)*1000;
        $deliver_charges = DeliveryCharges::selectRaw("*,min(delivery_charge) as delivery_charge_applied")
       ->where('start_limit','<=',$delivery_distance)
        ->where('end_limit','<=',$delivery_distance)
        ->first();
        $extra_charges = ($delivery_distance-$deliver_charges->end_limit);
        if($extra_charges>0)
        {
            $total_deliver_charge = (($extra_charges/1000)*$deliver_charges->extra_charges_per_km)+$deliver_charges->delivery_charge;
        }
        else{
            $total_deliver_charge = $deliver_charges->delivery_charge;
        }
        //dd($deliver_charges);
           
        $payment_method =  DB::table('order_payments')->where('order_payments.order_id', $id)
        ->join('order_payment_methods', 'order_payment_methods.order_payment_id', '=', 'order_payments.id')
        ->join('payment_methods', 'order_payment_methods.payment_method_id', '=', 'payment_methods.id')->select('payment_methods.*')->first();

        return compact(
            'order',
            'address',
            'products',
            'status',
            'vendor',
            'payment_method',
            'total_deliver_charge'
        );
    }
    
    public function dailyorder(int $deliveryPersonId)
    {
        $order = DB::table('orders')
        ->where('date', date('Y-m-d'))
         ->where('delivery_status_id', 3)
         ->where('delivery_person_id', $deliveryPersonId)
        ->get();
        //print_r($order);
        if(count($order)==0)
        {
           return []; 
        }
        else
        {   $i=1;
            foreach($order as $key)
            {
                $data['billing_amt']=$key->amount;
                $data['id']=$i;
                if($key->earnings=='')
                {
                  $earn=0;
                }
                else
                {
                    $earn=intval($key->earnings);
                }
                $data['earnings']=$earn;
                //$data['dd']=$i/5;
                $i++;
                 $dataa[]=$data;
            }
           
            return $dataa;
        }
        
    }

    public function update(Request $request, $deliveryPersonId , $id)
    {   
        // echo $deliveryPersonId;  
      
        $order = Order::findOrFail($id);
        if($request->delivery_status_id==1 && $order->delivery_status_id!=null)
        {
           return [
            'success' =>  false,
            'message' =>  'Already Accepted',
           ]; 
        }
        else
        {
        $date=date('Y-m-d');
        $earn=0;
        if($order->delivery_person_id != $deliveryPersonId && $order->delivery_person_id != null){
            return [
                'success' => false,
                'message' => 'Order does not belong to delivery Person'
            ];
        }
        $this->validate($request, [
            'delivery_status_id' => 'required|numeric',
            'delivery_slab'=>'sometimes',
        ]);

        $deliveryStatus = DeliveryStatus::findOrFail($request->delivery_status_id);
        $deliveryStatusId = $deliveryStatus->id;
        
        $orderStatusId=null;
        //getiing diffrent status based on deliveryPerson status
        $userToken = UserDeviceToken::where('user_id', $order->user_id)->get();
        $user = User::find($order->user_id);
        if ($deliveryStatus->code == 'delivered') 
        {
            $orderStatus = OrderStatus::where('code', 'delivered')->first();
            $orderStatusId= $orderStatus->id;
            if(!empty($userToken))
            $this->pushNotification($userToken, $orderStatus->name ,'#'. $order->id. ' '.$orderStatus->description,3);
            $orderAmount =  DB::table('order_products')->where('order_id', $order->id)->selectRaw('SUM( price * qty) as vendor_total, SUM( (price - (price * (discount * 0.01)) ) * qty) as item_total')->first();
            $delveryAmount = $order->amount - $orderAmount->item_total;
            // echo  $order->amount;
            // exit;
            //  exit;
            // dd($vendorAmount);
           $vlastbal=VendorWallet::where('vendor_id', $order->vendor_id)->select('new_balance')->orderBy('id','DESC')->get();
            if(count($vlastbal)==0)
            {
               $newbal=$order->cost_price_amount+0;
           }
            else
            {
               $lastvendor=$vlastbal[0]->new_balance;
               $newbal=$order->cost_price_amount+$lastvendor;
            }
          
            VendorWallet::create(['description' => 'Order '.$order->id.' payment credited','amount' => $order->cost_price_amount, 'vendor_id' => $order->vendor_id,'new_balance'=>$newbal]);
            $dlastbal=DeliveryPersonWallet::where('delivery_person_id', $deliveryPersonId)->where('is_collectable', '0')->select('new_balance')->orderBy('id','DESC')->get();
            if(count($dlastbal)==0)
            {
              $newbald=$order->delivery_charges_for_cust+0;
           }
            else
            {
               $lastdel=$dlastbal[0]->new_balance;
               $newbald=$order->delivery_charges_for_cust+$lastdel;
            }
            if($earn!='' && $earn!='0' && $earn!='0.00')
            {  
               $newbald1=$newbald+$earn;
               $amt=$order->delivery_charges_for_cust+$earn;
                DeliveryPersonWallet::create(['description' => 'Order '.$order->id.' payment credited with incentive','delivery_charges_for_cust'=>$amt, 'amount' => $amt, 'delivery_person_id'=>$deliveryPersonId,'new_balance'=>$newbald1]);
           }
            else
            {
               DeliveryPersonWallet::create(['description' => 'Order '.$order->id.' payment credited','delivery_charges_for_cust'=>$order->delivery_charges_for_cust, 'amount' => $order->delivery_charges_for_cust, 'delivery_person_id'=>$deliveryPersonId,'new_balance'=>$newbald]);
           }
            // $venidids=Vendor::where('id',$order->vendor_id)->first();
            // // print_r( $venidids);
            // // exit;
         

            if($order->marketing_person_id){
                MarketingPersonWallet::create([
                        'description' => 'Order '.$order->id." order amount". $order->amount." marketing commmission credited. Customer: ".$user->name,
                        'amount' => $order->marketing_charges,
                        'marketing_person_id'=>$order->marketing_person_id
                ]);
            }

            $vendorToken = VendorDeviceToken::where('vendor_id', $order->vendor_id)->pluck('token')->toArray();
            // dd($vendorToken);
            if($vendorToken){
             //  $fcm =$this->pushNotification($vendorToken, $orderStatus->name, '#'. $order->id.' '. $orderStatus->description.' Delivery Slot :'.$request->delivery_slot,2);
            }

            $orderPaymentMethod = OrderPayment::where('order_id',$id)->leftJoin('order_payment_methods', 'order_payment_methods.order_payment_id', '=', 'order_payments.id')->leftJoin('payment_methods', 'order_payment_methods.payment_method_id', '=', 'payment_methods.id')->select('payment_methods.*')->first();
          
        //   echo $earn;
        //   EXIT;
        //   if($earn!='' && $earn!='0' && $earn!='0.00')
        //   {  
        //       $newbald1=$newbald+$earn;
        //     //   print_r($newbald1);
        //     // exit;
        //       // DeliveryPersonWallet::create(['description' => 'Incentive of '.$order->id.' Payment credited', 'is_collectable'=> 0, 'amount' => $earn,'delivery_charges_for_cust'=>$earn,'delivery_person_id'=>$deliveryPersonId,'new_balance'=>$newbald1]);
        //   }
            if(isset($orderPaymentMethod->is_postpaid) && $orderPaymentMethod->is_postpaid){
               
               $dlastbal1=DeliveryPersonWallet::where('delivery_person_id', $deliveryPersonId)->where('is_collectable', '1')->select('new_balance')->orderBy('id','DESC')->get();
                // print_r($order->delivery_charges_for_cust);
                // exit;
               if(count($dlastbal1)==0)
               {
                   $newbald1=$order->amount+0;
               }
                else
                {
                    $lastdel1=$dlastbal1[0]->new_balance;
                   $newbald1=$order->amount+$lastdel1;
                }
               
               
                DeliveryPersonWallet::create(['description' => 'Order '.$order->id.' cash collected', 'is_collectable'=> 1, 'amount' => $order->amount,'delivery_charges_for_cust'=>$order->delivery_charges_for_cust,'delivery_person_id'=>$deliveryPersonId,'new_balance'=>$newbald1]);
            }
            $userToken = UserDeviceToken::where('user_id', $order->user_id)->pluck('token')->toArray();
            if($userToken)
                   $this->pushNotification($userToken, $orderStatus->name ,'#'. $order->id. ' '.$orderStatus->description,3);
            //$this->mail($id);

        }

         
        $queryData = $request->only('delivery_status_id');
        if($deliveryStatus->code =='accepted'){
            $queryData['delivery_person_id'] = $deliveryPersonId;
        }
        if($orderStatusId){
            $queryData['order_status_id'] = $orderStatusId;
        }
        if($deliveryStatusId){
            $queryData['delivery_status_id'] = $order->vendor_status_id == 3 && $deliveryStatusId==1 ? 6 : $deliveryStatusId;
        }

        $queryData['status_updated'] = date('Y-m-d H:i:s');
        if($deliveryStatusId==1)
        {
        $queryData['delivery_slot'] = $request->delivery_slab;
        $selectedTime = date('Y-m-d H:i:s');
        $endTime = strtotime("+".$request->delivery_slab." minutes", strtotime($selectedTime));
        $queryData['delivery_slab_time'] = date('Y-m-d H:i:s', $endTime);
        $queryData['delivery_accept_time'] = date('Y-m-d H:i:s');
        }
        elseif($deliveryStatusId==3)
        {
            $selectedTime = date('Y-m-d H:i:s');
            $queryData['delivered_time'] = date('Y-m-d H:i:s');  
        } 
        //exit;
        $queryData['earnings'] = $earn;
        
        $updated = $order->update($queryData);
        return [
            'success' => $updated ? true : false,
            'message' => $updated ? 'Updated' : 'Something went wrong',
        ];
        }
    }

    public function createPickedOTP($deliveryPersonId, $orderId)
    {
        $vendor = DB::table('orders')->where(['orders.id' => $orderId, 'delivery_person_id'=>$deliveryPersonId])->join('vendors', 'vendors.id', '=', 'orders.vendor_id')->select('vendors.*')->first();
        // dd($vendor);
        if(!$vendor || !$vendor->contact_no){
            return [
                'success' => false,
                'message' => 'Invalid Order or delivery boy or vendor'
            ];
        }

        $otp = rand(10000,99999);
        $message = 'Your one time password to use in JUSTALO is '.$otp;
        $data = [
            'mobile' => $vendor->contact_no,
            'code' => $otp
        ];
        $id = VerificationCode::create($data)->id;
        $sms = new SmsUtility;
        $sms->sendmessage([$vendor->contact_no], $message, 1);
        return [
            'verification_id'=>$id,
            'message' => 'OTP generated successfully'
        ];
    }

    /*public function verifyPickedOTP(Request $request, $deliveryPersonId ,$orderId){
        $this->validate($request, [
            'verification_id' => 'required|numeric',
            'otp' => 'required|numeric|min:5',
        ]);

        $order = Order::where(['id'=>$orderId, 'delivery_person_id'=>$deliveryPersonId])->first();
        if(!$order){
            return  [
                'success' => false,
                'message' => "Order Does not belong to Deliverry person or Order doesn't Exist",
                'updated' => false
            ];
        }
        $verification = VerificationCode::where(['id' => $request->verification_id, 'code'=>$request->otp])->first();
        $response =[];
        $response['success'] = false;

        if($verification){
            $response['success'] = true;
            $deliveryPickedStatusId= DeliveryStatus::where('code', 'picked')->pluck('id')->first();
            $vendorStatus = VendorStatus::where('code', 'picked')->first();
            $vendorPickedStatusId= $vendorStatus->id;
            $orderStatus = OrderStatus::where('code', 'out_for_delivery')->first();
            $orderPickedStatusId= $orderStatus->id;
            $data = [
                'order_status_id' => $orderPickedStatusId,
                'vendor_status_id' => $vendorPickedStatusId,
                'delivery_status_id' => $deliveryPickedStatusId,
                'status_updated' => date('Y-m-d H:i:s')
            ];

            $response['success'] = $order->update($data);
            $response['updated'] = $response['success'] ;

            $vendorToken = VendorDeviceToken::where('vendor_id', $order->vendor_id)->pluck('token')->toArray();
            // dd($vendorToken);
            if($vendorToken){
                 $fcm =$this->pushNotification($vendorToken, $vendorStatus->name, '#'. $order->id.' '. $vendorStatus->description);
            }
            $userToken = UserDeviceToken::where('user_id', $order->user_id)->pluck('token')->toArray();
            $user = User::find($order->user_id);
            if($userToken)
                    $this->pushNotification($userToken, $orderStatus->name ,'#'. $order->id. ' '.$orderStatus->description );
            $deliveryPerson = DeliveryPerson::find($order->delivery_person_id);
            $message = "#".$order->id." has been picked by our Delivery Executive. \n\n".$deliveryPerson->name . "\n". $deliveryPerson->mobile. " \n\n Reach out to him for futher updates. \n\n Ocean";
            $sms = new SmsUtility;
            $sms->sendmessage([$user->mobile], $message, 1);
        }
        return $response;
    }*/
    public function verifyPickedOTP(Request $request, $deliveryPersonId ,$orderId){
       /* 
        $validator = \Validator::make($request->all(), ['verification_id' => 'required|numeric','otp' => 'required|numeric|min:5']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }*/

        $order = Order::where(['id'=>$orderId, 'delivery_person_id'=>$deliveryPersonId])->first();
        if(!$order){
            return  [
                'success' => false,
                'message' => "Order Does not belong to Deliverry person or Order doesn't Exist",
                'updated' => false
            ];
        }
        /*$verification = VerificationCode::where(['id' => $request->verification_id, 'code'=>$request->otp])->first();
        $response =[];
        $response['success'] = false;

        if($verification){*/
        $bfc=$order->amount;
        $totalamt=$order->amount;
        $product=$request->product_id;
        if($product!='')
        {
        $product=explode(',',$product);
        $tol=0;
        foreach($product as $pro)
        {  
            $OrderProduct = OrderProduct::where('order_id', $orderId)->where('product_id', $pro)->first();
            
            $price=$OrderProduct->price;
            $qty=$OrderProduct->qty;
            $total=$price*$qty;
          
            $dataa=[
                'delivery_cancel_status'=>1,
                'delivery_cancel_pro'=>1,
                ];
            $response['success'] = DB::table('order_products')->where('id',$OrderProduct->id)->update($dataa);
             $tol+=$total;
        }
        
        $cd=date('Y-m-d H:i:s');
        $totalamt=$totalamt-$tol;
        $totalamt=$totalamt.'.00';
       // exit;
        }
        else
        {
            $total=0;
            $cd=null;
            $totalamt=$totalamt;
        }
        // echo $totalamt;
        // exit;
        
            // echo $total;
            // exit;
            $response['success'] = true;
            $deliveryPickedStatusId= DeliveryStatus::where('code', 'picked')->pluck('id')->first();
            $vendorStatus = VendorStatus::where('code', 'picked')->first();
            $vendorPickedStatusId= $vendorStatus->id;
            $orderStatus = OrderStatus::where('code', 'out_for_delivery')->first();
            $orderPickedStatusId= $orderStatus->id;
            $data = [
                'before_cancel_amt'=>$bfc,
                'amount'=>$totalamt,
                'cancel_amt'=>$total,
                'cancel_date'=>$cd,
                'order_status_id' => $orderPickedStatusId,
                'vendor_status_id' => $vendorPickedStatusId,
                'delivery_status_id' => $deliveryPickedStatusId,
                'status_updated' => date('Y-m-d H:i:s')
            ];

            $response['success'] = $order->update($data);
            $response['updated'] = $response['success'] ;

            $vendorToken = VendorDeviceToken::where('vendor_id', $order->vendor_id)->pluck('token')->toArray();
            // dd($vendorToken);
            if($vendorToken){
                 $fcm =$this->pushNotification($vendorToken, $vendorStatus->name, '#'. $order->id.' '. $vendorStatus->description,2);
           // }
            $userToken = UserDeviceToken::where('user_id', $order->user_id)->pluck('token')->toArray();
            $user = User::find($order->user_id);
            if($userToken)
                    $this->pushNotification($userToken, $orderStatus->name ,'#'. $order->id. ' '.$orderStatus->description,1 );
            $deliveryPerson = DeliveryPerson::find($order->delivery_person_id);
            //$message = "#".$order->id." has been picked by our Delivery Executive. \n\n".$deliveryPerson->name . "\n". $deliveryPerson->mobile. " \n\n Reach out to him for futher updates. \n\n Justalo";
            $message = "#".$order->id." has been picked by our Delivery Executive. ".$deliveryPerson->name." reach out to him for further updates. [Justalo]";
            $sms = new SmsUtility;
            $sms->sendmessage([$user->mobile], $message, 1);
        }
        return $response;
    }
    
    public function time($deliveryPersonId,$id)
    {
        $query = DB::table('orders')->where('delivery_person_id',$deliveryPersonId)
        ->join('users', 'user_id','=', 'users.id')
        ->where('payment_status', 1)
        ->where('delivery_status_id', 2)
        ->where('orders.id',$id)
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*','users.name', 'users.email', 'users.mobile');

        $data=$query->get();
        if(count($data)>0)
        {
            $time=$data[0]->delivery_slab_time;
            $ttime=strtotime($time);
            $ctime=strtotime(date('Y-m-d H:i:s'));
            $min=$ttime-$ctime;
            //exit();
            $fmin=gmdate("i:s", $min);
            $minutes = $min/60;
            if($minutes<0){$fmin=0;} 
            $dataa['id']=$data[0]->id;
            $dataa['min']=$fmin;
        } 
        else
        {
            $dataa['id']=$id;
            $dataa['min']=.0; 
        }
           
        return $dataa;
    } 
    
    private function pushNotification($recipients, $title, $message,$notiid)
    {
        // return fcm()->to($recipients)->priority('high')->notification([
        //     'title' => $title,
        //     'body' => $message,
        // ])->send();
        return $this->newPush($recipients, $title, $message,$notiid);
    }

    private function newPush($recipients, $title, $message,$notiid)
    {
        $fcm = new FirebaseCloudMessagingUtility($title, $message);
        return $fcm->send($recipients,$notiid);
    }

    function mailCheck($order)
    {
        return $this->mail($order);
    }

    function mail($orderId)
    {
        $order = DB::table('orders')
        ->join('users', 'user_id','=', 'users.id')
        ->where('payment_status', 1)
        ->whereRaw('orders.id = '.$orderId)
        ->select('orders.*','users.name as user_name', 'users.email as user_email', 'users.mobile as user_mobile')->first();

        // $status = DeliveryStatus::where('id', $order->delivery_status_id);
        $vendor = Vendor::where('id', $order->vendor_id)->first();
        $address = OrderAddress::where('order_id',$orderId)->first();
        $products = DB::table('order_products')->where('order_id',$orderId)
        ->join('products', 'products.id', '=', 'order_products.product_id')->get();

        // return view('mail.invoice' , compact(
        //     'order',
        //     'address',
        //     'products',
        //     'vendor'
        // ));
        $data = compact(
                'order',
                'address',
                'products',
                'vendor'
        );

        $data["email"]=$order->user_email;
        $data["client_name"]=$order->user_name;
        $data["subject"]='Justalo - Order #'.$order->id .' Invoice';

        $pdf = PDF::loadView('mail.invoice', $data);


            Mail::send('mail.invoice', $data, function($message)use($data,$pdf) {
            $message->to($data["email"], $data["client_name"])
            ->subject($data["subject"])
            ->attachData($pdf->output(), "invoice-".$data['order']->id. ".pdf");
            });

        if (Mail::failures()) {
             $this->statusdesc  =   "Error sending mail";
             $this->statuscode  =   "0";

        }else{

           $this->statusdesc  =   "Message sent Succesfully";
           $this->statuscode  =   "1";
        }
        return response()->json(compact('this'));
    }
}
