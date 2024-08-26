<?php
/*
* import checksum generation utility
* You can get this utility from https://developer.paytm.com/docs/checksum/
*/
require_once("PaytmChecksum.php");

$paytmParams = array();
$id=$_POST['user_id'];
$total=$_POST['total_amount'];
$rn='_'.date('h_i_s').'_'.rand(1,999);
$paytmParams["body"] = array(
  "requestType"  => "Payment",
  "mid" => "QzxAln53857791992022",
  "websiteName"  => "DEFAULT",
  "orderId"    => "copsmart".$rn,
  "callbackUrl"  => "https://securegw.paytm.in/theia/paytmCallback?ORDER_ID=copsmart".$rn,
  "txnAmount"   => array(
    "value"   => $total,
    "currency" => "INR",
  ),
  "userInfo"   => array(
    "custId"  => "CUST_".$id,
  ),
);

/*
* Generate checksum by parameters we have in body
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys
*/
$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), "03&%96kb9F4s%wF5");

$paytmParams["head"] = array(
  "signature" => $checksum
);

$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/* for Staging */
// $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";

/* for Production */
$url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=QzxAln53857791992022&orderId=copsmart".$rn;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
$response = curl_exec($ch);

$response= json_decode($response);
$datanew['response']=$response;
$datanew['order_id']="copsmart".$rn;
echo json_encode($datanew);