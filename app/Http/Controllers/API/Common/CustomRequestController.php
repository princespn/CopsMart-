<?php
namespace App\Http\Controllers\API\Common;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\User;
use App\Coupon;
use App\Vendor;
use App\UserAddress;
use App\OrderAddress;
use App\ServiceArea;
use App\OrderStatus;
use App\VendorStatus;
use App\DeliveryStatus;
use App\OrderProduct;
use App\ProductImage;
use App\DeliveryCharges;
use App\OrderPayment;
use App\PaymentMethod;
use App\OrderPaymentMethod;
use App\OrderPaymentMethodResponse;
use App\CategoryDeliverySlab;
use App\Category;
use App\Brand;
use App\Product;
use App\SubCategory;
use App\DeliveryPersonDeviceToken;
use App\VendorDeviceToken;
use App\DeliveryPersonWallet;
use App\DeliveryPerson;
use App\UserDeviceToken;
use App\Stock;
use App\Size;
use App\Color;
use App\Notification;
use App\Wishlist;
use App\AddCart;
use App\CustomRequest;
use App\Utilities\SmsUtility;
use App\Utilities\FirebaseCloudMessagingUtility;
use DB;
use App\OrderUserRating;
use DateTime;

class CustomRequestController extends Controller
{  

    public function addCustomrequest(Request $request)
    {    
         $this->validate($request, [
               'user_id'=> 'required','product_name'=> 'required','size_name'=> 'required','color_name'=> 'required','expected_date'=>'required','qty'=>'required','brand_name'=>'required'
            ]);  
            $request->merge(['status' => 'pending']);
            if(isset($request->product_id))
            {
                  $request->merge(['type' => 'Out Of Stock']);
            }
            else
            {
                  $request->merge(['type' => 'Custom']);
            }
             if(isset($request->image)){
                $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                \Image::make($request->image)->save(public_path('uploads/images/request/').$name);
                $request->merge(['image' => $name]);
                }
            $data = $request->only('user_id','product_id','product_name','size_name','size','color','color_name','expected_date','qty','status','image','brand_name','brand_id','type');
            $categoryId = CustomRequest::create($data)->id;
            if($categoryId)
            {
                return [
                'msg' => 'Custom Request Added Successfully',
            ];
            }
            else
            {
                return [
                'msg' => 'error' 
              ]; 
            }
           
    }
    
    public function getCustomrequest($id)
    {
        $type=$_GET['status'];
        if($type=='all')
        {
            $cust=CustomRequest::where('user_id',$id)->get();
        }
        else
        {
            $cust=CustomRequest::where('user_id',$id)->where('status',$type)->get();
        }
        if(count($cust)>0)
        {
             return $cust;
        }
        else
        {
             return ['msg'=>'No Data Found'];
        }
       
    }
    
    public function DeleteRequest($id)
    {
        // $notification =Notification::where('user_id',$id)->orderBy('id','desc')->get();
        // foreach($notification as $key)
        // {   
            $category = CustomRequest::findOrFail($id);
            $category->delete();
        // }
        return ['msg'=>'Request Deleted'];
    }

    
    
   
   
}
