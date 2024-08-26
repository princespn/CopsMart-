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
use App\Utilities\SmsUtility;
use App\Utilities\FirebaseCloudMessagingUtility;
use DB;
use App\OrderUserRating;
use DateTime;

class WishlistController extends Controller
{  

    public function addwishlist(Request $request)
    {
         $this->validate($request, [
               'vendor_id'=> 'required', 'product_id'=>'required','user_id'=> 'required'
            ]);  
            $data = $request->only('vendor_id','product_id','user_id');
            $categoryId = Wishlist::create($data)->id;
            if($categoryId)
            {
                return [
                'msg' => 'Wishlist Added Successfully',
                'id'=>$categoryId
            ];
            }
            else
            {
                return [
                'msg' => 'error' 
              ]; 
            }
           
    }
    
    public function RemoveWishlist($id)
    {
        $vv= Wishlist::where('id',$id)->get();
        if(count($vv)>0){
            $vendorProduct = Wishlist::findOrFail($id);
            if($vendorProduct->delete())
            {
            return [
                'msg' => 'Wishlist Updated Successfully'
            ];
            }
        }
        else
        {
            return [
            'msg' => 'error' 
          ]; 
        }
   
    }
   
}
