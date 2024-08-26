<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\DeliveryPerson;
use App\DeliveryStatus;
use App\DeliveryCharges;
use App\VendorStatus;
use App\OrderStatus;
use App\OrderProduct;
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
use DB;
use Fcm;
use App\Utilities\SmsUtility;
use App\Utilities\FirebaseCloudMessagingUtility;
use App\OrderPayment;
use App\OrderMethod;
use App\PaymentMethod;

use PDF;
use Mail;
class DeliveryPersonOrderController extends Controller
{
    public function index( int $deliveryPersonId)
    {
        
        $query = DB::table('orders')
        ->join('users', 'user_id','=', 'users.id')
        ->join('order_payments', 'order_payments.order_id', '=', 'orders.id')
        ->join('order_payment_methods', 'order_payment_methods.order_payment_id', '=', 'order_payments.id')
        ->join('payment_methods', 'order_payment_methods.payment_method_id', '=', 'payment_methods.id')
        ->join('vendors', 'vendors.id', '=', 'orders.vendor_id')
        ->join('order_addresses', 'order_addresses.order_id', '=', 'orders.id')
        ->where('payment_status', 1)
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*', 'users.name', 'users.email', 'users.mobile', 'payment_methods.name as payment_method', 'payment_methods.is_postpaid', 'vendors.address as pickup_address', 'order_addresses.address as delivery_address','order_addresses.landmark as delivery_landmark');

        

        $type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : 'all';
        switch ($type) {
            case 'active':
                $query->where('delivery_person_id',$deliveryPersonId)->whereRaw("delivery_status_id IS NOT NULL AND delivery_status_id<>'5' AND delivery_status_id <> 3");
                break;

            case 'pending':
                $deliveryPerson = DeliveryPerson::with('commodity')->findOrFail($deliveryPersonId);
                $commodityCondition='';
                foreach ($deliveryPerson->commodity as $key => $value) {
                    if($key==0){
                        $commodityCondition = '( commodity_type_id = '. $value->commodity_type_id;
                    }

                    $commodityCondition.= ' OR commodity_type_id = '. $value->commodity_type_id;
                    if( $key+1 == count($deliveryPerson->commodity) ){
                        $commodityCondition .= ')';
                    }
                }
                if(!empty($commodityCondition))
                {
                    $query->whereRaw($commodityCondition);
                }
                
                /*$query->whereNull("delivery_status_id")->where("delivery_person_id", $deliveryPersonId)->whereRaw("( service_area_id = $deliveryPerson->service_area_id OR service_area_id is NULL)");*/
                $query->where(['delivery_status_id' => null ])->whereRaw("(delivery_person_id = $deliveryPersonId or delivery_person_id is null)")->whereRaw("( service_area_id = $deliveryPerson->service_area_id and service_area_id is not NULL)");
                break;

            case 'delivered':
                $deliveredStatusId = DeliveryStatus::where('code', 'delivered')->pluck('id')->first();
                $query->where(['delivery_person_id' => $deliveryPersonId, 'delivery_status_id' => $deliveredStatusId ]);
            default:
                $query->where('delivery_person_id',$deliveryPersonId);

                break;
        }
        return $query->get();
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

    public function update(Request $request, $deliveryPersonId , $id)
    {
        $order = Order::findOrFail($id);
        if($order->delivery_person_id != $deliveryPersonId && $order->delivery_person_id != null){
            return [
                'success' => false,
                'message' => 'Order does not belong to delivery Person'
            ];
        }
        $this->validate($request, [
            'delivery_status_id' => 'required|numeric'
        ]);

        $deliveryStatus = DeliveryStatus::findOrFail($request->delivery_status_id);
        $deliveryStatusId = $deliveryStatus->id;
        $orderStatusId=null;
        //getiing diffrent status based on deliveryPerson status
        $userToken = UserDeviceToken::where('user_id', $order->user_id)->get();
        $user = User::find($order->user_id);
        if ($deliveryStatus->code == 'delivered') {
            $orderStatus = OrderStatus::where('code', 'delivered')->first();
            $orderStatusId= $orderStatus->id;
            if(!empty($userToken))
                $this->pushNotification($userToken, $orderStatus->name ,'#'. $order->id. ' '.$orderStatus->description );

            $orderAmount =  DB::table('order_products')->where('order_id', $order->id)->selectRaw('SUM( price * qty) as vendor_total, SUM( (price - (price * (discount * 0.01)) ) * qty) as item_total')->first();
            $delveryAmount = $order->amount - $orderAmount->item_total;
            // dd($vendorAmount);
            VendorWallet::create(['description' => 'Order '.$order->id.' payment credited','amount' => $orderAmount->vendor_total, 'vendor_id' => $order->vendor_id]);
            DeliveryPersonWallet::create(['description' => 'Order '.$order->id.' payment credited','delivery_charges_for_cust'=>$order->delivery_charges_for_cust, 'amount' => $order->delivery_charges, 'delivery_person_id'=>$deliveryPersonId]);

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
               $fcm =$this->pushNotification($vendorToken, $orderStatus->name, '#'. $order->id.' '. $orderStatus->description);
            }

            $orderPaymentMethod = OrderPayment::where('order_id',$id)->leftJoin('order_payment_methods', 'order_payment_methods.order_payment_id', '=', 'order_payments.id')->leftJoin('payment_methods', 'order_payment_methods.payment_method_id', '=', 'payment_methods.id')->select('payment_methods.*')->first();

            if(isset($orderPaymentMethod->is_postpaid) && $orderPaymentMethod->is_postpaid){

                DeliveryPersonWallet::create(['description' => 'Order '.$order->id.' cash collected', 'is_collectable'=> 1, 'amount' => $order->amount,'delivery_charges_for_cust'=>$order->delivery_charges_for_cust,'delivery_person_id'=>$deliveryPersonId]);
            }
            $userToken = UserDeviceToken::where('user_id', $order->user_id)->pluck('token')->toArray();
            if($userToken)
                   $this->pushNotification($userToken, $orderStatus->name ,'#'. $order->id. ' '.$orderStatus->description );
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
        $updated = $order->update($queryData);
        return [
            'success' => $updated ? true : false,
            'message' => $updated ? 'Updated' : 'Something went wrong',
        ];

    }



    public function createPickedOTP($deliveryPersonId, $orderId){
        $vendor = DB::table('orders')->where(['orders.id' => $orderId, 'delivery_person_id'=>$deliveryPersonId])->join('vendors', 'vendors.id', '=', 'orders.vendor_id')->select('vendors.*')->first();
        // dd($vendor);
        if(!$vendor || !$vendor->contact_no){
            return [
                'success' => false,
                'message' => 'Invalid Order or delivery boy or vendor'
            ];
        }

        $otp = rand(10000,99999);
        $message = 'Your one time password to share with with delivery executive is '.$otp;
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
           // }
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
    }

    private function pushNotification($recipients, $title, $message){
        // return fcm()->to($recipients)->priority('high')->notification([
        //     'title' => $title,
        //     'body' => $message,
        // ])->send();
        return $this->newPush($recipients, $title, $message);
    }


    private function newPush($recipients, $title, $message){
        $fcm = new FirebaseCloudMessagingUtility($title, $message);
        return $fcm->send($recipients,1);
    }

    function mailCheck($order){
        return $this->mail($order);
    }


    function mail($orderId){

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
        $data["subject"]='Ocean - Order #'.$order->id .' Invoice';

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
