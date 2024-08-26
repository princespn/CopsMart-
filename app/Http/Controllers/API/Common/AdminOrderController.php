<?php
//date_default_timezone_set('Asia/Kolkata');
namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AdminOrder;
use App\User;
use App\Coupon;
use App\AdminServiceArea;
use App\UserAddress;
use App\OrderPayment;
use App\PaymentMethod;
use App\OrderPaymentMethod;
use App\AdminOrderAddress;
use App\ServiceArea;
use App\OrderStatus;
use App\AdminOrderProduct;
use App\DeliveryCharges;
use App\AdminCategory;
use App\Utilities\SmsUtility;
use App\Utilities\FirebaseCloudMessagingUtility;
use DB;
use App\Utilities\DistanceUtility;
use DateTime;
class AdminOrderController extends Controller
{
    public function index($customerId)
    {

        $query = DB::table('admin_orders')->where('admin_orders.user_id',$customerId)
        ->join('users', 'admin_orders.user_id','=', 'users.id')
        ->join('admin_service_areas', 'admin_service_area_id','=', 'admin_service_areas.id')
        ->where('admin_orders.payment_status', 1)
        ->orderBy('admin_orders.id', 'DESC')
        ->select('admin_orders.*','users.name', 'users.email', 'users.mobile','admin_service_areas.name');


        $type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : 'all';
        switch ($type) {
            case 'active':
                $query->whereRaw("order_status_id IS NOT NULL AND order_status_id<>'2' ");
                break;

            case 'pending':
                $query->where("order_status_id", null);
                break;

            default:

                break;
        }
        return $query->get();
    }

    public function checkLastOrderFeedback(Request $request, $userId){
        return Order::where(['order_status_id' => 9,'user_id' => $userId])->latest()->with('rating')->first();
    }
    public function verifyAndCalculateOrder(Request $request){
        $validator = \Validator::make($request->all(), ['user_id'=>'required|numeric','user_address_id'=>'required|numeric','items'=>'required','admin_service_area_id'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        $items= json_decode($request->items);
        $calculatedItems = $this->calculateOrder($request, $items);
        $response['charges'] = $calculatedItems;
        $response['promotion_status'] =[
            'status' => $calculatedItems->discount > 0 ? true : false,
            'msg' => $calculatedItems->discount > 0 ? 'Applied successfully' : 'Promo not applicable'
        ];
        return $response;
    }

    public function ordersByUser($user){
        
        $orders =  AdminOrder::where('user_id', $user)->with(['address', 'status'] )->orderBy('id', 'DESC')->get();
        foreach ($orders as $key => $order) {
            $products = DB::table('admin_order_products')->where('order_id',$order->id)
        ->join('admin_products', 'admin_products.id', '=', 'admin_order_products.product_id')->join('admin_service_area_products','admin_service_area_products.id','=','admin_order_products.admin_service_area_product_id')->join('packages','packages.id','=','admin_service_area_products.package_id')->select('admin_order_products.*','admin_products.*','admin_service_area_products.package_id','packages.name as package_name')->get();
            $order->products = $products;
            $orders[$key] = $order;
            $orders[$key]['order_date'] = date('d/m/Y h:i A', strtotime($order->created_at));
;
        }
        return $orders;
    }

    public function placeOrder(Request $request){
        
         $validator = \Validator::make($request->all(), [
             'user_id' => 'required|numeric',
            'user_address_id' => 'required|numeric',
            'admin_service_area_id' => 'required|numeric',
            'payment_method_id' => 'required|numeric',
            'items' => 'required',]);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        $items= json_decode($request->items);
        $calculatedItems = $this->calculateOrder($request, $items);
        
        $order = $this->createOrder($request, $calculatedItems);
        return $order;
    }

    private function createOrder($data, $items){
        return DB::transaction(function () use ($data, $items) {

            $pendingOrderStatus = OrderStatus::where(['code'=>'created'])->pluck('id')->first();
            // dd($data->user_id);
            $orderData = [
                'user_id' => (int) $data->user_id,
                'admin_service_area_id' => $data->admin_service_area_id,
                'amount' => $data->final_total,
                'delivery_charges_for_cust' => $items->delivery_charges_for_cust,
                'date' => date('Y-m-d'),
                'payment_status' => '1',
                'order_status_id' => $pendingOrderStatus,
                'status_updated' => date('Y-m-d H:i:s'),
                'scheduled_delivery_date' => date('Y-m-d H:i:s', strtotime($items->delivery_in_days.' hour')),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            $order = AdminOrder::create($orderData)->id; // creating Order

            $orderProducts =[];
            foreach ($items->items as $key => $item) {
                $vp = DB::table('admin_service_area_products')
                ->join('admin_products', 'admin_products.id', '=', 'admin_service_area_products.product_id')
                ->where('admin_service_area_products.id', $item->admin_service_area_product_id)
                ->where('admin_service_area_products.admin_service_area_id', $data->admin_service_area_id)
                ->select(['*', 'admin_service_area_products.id as vendor_product_id','admin_products.id as product_id' ])->first();
                if($vp){
                    $orderProduct =[
                        'order_id' => $order,
                        'product_id' => $vp->product_id,
                        'admin_service_area_product_id' => $vp->id,
                        'name' => $vp->name,
                        'sell_price' => $item->sell_price,
                        'mrp' => $vp->mrp,
                        'qty' => $item->qty,
                        'discount' => isset($item->discount) ? $item->discount : 0,
                        'final_price' => $item->sell_price,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $orderProducts[] = $orderProduct;
                }
            }
            // dd($orderProducts);
            AdminOrderProduct::insert($orderProducts); // inserting order items in db
            $address =  UserAddress::findOrFail($data->user_address_id);
            $vend =  AdminServiceArea::findOrFail($data->admin_service_area_id);
            
            
            $latitude = $address->lat;
            $longitude= $address->long;
            $address->service_area_id = $data->service_area_id;
            $address->update();
            
            AdminOrderAddress::create([
                'order_id' => $order,
                'service_area_id' => $address->service_area_id,
                'mobile' => $address->mobile,
                'name' => $address->name,
                'address' => $address->address,
                'landmark' => $address->landmark,
                'latitude' => $address->lat,
                'longitude' => $address->long,
            ]);

            $orderPaymentId = OrderPayment::create([
                'order_id'=>$order,
                'amount' => $items->final_total,
                'type' => 'Admin',
            ])->id;

            $paymentMethod = PaymentMethod::findOrFail($data->payment_method_id);

            $opmData =[
                'order_payment_id' =>$orderPaymentId,
                'payment_method_id' =>$paymentMethod->id,
                'amount' =>$items->final_total,
                'type' => 'Admin',
            ];
            $orderPaymentMethodId =  OrderPaymentMethod::create($opmData)->id;
            $opmData['id'] = $orderPaymentMethodId;
            $paymentParams = NULL;
            if($paymentMethod->is_postpaid == true){
                // changing order status to placed if COD payment
                $placedOrderStatus = OrderStatus::where(['code'=>'placed'])->pluck('id')->first();
                DB::table('admin_orders')->where('id',$order)->update(['order_status_id'=>$placedOrderStatus, 'payment_status'=>1]);
                $orderPayment = OrderPayment::findorFail($orderPaymentId);
                $orderPayment->status = 1;
                $orderPayment->save();
                $orderPaymentMethod = OrderPaymentMethod::findorFail($orderPaymentMethodId);
                $orderPaymentMethod->status = 1;
                $orderPaymentMethod->save();
            }else{
                $paymentParams = $this->paytmPaymentParams($orderPaymentMethodId, $data->user_id);
            }

            return [
                'order_id' => $order,
                'payment' => [
                    'post_processing' => $paymentMethod->is_postpaid == true ? false : true,
                    'payment_method' => $paymentMethod,
                    'params' => $paymentParams
                ],
                'message' => 'Order created Successfully'
            ];
        }, 5); // transaction ends here
    }

    private function calculateOrder($data, $items){
        $orderInitialTotal = $this->getOrderTotal($items);
        $calculatedItems = $this->applyDiscount($items);
        $delivery_charges = $this->deliverychargeforcust($items);
        $calculatedItems->delivery_charges_for_cust = $delivery_charges->total_delivery_charge;
        $calculatedItems->delivery_in_days = $delivery_charges->delivery_in_days;
        $calculatedItems->discount = round($calculatedItems->discount,2);
        $calculatedItems->discounted_total = round($calculatedItems->discounted_total,2);
        $calculatedItems->final_total = $calculatedItems->discounted_total + $calculatedItems->delivery_charges_for_cust;
        return $calculatedItems;
    }


    private function applyDiscount($items){
        $result = json_decode('{}') ;
        $result->items = $items;
        $discount = 0;
        $discounted_total = 0;
        foreach ($items as  $item) {
                if($item->mrp>$item->sell_price){
                    $discount = $discount+($item->mrp - $item->sell_price)*$item->qty;
                }
                else{
                    $discount = $discount+0;
                }
                $discounted_total = $discounted_total+($item->sell_price*$item->qty);
        }
        
        $result->discounted_total = $discounted_total;
        $result->discount = $discount;
        return $result;
    }
    
    private function deliverychargeforcust($items)
    {
        $delivery_result = json_decode('{}') ;
        $delivery_result->items = [];
        $delivery_charge_array = [];
        $delivery_in_days_array = [];
        foreach ($items as  $item) {
                array_push($delivery_charge_array,$item->delivery_charges);
                array_push($delivery_in_days_array,$item->delivery_in_days);
        }
        $delivery_result->total_delivery_charge = max($delivery_charge_array);
        $delivery_result->delivery_in_days = max($delivery_in_days_array);
        return $delivery_result;
    }

    

    private function getOrderTotal($items){
        $total =0;
        foreach ($items as $key => $item) {
            $total+=$item->sell_price * $item->qty ;
        }
        return $total;
    }

    public function paytmPaymentStatus(Request $request){
        // updates payment status of order based on response received
        $payment = OrderPaymentMethod::findOrFail($request->ORDERID);
            $success=null;
            switch ($request->STATUS) {
                case 'TXN_SUCCESS':
                    $success=1;
                    break;
                case 'TXN_FAILURE':
                    $success=0;
                    break;
                case 'TXN_ABORTED':
                    $success=0;
                    break;
                default:
                    $success=null;
                    break;
            }
            $q_data['status']= $success;
            $q_data['response_object'] = json_encode($request->all());
            $q_data['payment_method_id'] =$payment->payment_method_id;
            $q_data['order_id']= $q_data['order_payment_method_id']=  $request->ORDERID;
            if(isset($request->STATUS) && $request->STATUS!=''){
                $q_data['status_code']= $request->STATUS;
            }
            if(isset($request->BANKNAME) && $request->BANKNAME!=''){
                $q_data['bank_name']= $request->BANKNAME;
            }
            if(isset($request->TXNDATE) && $request->TXNDATE!=''){
                $q_data['date']= $request->TXNDATE;
            }
            if(isset($request->TXNID) && $request->TXNID!=''){
                $q_data['transaction_uid']= $request->TXNID;
            }
            // if(isset($request->RESPCODE) && $request->RESPCODE!=''){
            //     $q_data['response_code']= $request->RESPCODE;
            // }
            if(isset($request->TXNAMOUNT) && $request->TXNAMOUNT!=''){
                $q_data['amount']= $request->TXNAMOUNT;
            }
            if(isset($request->PAYMENTMODE) && $request->PAYMENTMODE!=''){
                $q_data['payment_mode']= $request->PAYMENTMODE;
            }
            if(isset($request->BANKTXNID) && $request->BANKTXNID!=''){
                $q_data['bank_txn_id']= $request->BANKTXNID;
            }
            if(isset($request->CURRENCY) && $request->CURRENCY!=''){
                $q_data['currency']= $request->CURRENCY;
            }
            if(isset($request->GATEWAYNAME) && $request->GATEWAYNAME!=''){
                $q_data['gateway_name']= $request->GATEWAYNAME;
            }
            if(isset($request->RESPMSG) && $request->RESPMSG!=''){
                $q_data['response_msg']= $request->RESPMSG;
            }
            $paymentResponse = OrderPaymentMethodResponse::where('order_payment_method_id',$request->ORDERID)->first();
            if($paymentResponse){
                $paymentResponse->update($q_data);
                $opmr_status =$paymentResponse->save();
                $opm_id = $paymentResponse->order_payment_method_id;
            }else{
                $opmr_status = OrderPaymentMethodResponse::create($q_data);
                $opm_id = $request->ORDERID;
            }
            if($success==1 || $success==0){
                $payment->status = $success;
                $payment->save();

                $orderId = $this->getOrderIdFromOrderPaymentMethodId($opm_id);
                $placedOrderStatus = OrderStatus::where(['code'=>'placed'])->pluck('id')->first();
                if($orderId){
                    DB::table('orders')->where('id',$orderId->order_id)->update(['order_status_id'=>$placedOrderStatus, 'payment_status'=>$success]);
                    DB::table('order_payments')->where('id',$orderId->order_payment_id)->update(['status'=>$success]);
                    // $this->sendOrderMsgToAdmin($orderId);
                }
            }
            return ['success' => $opmr_status];

    }

    public function getOrderIdFromOrderPaymentMethodId($opm_id){
        return DB::table('order_payment_methods')
        ->join('order_payments', 'order_payments.id', '=', 'order_payment_methods.order_payment_id')
        ->where('order_payment_methods.id', $opm_id)
        ->select(['order_payments.id as order_payment_id','order_id'])->first();
    }


    private function paytmPaymentParams($paymentId,$userId) {
        // Creates paytm parameters for Payment Initiation
        $user = User::find($userId);
        $payment = OrderPaymentMethod::find($paymentId);

        if($payment == null || $user == null){
            return false;
        }
        $order = $payment->id;
        $amount = $payment->amount;
        $customerId = $user->id;
        $callback_url =config('app.PAYTM.CALLBACK_URL');
        //$transId = $this->PaymentModel->getCreatePayment($orderId, $customerId, $order->row()->amount);

        $this->getAllEncdecFunc(); // loading all paytm functions
        $this->getConfigPaytmSettings(); // loading paytm config settings

        $checkSum = "";
        $paramList = array();

        $INDUSTRY_TYPE_ID = "Retail109";
        $CHANNEL_ID = "WAP"; //WEB OR WAP
        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = PAYTM_MERCHANT_MID;
        $paramList["ORDER_ID"] = $order;
        $paramList["CUST_ID"] = $customerId;
        $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = $CHANNEL_ID;
        $paramList["TXN_AMOUNT"] = $payment->amount ? $amount : 0;
        $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;

        $paramList["CALLBACK_URL"] = $callback_url.$order;
        $paramList["MSISDN"] = $user->mobile != '' ? $user->mobile : '9999999999'; //Mobile number of customer
        $paramList["EMAIL"] = $user->email != '' ? $user->email : ''; //Email ID of customer
        //["VERIFIED_BY"] = "MOBILE"; //
        //$paramList["IS_USER_VERIFIED"] = "YES"; //
        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);
        $paramList['CHECKSUM'] = $checkSum;

        return $paramList;
    }


    // PAYTM GATEWAY RELATED FUNCTIONS
    /**
     * Get all the functions from encdec_paytm.php
     */
    function getAllEncdecFunc() {
        function encrypt_e($input, $ky) {
            $key   = html_entity_decode($ky);
            $iv = "@@@@&&&&####$$$$";
            $data = openssl_encrypt ( $input , "AES-128-CBC" , $key, 0, $iv );
            return $data;
        }
        function decrypt_e($crypt, $ky) {
            $key   = html_entity_decode($ky);
            $iv = "@@@@&&&&####$$$$";
            $data = openssl_decrypt ( $crypt , "AES-128-CBC" , $key, 0, $iv );
            return $data;
        }
        function pkcs5_pad_e($text, $blocksize) {
            $pad = $blocksize - (strlen($text) % $blocksize);
            return $text . str_repeat(chr($pad), $pad);
        }
        function pkcs5_unpad_e($text) {
            $pad = ord($text{strlen($text) - 1});
            if ($pad > strlen($text))
                return false;
            return substr($text, 0, -1 * $pad);
        }
        function generateSalt_e($length) {
            $random = "";
            srand((double) microtime() * 1000000);
            $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
            $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
            $data .= "0FGH45OP89";
            for ($i = 0; $i < $length; $i++) {
                $random .= substr($data, (rand() % (strlen($data))), 1);
            }
            return $random;
        }
        function checkString_e($value) {
            if ($value == 'null')
                $value = '';
            return $value;
        }
        function getChecksumFromArray($arrayList, $key, $sort=1) {
            if ($sort != 0) {
                ksort($arrayList);
            }
            $str = getArray2Str($arrayList);
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }
        function getChecksumFromString($str, $key) {
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }
        function verifychecksum_e($arrayList, $key, $checksumvalue) {
            $arrayList = removeCheckSumParam($arrayList);
            ksort($arrayList);
            $str = getArray2StrForVerify($arrayList);
            $paytm_hash = decrypt_e($checksumvalue, $key);
            $salt = substr($paytm_hash, -4);
            $finalString = $str . "|" . $salt;
            $website_hash = hash("sha256", $finalString);
            $website_hash .= $salt;
            $validFlag = "FALSE";
            if ($website_hash == $paytm_hash) {
                $validFlag = "TRUE";
            } else {
                $validFlag = "FALSE";
            }
            return $validFlag;
        }
        function verifychecksum_eFromStr($str, $key, $checksumvalue) {
            $paytm_hash = decrypt_e($checksumvalue, $key);
            $salt = substr($paytm_hash, -4);
            $finalString = $str . "|" . $salt;
            $website_hash = hash("sha256", $finalString);
            $website_hash .= $salt;
            $validFlag = "FALSE";
            if ($website_hash == $paytm_hash) {
                $validFlag = "TRUE";
            } else {
                $validFlag = "FALSE";
            }
            return $validFlag;
        }
        function getArray2Str($arrayList) {
            $findme   = 'REFUND';
            $findmepipe = '|';
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                $pos = strpos($value, $findme);
                $pospipe = strpos($value, $findmepipe);
                if ($pos !== false || $pospipe !== false)
                {
                    continue;
                }
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }
        function getArray2StrForVerify($arrayList) {
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }
        function redirect2PG($paramList, $key) {
            $hashString = getchecksumFromArray($paramList, $key);
            $checksum = encrypt_e($hashString, $key);
        }
        function removeCheckSumParam($arrayList) {
            if (isset($arrayList["CHECKSUMHASH"])) {
                unset($arrayList["CHECKSUMHASH"]);
            }
            return $arrayList;
        }
        function getTxnStatus($requestParamList) {
            return callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
        }
        function getTxnStatusNew($requestParamList) {
            return callNewAPI(PAYTM_STATUS_QUERY_NEW_URL, $requestParamList);
        }
        function initiateTxnRefund($requestParamList) {
            $CHECKSUM = getRefundChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY,0);
            $requestParamList["CHECKSUM"] = $CHECKSUM;
            return callAPI(PAYTM_REFUND_URL, $requestParamList);
        }
        function callAPI($apiURL, $requestParamList) {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData =json_encode($requestParamList);
            $postData = 'JsonData='.urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($postData))
            );
            $jsonResponse = curl_exec($ch);
            $responseParamList = json_decode($jsonResponse,true);
            return $responseParamList;
        }
        function callNewAPI($apiURL, $requestParamList) {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData =json_encode($requestParamList);
            $postData = 'JsonData='.urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($postData))
            );
            $jsonResponse = curl_exec($ch);
            $responseParamList = json_decode($jsonResponse,true);
            return $responseParamList;
        }
        function getRefundChecksumFromArray($arrayList, $key, $sort=1) {
            if ($sort != 0) {
                ksort($arrayList);
            }
            $str = getRefundArray2Str($arrayList);
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }
        function getRefundArray2Str($arrayList) {
            $findmepipe = '|';
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                $pospipe = strpos($value, $findmepipe);
                if ($pospipe !== false)
                {
                    continue;
                }
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }
        function callRefundAPI($refundApiURL, $requestParamList) {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData =json_encode($requestParamList);
            $postData = 'JsonData='.urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $refundApiURL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $jsonResponse = curl_exec($ch);
            $responseParamList = json_decode($jsonResponse,true);
            return $responseParamList;
        }
    }
    /**
     * Config Paytm Settings from config_paytm.php file of paytm kit
     */
    function getConfigPaytmSettings() {
        define('PAYTM_ENVIRONMENT', config('app.PAYTM.ENV')); // PROD
        define('PAYTM_MERCHANT_KEY', config('app.PAYTM.KEY')); //Change this constant's value with Merchant key downloaded from portal
        define('PAYTM_MERCHANT_MID', config('app.PAYTM.MID')); //Change this constant's value with MID (Merchant ID) received from Paytm
        define('PAYTM_MERCHANT_WEBSITE', config('app.PAYTM.WEBSITE')); //Change this constant's value with Website name received from Paytm
        $PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
        $PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';
        if (PAYTM_ENVIRONMENT == 'PROD') {
            $PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
            $PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
        }
        define('PAYTM_REFUND_URL', '');
        define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
        define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
        define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
    }
    function sendPushNotification()
    {
        $deliveryToken = array('eXo-jcuBQyuqDIrcJrV6fl:APA91bFxqXACpjHtjLBY2qfXtPBi-73pjvO5i5V4D_J1lkIQjsbkakhFyp3i3HUpM0oODeYZW3SnEr4NFJ27JHdgv7d_igpP39o9FTjYDdUOLbBCGWAn11T9jga6eFGNuZ2KkGmGBVid');
        $fcm = new FirebaseCloudMessagingUtility('New Delivery Available at your location!', '[Category]: test [CUSTOMER LANDMARK]: test address');
           // $fcm->send($deliveryToken);
            $fcm->sendData($deliveryToken, ['order_id' => 1] ,1);
    }
    
    public function adminCancelOrder(Request $request){
        $validator = \Validator::make($request->all(), [
             'user_id' => 'required|numeric',
            'order_id' => 'required|numeric',]);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        
        $order =  AdminOrder::find($request->order_id);
        if($order){
            $start_date = new DateTime($order->created_at);
            $end_date = date("Y-m-d H:i:s");
            $since_start = $start_date->diff(new DateTime($end_date));
            
            if($since_start->i<=5){
                
                $order->order_status_id = 3;
                $order->update();
                
                $response['success'] = '1';
                $response['message'] = "Order has been canceled successfully.";
                
        
            }
            else{
                $response['success'] = '0';
                $response['message'] = "Order cancelation timesup.";
            }
            

        }
        else{
            $response['success'] = '0';
            $response['message'] = "No such is available.";
        }
        
        return $response;
    }

}
