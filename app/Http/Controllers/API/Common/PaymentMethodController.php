<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PaymentMethod;
use App\RazorpaySetting;
use App\PaytmSetting;
use Razorpay\Api\Api;
class PaymentMethodController extends Controller
{
    
    public function getAll(){
        return PaymentMethod::get();
    }
    
    public function getrazor(){
        return RazorpaySetting::where('is_active',1)->first();
    }
    
     public function getpaytm(){
        return PaytmSetting::where('is_active',1)->first();
    }
    
    
//   public function GetDataForRazorpay($order)
//   {
//         $rz= RazorpaySetting::where('is_active',1)->first();
//   // $ab=$this->GetapiDetails();
//     $api = new Api($rz['rkey'],  $rz['rsecret']);
//     $razorpayOrder = $api->order->create($order);
//     $dt['orderid'] = $razorpayOrder['id'];
//     $dt['amount'] =$displayAmount = $amount = $order['amount'];
//     $displayCurrency=$dt['currency']=$order['currency'];
//     if ($displayCurrency !== 'INR')
//     {
//         $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
//         $exchange = json_decode(file_get_contents($url), true);
//         $dt['amount']=$displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
//     }
//     return $dt;
//   }
    
//   public function getOrderId()
//   {
    
//      //print_r($rz);
     
//       $orderData = [
//             'receipt'         => rand(1111,9999),
//             'amount'          => 1 * 100, // 2000 rupees in paise
//             'currency'        => 'INR',
//             'payment_capture' => 1 // auto capture
//           ];
//           return $ab1=$this->GetDataForRazorpay($orderData);
          
//   }
   
}
