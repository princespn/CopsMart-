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
use App\AddCart;
use App\Utilities\SmsUtility;
use App\Utilities\FirebaseCloudMessagingUtility;
use DB;
use App\OrderUserRating;
use DateTime;
class NotificationController extends Controller
{  


    
    public function SendNotification(Request $request)
    {     
        
       
        if($request->type=='Home')
        {
            $this->validate($request, [
                'type' => 'required|string|max:200',
                'description' => 'required',
                'title' => 'required',
            ]);
           
        }
        elseif($request->type=='Product')
        {
            $this->validate($request, [
                'type' => 'required|string|max:200',
                // 'images' => 'required',
                'description' => 'required',
                'title' => 'required',
                'product_id' => 'required',
            ]);
           
        }
        elseif($request->type=='Category')
        {

            $this->validate($request, [
                'type' => 'required|string|max:200',
                // 'images' => 'required',
                'description' => 'required',
                'title' => 'required',
                'category_id' => 'required',
                // 'sub_category_id' => 'required',
                // 'sub_sub_category_id' => 'required',
            ]);
            
        }
        elseif($request->type=='Brand')
        {
            $this->validate($request, [
                'type' => 'required|string|max:200',
                // 'images' => 'required',
                'description' => 'required',
                'title' => 'required',
                'brand_id' => 'required',
            ]);
            
        }
        else
        {
            $this->validate($request, [
                'type' => 'required|string|max:200',
                // 'images' => 'required',
                'description' => 'required',
                'title' => 'required',
            ]);
        }
       
        if($request->images){
            $name = time().'.' . explode('/', explode(':', substr($request->images, 0, strpos($request->images, ';')))[1])[1];
            \Image::make($request->images)->save(public_path('uploads/images/notification/').$name);
            $request->merge(['image' => $name]);
        }
        $request->merge(['is_send'=>1]);
        
        $user=User::where('vendor_id',$request->vendor_id)->get();
        $brand_name='';
        $product_name='';
        $sub_sub_category_name='';
        $sub_category_name='';
        $category_name='';
           
            $bnm=Category::where('id',$request->category_id)->first();
            if(isset($bnm))
            {
            $category_name=$bnm->name;
            }
            $bnm1=Category::where('id',$request->sub_category_id)->first();
            if(isset($bnm1))
            {
            $sub_category_name=$bnm1->name;
            }
            $bnm2=SubCategory::where('id',$request->sub_sub_category_id)->first();
            if(isset($bnm2))
            {
            $sub_sub_category_name=$bnm2->name;
            }
            $bnm3=Product::where('id',$request->product_id)->first();
            if(isset($bnm3))
            {
            $product_name=$bnm3->name;
            }
            $bnm4=Brand::where('id',$request->brand_id)->first();
            if(isset($bnm4))
            {
            $brand_name=$bnm4->name;
            }
            
            if($request->type=='Home')
            {
            $dt='';
            $maindt='';
            }
            elseif($request->type=='Product')
            {
            $dt='productPage';
            $maindt=$dt.'#'.$product_name.'#'.$request->product_id;
            }
            elseif($request->type=='Category' &&  $request->category_id!='0' && $request->sub_category_id=='' && $request->sub_sub_category_id=='')
            {
            $dt='categoryPage';
            $maindt=$dt.'#'.$category_name.'#'.$request->category_id;
            }
            elseif($request->type=='Category' && $request->category_id!=''&& $request->sub_category_id!='' && $request->sub_sub_category_id=='')
            {
            $dt='allSubSubCategoryPage';
            $maindt=$dt.'#'.$category_name.'#'.$request->category_id.'#'.$sub_category_name.'#'.$request->sub_category_id;
            }
            elseif($request->type=='Category' && $request->category_id!=''&& $request->sub_category_id!='' && $request->sub_sub_category_id!='')
            {
            $dt='subCategoryPage';
            $maindt=$dt.'#'.$category_name.'#'.$request->category_id.'#'.$sub_category_name.'#'.$request->sub_category_id.'#'.$sub_sub_category_name.'#'.$request->sub_sub_category_id;
            }
            elseif($request->type=='Brand')
            {
            $dt='brandPage';
            $maindt=$dt.'#'.$brand_name.'#'.$request->brand_id;
            }
            
           
            foreach($user as $key)
            {   
            $request->merge(['user_id'=>$key->id]);
            $utoken=UserDeviceToken::where('user_id', $key->id)->orderBy('created_at','DESC');
            $utoken = $utoken->pluck('token')->toArray();
            $fcm =new FirebaseCloudMessagingUtility($request->title);
           // echo $request->image;exit;
            $ffcm=$fcm->sendData($utoken, ['image'=>$request->image,'order_id'=>'','venord_id'=>$request->title,'description' => $request->description,
            'type'=>$request->type,'category_id'=>$request->category_id,
            'sub_category_id'=>$request->sub_category_id,'sub_sub_category_id'=>$request->sub_sub_category_id,
            'brand_id'=>$request->brand_id,'product_id'=>$request->product_id,'pro_type'=>$maindt,
            'brand_name'=>$brand_name,'product_name'=>$product_name,'category_name'=>$category_name,'sub_category_name'=>$sub_category_name,'sub_sub_category_name'=>$sub_sub_category_name,
          ],2);
            $data = $request->only('user_id','vendor_id','title','type','product_id','category_id','sub_category_id','sub_sub_category_id','brand_id','image','is_send');
            $categoryId = Notification::create($data)->id;
        }
        exit;
        return [
            'category' => $categoryId
        ];
    }
     

    public function notificationlist($id)
    {
        $notification =Notification::where('user_id',$id)->orderBy('id','desc')->get();
        $notificationr =Notification::where('user_id',$id)->where('is_read',0)->orderBy('id','desc')->get();
        $data=[];
        foreach($notification as $key)
        {   
            $category_name='';
            $sub_category_name='';
            $sub_sub_category_name='';
            $product_name='';
            $brand_name='';
            $bnm=Category::where('id',$key->category_id)->first();
            if(isset($bnm))
            {
            $category_name=$bnm->name;
            }
            $bnm1=Category::where('id',$key->sub_category_id)->first();
            if(isset($bnm1))
            {
            $sub_category_name=$bnm1->name;
            }
            $bnm2=SubCategory::where('id',$key->sub_sub_category_id)->first();
            if(isset($bnm2))
            {
            $sub_sub_category_name=$bnm2->name;
            }
            $bnm3=Product::where('id',$key->product_id)->first();
            if(isset($bnm3))
            {
            $product_name=$bnm3->name;
            }
            $bnm4=Brand::where('id',$key->brand_id)->first();
            if(isset($bnm4))
            {
            $brand_name=$bnm4->name;
            }
            
            if($key->type=='Home')
            {
            $dt='';
            }
            elseif($key->type=='Product')
            {
            $dt='productPage';
            }
            elseif($key->type=='Category' &&  $key->category_id!='0' && $key->sub_category_id=='' && $key->sub_sub_category_id=='')
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
            elseif($key->type=='order')
            {
            $dt='orderPage';
            }
            $key->pro_type=$dt;
            $key->category_name=$category_name;
            $key->sub_category_name=$sub_category_name;
            $key->sub_sub_category_name=$sub_sub_category_name;
            $key->product_name=$product_name;
            $key->brand_name=$brand_name;
            $data[]=$key;
        }
        if(count($data)>0)
        {
             return ['status'=>200,'msg'=>'List Fetch','count'=>count($notificationr),'notification'=>$data];
        }
        else
        {
            return ['status'=>201,'msg'=>'No data found'];
        }
        
    }


    public function CheckNotification($id)
    {
          $order=Order::where('orders.id',$id)->join('order_statuses','order_statuses.id','=','orders.order_status_id')->select(['orders.*','order_statuses.name as order_statusn'])->first();
          $opr=OrderProduct::where('order_id',$id)->join('product_images', 'order_products.product_id','=', 'product_images.product_id')->limit(1)->select(['product_images.name as proimg'])->get();
          $user=User::where('id',$order->user_id)->first();
          $utoken=UserDeviceToken::where('user_id', $order->user_id)->orderBy('created_at','DESC')->limit(1);
          $utoken = $utoken->pluck('token')->toArray();
          $text='Hi'.$user->name.'  Order has been placed successfully.';
          $mbdy="We have recevied payment from you and waiting for vendor to accept and confirm your order.";
          $fcm =new FirebaseCloudMessagingUtility($text);
          $fcm->sendData($utoken, ['mbody' => $mbdy,'order_id'=>$id,'image' => $opr[0]->proimg,'order_status'=>$order->order_statusn],2);
    }
    
    public function clearnotification($id)
    {
        $notification =Notification::where('user_id',$id)->orderBy('id','desc')->get();
        foreach($notification as $key)
        {   
            $category = Notification::findOrFail($key->id);
            $data['is_read']=1;
            $category->update($data);
        }
        return ['notification_read'=>count($notification)];
    }
   
}
