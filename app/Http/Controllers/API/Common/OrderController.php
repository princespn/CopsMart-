<?php
namespace App\Http\Controllers\API\Common;
date_default_timezone_set('Asia/Kolkata');
// require_once('autoload.php');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\VendorWallet;
use App\User;
use App\Coupon;
use App\Makeabill;
use App\Vendor;
use App\UserAddress;
use App\Product;
use App\OrderAddress;
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
use App\DeliveryPersonDeviceToken;
use App\VendorDeviceToken;
use App\DeliveryPersonWallet;
use App\DeliveryPerson;
use App\UserDeviceToken;
use App\Stock;
use App\StockFetch;
use App\TmpPurchase;
use App\Purchase;
use App\Size;
use App\Color;
use App\SaleReport;
use App\PgSetting;
use App\AddCart;
use App\Notification;
use App\Utilities\SmsUtility;
use App\Utilities\FirebaseCloudMessagingUtility;
use DB;
use App\OrderUserRating;
use App\RazorpaySetting;
use App\PaytmSetting;
use DateTime;
use Carbon\Carbon;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use App\Utilities\DistanceUtility;
use Razorpay\Api\Api;
use Illuminate\Support\Str;
use paytm\checksum\PaytmChecksumLibrary;
class OrderController extends Controller
{  
    
    function checkdata()
    { 
   
     $order=Purchase::where('vendor_id',11)->where('deleted_at',NULL)->whereBetween('invoice_date', ['2022-01-01', '2022-01-31'])->get();
     foreach($order as $key2)
        {  
            $id[]=$key2->tmp_purchase_id;
        }
        
        foreach($id as $idd)
        {
            $iddd[]=$idd;
        }
        $idx=explode(',',implode(',',$iddd));
        
        $orderx=TmpPurchase::where('vendor_id',11)->where('deleted_at',NULL)->whereBetween('invoice_date', ['2022-01-01', '2022-01-31'])->get();
        foreach ($orderx as $keyyy)
        { 
            $fd[]=$keyyy->id;
        }
        $i=0;
        foreach($fd as $ll)
        {  
            if( in_array( $ll ,$idx ) )
            {
               
            }
            else
            {
               echo $ll;
            }
            $i++;
        }

        
    }
    
    function changehsn()
    { 
        $dtt=[];
       // echo "<pre>";
       $order=Order::where('vendor_id',11)->get();
       foreach($order as $key)
       {
           $op=OrderProduct::where('order_id',$key->id)->where('hsn',NULL)->where('deleted_at',NULL)->get();
           
           foreach($op as $keey)
           {
               $ppro=Product::where('id',$keey->product_id)->first();
               $orderd=OrderProduct::findOrFail($keey->id);
               $orderd->hsn=$ppro->hsn;
               $orderd->update();
           }
       }
       print_r($dtt);
    }



    function getemptyCartData($id)
    {
        $ppro=Makeabill::where('user_id',$id)->where('deleted_at',NULL)->delete();
        echo json_encode($ppro);
    }
    
    function emptyCartData($id)
    {
       $ppro=AddCart::where('user_id',$id)->where('deleted_at',NULL)->delete();
       if($ppro)
       {
           $response['resid']=200;
           $response['resmsg']='Cart Cleared Successfully';
       }else
       {
           $response['resid']=202;
           $response['resmsg']='Error Occured';
       }
       echo json_encode($response);
    }
    
    function checkorderpro($id,$size,$color,$sales,$purchase)
    {
        
        
        $orderpoint=OrderProduct::whereRaw('product_id='.$id)->whereRaw('size='.$size)->whereRaw('color='.$color)->whereRaw('price ='.$sales)->whereRaw('purchase_rate ='.$purchase)->get();
        $idd=[];
        $idds=[];
        $data=SaleReport::join('orders','orders.id','=','sale_reports.order_id')->join('users','users.id','=','sale_reports.user_id')->join('order_products','order_products.order_id','=','sale_reports.order_id')->where('sale_reports.vendor_id',11)->where('orders.vendor_id',11)->where('orders.deleted_at',NULL)->select(['sale_reports.*','orders.invoice_no','orders.date','order_products.qty','users.name as username']);
        $data->where('order_products.product_id',$id)->groupBy('sale_reports.order_id');
        $data=$data->get();
        echo  count($orderpoint);
        foreach($orderpoint as $kk)
        {
           $idd[]=$kk->order_id;
        }
        foreach($data as $key)
        {
           if(in_array($key->order_id, $idd)){
                
            } 
            else
            {
                $idds[]=$key->order_id;
            }
            
        }
        print_r($idds);
    }
    
    
    
    function order_datatable(Request $request ,$id)
    {
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        
        $query = Order::where('orders.vendor_id',$id)->join('order_statuses', 'orders.order_status_id','=', 'order_statuses.id')->orderBy('orders.id','desc')->select(['orders.*','order_statuses.name as orderstatus']);
        if($searchValue!=''){
            $query->where('orders.id', "LIKE", "%$searchValue%")->orWhere('orders.invoice_no', "LIKE", "%$searchValue%");;
        }
        $data = $query->paginate($length);
        // echo count($data);exit;
        $dt=[];
        if(count($data)>0){
            $dt=[];
            $i=1;
            foreach($data as $key)
            {   
                $users=User::where('id',$key->user_id)->first();
                if(isset($users))
                {
                    $key->nameuser=$users->name;
                }
                else
                {
                    $key->nameuser='Account Removed';
                }
                $key->srno=$i;
                $key->orderdate=date('d-m-Y H:i A',strtotime($key->created_at));
                $std=DeliveryStatus::where('id',$key->delivery_status_id)->first();
                // print_r($std);exit;
                if($key->delivery_status_id==null)
                {
                    $key->deliverystatus='NA';
                }
                else
                {
               
                if(isset($std)) 
                {
                    $key->deliverystatus=$std->name;
                }
                else
                {
                    $key->deliverystatus='NA';
                }
            }
                $dt[]=$key;
                $i++;
            }
            $dt=$data;
            return new DataTableCollectionResource($dt);
        }
        else
        {
            return new DataTableCollectionResource($data);
        }
        
     
       
    }

    function order_datatable_delwise(Request $request ,$id)
    {
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        
        $query = Order::where('orders.delivery_person_id',$id)->where('orders.amount','!=','0')->join('order_statuses', 'orders.order_status_id','=', 'order_statuses.id')->orderBy('orders.id','desc')->select(['orders.*','order_statuses.name as orderstatus']);
        if($searchValue!=''){
            $query->where('orders.id', "LIKE", "%$searchValue%")->orWhere('orders.invoice_no', "LIKE", "%$searchValue%");;
        }
        $data = $query->paginate($length);
        // echo count($data);exit;
        $dt=[];
        if(count($data)>0){
            $dt=[];
            $i=1;
            foreach($data as $key)
            {   
                $users=User::where('id',$key->user_id)->first();
                if(isset($users))
                {
                    $key->nameuser=$users->name;
                }
                else
                {
                    $key->nameuser='Account Removed';
                }
                $key->srno=$i;
                $key->orderdate=date('d-m-Y H:i A',strtotime($key->created_at));
                $std=DeliveryStatus::where('id',$key->delivery_status_id)->first();
                // print_r($std);exit;
                if($key->delivery_status_id==null)
                {
                    $key->deliverystatus='NA';
                }
                else
                {
               
                if(isset($std)) 
                {
                    $key->deliverystatus=$std->name;
                }
                else
                {
                    $key->deliverystatus='NA';
                }
            }
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
                $dt[]=$key;
                $i++;
            }
            $dt=$data;
            return new DataTableCollectionResource($dt);
        }
        else
        {
            return new DataTableCollectionResource($data);
        }
        
     
       
    }

    function vendor_datatable(Request $request ,$id)
    {
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        $query = Vendor::where('vendors.admin_id',$id)->join('users', 'users.admin_id','=', 'vendors.id')->select(['vendors.*','users.id as vendor_ad_id']);
        if($searchValue!=''){
            $query->where('vendors.name', "LIKE", "%$searchValue%");
        }
        $data = $query->paginate($length);
        // echo count($data);exit;
        $dt=[];
        if(count($data)>0){
            $dt=[];
            $i=1;
            foreach($data as $key)
            {   
                $totalo=Order::where('vendor_id',$key->vendor_ad_id)->where('order_status_id','!=',3)->get()->count();
                $pending_orders=Order::where('vendor_id',$key->vendor_ad_id)->whereNotIn('order_status_id',[3,9])->get()->count();
                $delivered=Order::where('vendor_id',$key->vendor_ad_id)->where('order_status_id','=',9)->get()->count();
                
                $key->total_order=$totalo;
                $key->pending_orders=$pending_orders;
                $key->delivered=$delivered;
                
                $key->srno=$i;
                $dt[]=$key;
                $i++;
            }
            $dt=$data;
            return new DataTableCollectionResource($dt);
        }
        else
        {
            return new DataTableCollectionResource($data);
        }
        
     
       
    }

    function order_online_datatable(Request $request ,$id)
    {
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        
        $query = Order::where('orders.vendor_id',$id)->where('orders.makeabill',0)->join('order_statuses', 'orders.order_status_id','=', 'order_statuses.id')->orderBy('orders.id','desc')->select(['orders.*','order_statuses.name as orderstatus']);
        if($searchValue!=''){
            $query->where('orders.id', "LIKE", "%$searchValue%");
        }
        $data = $query->paginate($length);
        // echo count($data);exit;
        $dt=[];
        if(count($data)>0){
            $dt=[];
            $i=1;
            foreach($data as $key)
            {   
                $users=User::where('id',$key->user_id)->first();
                if(isset($users))
                {
                    $key->nameuser=$users->name;
                }
                else
                {
                    $key->nameuser='Account Removed';
                }
                $key->srno=$i;
                $key->orderdate=date('d-m-Y H:i A',strtotime($key->created_at));
                $std=DeliveryStatus::where('id',$key->delivery_status_id)->first();
                // print_r($std);exit;
                if($key->delivery_status_id==null)
                {
                    $key->deliverystatus='NA';
                }
                else
                {
               
                if(isset($std)) 
                {
                    $key->deliverystatus=$std->name;
                }
                else
                {
                    $key->deliverystatus='NA';
                }
            }
                $dt[]=$key;
                $i++;
            }
            $dt=$data;
            return new DataTableCollectionResource($dt);
        }
        else
        {
            return new DataTableCollectionResource($data);
        }
        
     
       
    }

    function order_count($a)
    {   
        $totalOrders = DB::table('orders')->where('vendor_id',$a)->whereRaw('order_status_id != 3')->selectRaw('Count(id) as total')->first()->total;
        $deliOrders = DB::table('orders')->where('vendor_id',$a)->whereRaw('order_status_id = 9')->selectRaw('Count(id) as total')->first()->total;
        $newOrders = DB::table('orders')->where('vendor_id',$a)->whereRaw('order_status_id IN ("4","5","6","7")')->selectRaw('Count(id) as total')->first()->total;
        $orderAmount =  DB::table('orders')->where('vendor_id',$a)->whereRaw('order_status_id = 9')->selectRaw('SUM(amount) as total')->first()->total;;
        $vendor=Vendor::where('users.id',$a)->join('users', 'users.admin_id','=', 'vendors.id')->select(['vendors.*','users.id as vendor_ad_id'])->get();
        //    print_r( $orderAmount );exit;
        return [
            'total' => $totalOrders,
            'delivered' => $deliOrders,
            'neworder' => $newOrders,
            'sales' => $orderAmount,
            'vendor'=>$vendor,
        ];
    }

    function acceptorder($id)
    {
        // $order=Order::findorfail($id);
        $order = Order::findOrFail($id);
        if($order->order_status_id>=5)
        {
            return 2;
        }
        else
        {
        $vendorStatus = VendorStatus::where('code', 'accepted')->first();
        $vendorPickedStatusId= $vendorStatus->id;
        $orderStatus = OrderStatus::where('code', 'confirmed')->first();
        $orderPickedStatusId= $orderStatus->id;
        $data = [
            'order_status_id' => $orderPickedStatusId,
            'vendor_status_id' => $vendorPickedStatusId,
            // 'delivery_status_id' => $deliveryPickedStatusId,
            'status_updated' => date('Y-m-d H:i:s')
        ];
          if($order->update($data))
          {  
            $this->AcceptOrderNotification($id);
            return 1;
          }
          else
          {
            return 0 ;
          }
        }        
    }

    function packageorder($id)
    {
        // $order=Order::findorfail($id);
        $order = Order::findOrFail($id);
        if($order->order_status_id>=6)
        {
            return 2;
        }
        else if($order->order_status_id==5)
        {
            $vendorStatus = VendorStatus::where('code', 'packaging')->first();
            $vendorPickedStatusId= $vendorStatus->id;
            $orderStatus = OrderStatus::where('code', 'ready')->first();
            $orderPickedStatusId= $orderStatus->id;
            if($order->delivery_status_id==1)
            {
                $deliveryStatus = DeliveryStatus::where('code', 'packed')->first();
                $deliveryPickedStatusId=$deliveryStatus->id;
            }
            else
            {
                $deliveryPickedStatusId=$order->delivery_status_id;
            }
            $data = [
                'order_status_id' => $orderPickedStatusId,
                'vendor_status_id' => $vendorPickedStatusId,
                'delivery_status_id' => $deliveryPickedStatusId,
                'status_updated' => date('Y-m-d H:i:s')
            ];
            if($order->update($data))
            {  
                $this->PackageOrderNotification($id);
                return 1;
            }
            else
            {
                return 0 ;
            }
        }
        else
        {
          return 3 ;
        }
    }

    function outfordelivery($id)
    {
        // $order=Order::findorfail($id);
        $order = Order::findOrFail($id);
        if($order->order_status_id>=7)
        {
            return 2;
        }
        else if($order->order_status_id==6)
        {
        $vendorStatus = VendorStatus::where('code', 'out_for_delivery')->first();
        $vendorPickedStatusId= $vendorStatus->id;
        $orderStatus = OrderStatus::where('code', 'out_for_delivery')->first();
        $orderPickedStatusId= $orderStatus->id;
        if($order->delivery_status_id==1 && $order->delivery_person_id!='')
        {
            $deliveryStatus = DeliveryStatus::where('code', 'out_for_delivery')->first();
            $deliveryPickedStatusId=$deliveryStatus->id;
        }
        else
        {
            $deliveryPickedStatusId=$order->delivery_status_id;
        }
        $data = [
            'order_status_id' => $orderPickedStatusId,
            'vendor_status_id' => $vendorPickedStatusId,
            'delivery_status_id' => $deliveryPickedStatusId,
            'status_updated' => date('Y-m-d H:i:s')
        ];
          if($order->update($data))
          { 
            $this->OutForOrderNotification($id);
            return 1;
          }
          else
          {
            return 0 ;
          }
        }
        else
        {
          return 3 ;
        }
       
    }


    function deliveredorder($id)
    {
        // $order=Order::findorfail($id);
        $order = Order::findOrFail($id);
        if($order->order_status_id>=9)
        {
            return 2;
        }
        else if($order->delivery_person_id==null)
        {
          return 4;
        }
        else if($order->order_status_id==7)
        {
            $vendorStatus = VendorStatus::where('code', 'delivered')->first();
            $vendorPickedStatusId= $vendorStatus->id;
            $orderStatus = OrderStatus::where('code', 'delivered')->first();
            $orderPickedStatusId= $orderStatus->id;
            if($order->delivery_status_id==1)
            {
                $deliveryStatus = DeliveryStatus::where('code', 'delivered')->first();
                $deliveryPickedStatusId=$deliveryStatus->id;
            }
            else
            {
                $deliveryPickedStatusId=$order->delivery_status_id;
            }
            $data = [
                'order_status_id' => $orderPickedStatusId,
                'vendor_status_id' => $vendorPickedStatusId,
                'delivery_status_id' => $deliveryPickedStatusId,
                'status_updated' => date('Y-m-d H:i:s')
            ];
            if($order->update($data))
            {  
                $this->DeliveredForOrderNotification($id);
                return 1;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 3;
        }
    }
   
    function orderdetails($id)
    {
         $order=Order::where('orders.id',$id)->join('order_statuses','order_statuses.id','=','orders.order_status_id')->select(['orders.*','order_statuses.name as orderstatus'])->get();
      //   print_r($order);
         $dt=[];
         if(count($order)>0)
         {  
            foreach($order as $key)
            {   
                //echo $key->vendor_id;exit;
                $key->ordertotalno=Order::where('user_id',$key->user_id)->where('amount','!=',0)->get()->count();
                $us=User::where('id',$key->vendor_id)->first();
                // print_r($us);exit;
               // $users=User::where('id',$key->user_id)->first();
                $vendor=Vendor::where('id',$us->admin_id)->first();
                $key->vendorname=$vendor->name;
                $ordp=OrderPayment::where('order_id',$key->id)
                ->join('order_payment_methods','order_payment_methods.order_payment_id','=','order_payments.id')
                ->join('payment_methods','order_payment_methods.payment_method_id','=','payment_methods.id')
                ->join('order_payment_method_responses','order_payment_method_responses.order_payment_method_id','=','order_payment_methods.id')
                ->select('order_payment_method_responses.*','payment_methods.name as pname')->first();
                $key->order_payment=$ordp;  
                $key->payment_mode=ucfirst($ordp->payment_mode);
                $del=DeliveryPerson::where('id',$key->delivery_person_id)->first();
                $key->delivery_person=$del;
                $keys=User::where('id',$key->user_id)->first();
               if(isset($keys))
                {
                    $key->user=$keys;
                }
                else
                {
                    $key->user=[];
                }
                $vennnc=Vendor::where('id',$us->admin_id)->first();
                if($vennnc->open_time!=''){$vennnc->opentime=date('h:i A',strtotime($vennnc->open_time));}
                if($vennnc->close_time!=''){$vennnc->closetime=date('h:i A',strtotime($vennnc->close_time));}
              $key->vendors=$vennnc;
              $key->useraddress=OrderAddress::where('order_id',$key->id)->first();
              $pro=OrderProduct::where('order_products.order_id',$key->id)->join('products','order_products.product_id','=','products.id')->join('colors','order_products.color','=','colors.id')->join('sizes','order_products.size','=','sizes.id')->where('order_products.deleted_at', null)->select(['order_products.*','products.hsn as prohsn','sizes.name as size_name','colors.name as color_name','products.name as p_v_name'])->get();
              $prod=[];
              foreach($pro as $kkey)
              {
                $prod[]=$kkey;
              }
              $key->products=$prod;
              $dt[]=$key;
            }
         }
         return $dt;
    }
    
    public function CustOrderDetails( int $id)
    {
       
        $orders =  Order::with(['address', 'status'])->where('id',$id)->orderBy('id', 'DESC')->get();
        foreach ($orders as $key => $order) {
            $products = DB::table('order_products')->where('order_products.order_id',$order->id)->where('order_products.deleted_at', null)
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->join('sizes','sizes.id','=','order_products.size')
            ->join('colors','colors.id','=','order_products.color')
            // ->join('product_images','product_images.product_id','=','order_products.product_id')
            ->select('order_products.*','products.*','colors.name as selected_color','sizes.name as selected_size')->get();
            // $coupon =  Coupon::where('id', $order->coupon_id)->get();
            // $order->coupon = $coupon;
            $order->products = $products;
            $order->delivery_person = DB::table('delivery_people')->where('id', $order->delivery_person_id)->first();
            // $order->product_image = ProductImage::where('product_id', $order->delivery_person_id)->first();
            $orders[$key] = $order;
            $orders[$key]['order_date'] = date('d/m/Y h:i A', strtotime($order->created_at));
        }
        $tot=0;
        $totg=0;
        if(count($orders)>0)
        {
         foreach($orders as $key)
         {          $ss=[];
            foreach($key->products as $kkey)
            {
                $proimg=ProductImage::where('product_id',$kkey->id)->orderBy('id','desc')->limit(1)->first();
               // print_r($proimg['name']);
                if(isset($proimg))
                {
                    $kkey->product_image=$proimg['name'];
                }
                else
                {
                    $kkey->product_image='';
                }
               $tot +=$kkey->taxable_rate*$kkey->qty;
                $totg +=$kkey->tgst*$kkey->qty;
                $ss[]=$kkey;
                
                
            }
            $key->total_taxable=round($tot,2);
            $key->total_gst= round($totg,2) ;
            $sdt=$tot+$totg;
            $key->final_total=round($sdt,2);
            $key->products=$ss;
            $ordp=OrderPayment::where('order_id',$key->id)
            ->join('order_payment_methods','order_payment_methods.order_payment_id','=','order_payments.id')
            ->join('order_payment_method_responses','order_payment_method_responses.order_payment_method_id','=','order_payment_methods.id')
            ->select('order_payment_method_responses.*')->first();
            $key->order_payment=$ordp;
            $key->payment_mode=ucfirst($ordp->payment_mode);
            $us=User::where('id',$key->vendor_id)->first();
            $vendor=Vendor::where('id',$us->admin_id)->first();
            $key->vendor_details=Vendor::where('id',$us->admin_id)->first();
            $key->user=User::where('id',$key->user_id)->first();
            $key->invoice_no='INV'.$key->id;
            $data[]=$key;
         }
         return $data;
        }
        else
        {
            return $orders;
        }
 
    }

    public function index($customerId)
    {
        $query = DB::table('orders')->where('orders.user_id',$customerId)
        ->join('users', 'orders.user_id','=', 'users.id')
        ->join('vendors', 'vendor_id','=', 'vendors.id')
        ->where('orders.payment_status', 1)
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*','users.name', 'users.email', 'users.mobile','vendors.name');
        $type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : 'all';
        switch ($type) {
            case 'active':
                $query->whereRaw("vendor_status_id IS NOT NULL AND vendor_status_id<>'2' ");
                break;
            case 'pending':
                $query->where("vendor_status_id", null);
                break;
            default:
                break;
        }
        return $query->get();
    }

    public function checkLastOrderFeedback(Request $request, $userId)
    {
        return Order::where(['order_status_id' => 9,'user_id' => $userId])->latest()->with('rating')->first();
    }
    
    public function ordersByUser($user)
    {
        // return Order::where('user_id', $user)->with( 'status')->get();
        if($_GET['type']=='delivered')
        {
            $orders =  Order::where('order_status_id',9)->where('amount','!=',0)->where('user_id', $user)->with(['address', 'status'])->orderBy('id', 'DESC')->get();
            foreach ($orders as $key => $order) {
                $products = DB::table('order_products')->where('order_id',$order->id)
                ->join('products', 'products.id', '=', 'order_products.product_id')
                ->join('sizes','sizes.id','=','order_products.size')
                ->join('colors','colors.id','=','order_products.color')->where('order_products.deleted_at', null)
                // ->join('product_images','product_images.product_id','=','order_products.product_id')
                ->select('order_products.*','products.*','colors.name as selected_color','sizes.name as selected_size')->get();
                // $coupon =  Coupon::where('id', $order->coupon_id)->get();
                // $order->coupon = $coupon;
                $order->products = $products;
                $order->delivery_person = DB::table('delivery_people')->where('id', $order->delivery_person_id)->first();
                // $order->product_image = ProductImage::where('product_id', $order->delivery_person_id)->first();
                $orders[$key] = $order;
                $orders[$key]['order_date'] = date('d/m/Y h:i A', strtotime($order->created_at));
            }
            if(count($orders)>0)
            {
             foreach($orders as $key)
             {          
                 $ss=[];
                foreach($key->products as $kkey)
                {
                    $proimg=ProductImage::where('product_id',$kkey->id)->orderBy('id','desc')->limit(1)->first();
                    if(isset($proimg))
                    {
                      $kkey->product_image=$proimg->name;
                    }
                    else
                    {
                    $kkey->product_image='';
                    }
                    $ss[]=$kkey;
                }
                $key->products=$ss;
                $ordp=OrderPayment::where('order_id',$key->id)
                ->join('order_payment_methods','order_payment_methods.order_payment_id','=','order_payments.id')
                ->join('order_payment_method_responses','order_payment_method_responses.order_payment_method_id','=','order_payment_methods.id')
                ->select('order_payment_method_responses.*')->first();
                $key->order_payment=$ordp;
                $key->invoice_no='INV'.$key->id;
                $data[]=$key;
             }
             return $data;
            }
            else
            {
                return $orders;
            }
        }
        elseif($_GET['type']=='inprogress')
        {
         $orders =  Order::whereNotIn('order_status_id', [9])->where('amount','!=',0)->where('user_id', $user)->with(['address', 'status'])->orderBy('id', 'DESC')->get();
        foreach ($orders as $key => $order) {
            $products = DB::table('order_products')->where('order_id',$order->id)
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->join('sizes','sizes.id','=','order_products.size')
            ->join('colors','colors.id','=','order_products.color')->where('order_products.deleted_at', null)
            // ->join('product_images','product_images.product_id','=','order_products.product_id')
            ->select('order_products.*','products.*','colors.name as selected_color','sizes.name as selected_size')->get();
            // $coupon =  Coupon::where('id', $order->coupon_id)->get();
            // $order->coupon = $coupon;
            $order->products = $products;
            $order->delivery_person = DB::table('delivery_people')->where('id', $order->delivery_person_id)->first();
            // $order->product_image = ProductImage::where('product_id', $order->delivery_person_id)->first();
            $orders[$key] = $order;
            $orders[$key]['order_date'] = date('d/m/Y h:i A', strtotime($order->created_at));
        }
        if(count($orders)>0)
        {
         foreach($orders as $key)
         {          
             $ss=[];
            foreach($key->products as $kkey)
            {
                $proimg=ProductImage::where('product_id',$kkey->id)->orderBy('id','desc')->limit(1)->first();
                $kkey->product_image=$proimg->name;
                $ss[]=$kkey;
            }
            $key->products=$ss;
            $ordp=OrderPayment::where('order_id',$key->id)
            ->join('order_payment_methods','order_payment_methods.order_payment_id','=','order_payments.id')
            ->join('order_payment_method_responses','order_payment_method_responses.order_payment_method_id','=','order_payment_methods.id')
            ->select('order_payment_method_responses.*')->first();
            $key->order_payment=$ordp;
            $key->invoice_no='INV'.$key->id;
            $data[]=$key;
         }
         return $data;
        }
        else
        {
            return $orders;
        }
         }
    }
    
    
    
    public function ordersByUserNew(Request $request,$user)
    {
        // return Order::where('user_id', $user)->with( 'status')->get();
        $total_records_per_page =$request->total_cnt;
        $page_no=$request->page_no;
        $offset = ($page_no-1) * $total_records_per_page;
        if($_GET['type']=='delivered')
        {   
            
            $ordersx =  Order::where('order_status_id',9)->where('amount','!=',0)->where('user_id', $user)->with(['address', 'status'])->orderBy('id', 'DESC')->get();
            $nnct=count($ordersx);
            if($nnct>0){
                 $total_pages=ceil($nnct/$total_records_per_page);
                $dtt=[];
                $orders =  Order::where('order_status_id',9)->where('amount','!=',0)->where('user_id', $user)->with(['address', 'status'])->offset($offset)->limit($total_records_per_page)->orderBy('id', 'DESC')->get();
                foreach ($orders as $key => $order) {
                    $products = DB::table('order_products')->where('order_id',$order->id)
                    ->join('products', 'products.id', '=', 'order_products.product_id')
                    ->join('sizes','sizes.id','=','order_products.size')
                    ->join('colors','colors.id','=','order_products.color')->where('order_products.deleted_at', null)
                    // ->join('product_images','product_images.product_id','=','order_products.product_id')
                    ->select('order_products.*','products.*','colors.name as selected_color','sizes.name as selected_size')->get();
                    // $coupon =  Coupon::where('id', $order->coupon_id)->get();
                    // $order->coupon = $coupon;
                    $order->products = $products;
                    $order->delivery_person = DB::table('delivery_people')->where('id', $order->delivery_person_id)->first();
                    // $order->product_image = ProductImage::where('product_id', $order->delivery_person_id)->first();
                    $orders[$key] = $order;
                    $orders[$key]['order_date'] = date('d/m/Y h:i A', strtotime($order->created_at));
                }
                if(count($orders)>0)
                {
                 foreach($orders as $key)
                 {          
                     $ss=[];
                    foreach($key->products as $kkey)
                    {
                        $proimg=ProductImage::where('product_id',$kkey->id)->orderBy('id','desc')->limit(1)->first();
                        if(isset($proimg))
                        {
                          $kkey->product_image=$proimg->name;
                        }
                        else
                        {
                        $kkey->product_image='';
                        }
                        $ss[]=$kkey;
                    }
                    $key->products=$ss;
                    $ordp=OrderPayment::where('order_id',$key->id)
                    ->join('order_payment_methods','order_payment_methods.order_payment_id','=','order_payments.id')
                    ->join('order_payment_method_responses','order_payment_method_responses.order_payment_method_id','=','order_payment_methods.id')
                    ->select('order_payment_method_responses.*')->first();
                    $key->order_payment=$ordp;
                    $key->invoice_no='INV'.$key->id;
                    $data[]=$key;
                 }
                
                   $dtt['count']=$nnct;
                   $dtt['total_pages']=$total_pages;
                   $dtt['data']=$data;
                 return $dtt;
                }
                else
                {
                    return $dtt;
                }
            }
        }
        elseif($_GET['type']=='inprogress')
        {
         $ordersx =  Order::whereNotIn('order_status_id', [9])->where('amount','!=',0)->where('user_id', $user)->with(['address', 'status'])->orderBy('id', 'DESC')->get();
         $nnct=count($ordersx);
         if($nnct>0){
              $dtt=[];
               $total_pages=ceil($nnct/$total_records_per_page);
             $orders =  Order::whereNotIn('order_status_id', [9])->where('amount','!=',0)->where('user_id', $user)->with(['address', 'status'])->offset($offset)->limit($total_records_per_page)->orderBy('id', 'DESC')->get();
            foreach ($orders as $key => $order) {
                $products = DB::table('order_products')->where('order_id',$order->id)
                ->join('products', 'products.id', '=', 'order_products.product_id')
                ->join('sizes','sizes.id','=','order_products.size')
                ->join('colors','colors.id','=','order_products.color')->where('order_products.deleted_at', null)
                // ->join('product_images','product_images.product_id','=','order_products.product_id')
                ->select('order_products.*','products.*','colors.name as selected_color','sizes.name as selected_size')->get();
                // $coupon =  Coupon::where('id', $order->coupon_id)->get();
                // $order->coupon = $coupon;
                $order->products = $products;
                $order->delivery_person = DB::table('delivery_people')->where('id', $order->delivery_person_id)->first();
                // $order->product_image = ProductImage::where('product_id', $order->delivery_person_id)->first();
                $orders[$key] = $order;
                $orders[$key]['order_date'] = date('d/m/Y h:i A', strtotime($order->created_at));
            }
            if(count($orders)>0)
            {
             foreach($orders as $key)
             {          
                 $ss=[];
                foreach($key->products as $kkey)
                {
                    $proimg=ProductImage::where('product_id',$kkey->id)->orderBy('id','desc')->limit(1)->first();
                    $kkey->product_image=$proimg->name;
                    $ss[]=$kkey;
                }
                $key->products=$ss;
                $ordp=OrderPayment::where('order_id',$key->id)
                ->join('order_payment_methods','order_payment_methods.order_payment_id','=','order_payments.id')
                ->join('order_payment_method_responses','order_payment_method_responses.order_payment_method_id','=','order_payment_methods.id')
                ->select('order_payment_method_responses.*')->first();
                $key->order_payment=$ordp;
                $key->invoice_no='INV'.$key->id;
                $data[]=$key;
             }
             $dtt['count']=$nnct;
                   $dtt['total_pages']=$total_pages;
                   $dtt['data']=$data;
                 return $dtt;
            }
            else
            {
                return $dtt;
            }
          }
        }
    }

    public function placeOrder(Request $request)
    {
         $validator = \Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'delivery_charges' => 'required|numeric',
            'totalprice' => 'required|numeric',
            'user_address_id' => 'required|numeric',
            'vendor_id' => 'required|numeric',
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
          //   print_r($items);exit;
        $order = $this->createOrder($request, $items);
        $sms = new SmsUtility;
        //$sms->sendmessage(['9403240714'], 'Order '.$order['order_id']. ' placed successfully.' , 2);
        // $sms->sendmessage(['9168286223'], 'Order '.$order['order_id']. ' placed successfully.' , 2);
        $dataa=array();
        //$this->sendNewDeliveryNotification($request->user_address_id, $request->vendor_id, $order['order_id'],$calculatedItems->delivery_charges,$dataa);
        return $order;
        // }
    }

    private function createOrder($data, $items)
    {
        // return DB::transaction(function () use ($data, $items) {
            $paytmresponse=json_decode($data->paytm_response);
            $pendingOrderStatus = OrderStatus::where(['code'=>'placed'])->pluck('id')->first();
            $orderfetch=Order::where('vendor_id', $data->vendor_id)->orderBy('id','desc')->first();
            if(isset($orderfetch)) 
            {
                $innv=$orderfetch->invoice_no;
                if($innv!='')
                {
                $exp=explode('_',$innv);
                $expp=$exp[1]+1;
                }else
                {
                    $expp=1;
                }
            }
            else
            {
                $expp=1;
            }
            
            if($data->delivery_type=='Counter Billing')
            {
                $pendingOrderStatus = OrderStatus::where(['code'=>'out_for_delivery'])->pluck('id')->first();
            }


            $orderData = [
                'user_id' =>  $data->user_id,
                'vendor_id' => $data->vendor_id,
                'invoice_no' => $data->vendor_id.'_'.$expp,
                'amount' => $data->totalprice,
                'delivery_charges' => $data->delivery_charges,
                'delivery_type' => $data->delivery_type,
                'date' => date('Y-m-d'),
                'payment_status'=>1,
                'order_status_id' => $pendingOrderStatus,
                'status_updated' => date('Y-m-d H:i:s'),
                'itemtotal'=>$data->totalprice,
                'scheduled_delivery_date' => date('Y-m-d H:i:s', strtotime('120 minutes')),
            ];
            $order = Order::create($orderData); // creating Order
            $ordid= $order->id;

            $orderProducts =[];
            foreach ($items as  $item) {
                $csssdata=AddCart::where('id',$item->cart_id)->first();
                $stc=StockFetch::where('id',$csssdata->stockfetch_id)->first();
                // $datst=[];
                  $proo=Stock::where('vendor_id',$data->vendor_id)->where('product_id',$item->product_id)->where('size',$item->size)->where('color',$item->color)->whereRaw('price='.$item->price)->whereRaw('purchase_rate='.$stc->purchase_rate)->first();
                  $prooid=StockFetch::where('vendor_id',$data->vendor_id)->where('product_id',$item->product_id)->where('size',$item->size)->where('color',$item->color)->whereRaw('sales_rate='.$item->price)->whereRaw('purchase_rate='.$stc->purchase_rate)->first();
                   if(isset($proo) && isset($prooid))
                 {
                  $sqty=$proo->sold_qty;
                  $sidc=Stock::findOrFail($proo->id);
                  $sidc->sold_qty=$sqty+$item->qty;
                  $sidc->update();

                  
                  $sqtyx=$prooid->quantity;
                  $sidcs=StockFetch::findOrFail($prooid->id);
                  $sidcs->quantity=$sqtyx-$item->qty;
                  $sidcs->update();
                  $pro=Product::where('id',$item->product_id)->first();
                  $orderProduct =[
                        'order_id' => $ordid,
                        'product_id' => $item->product_id,
                        'name' => $item->name,
                        'price' => $item->price,
                        'hsn' => $pro->hsn,
                        'mrp' => $item->mrp,
                        'qty' => $item->qty,
                        'size'=>$item->size,
                        'color' =>$item->color,
                        'gst'=>$proo->gst,
                        'tgst' =>$proo->tgst,
                        'cgst'=>$proo->cgst,
                        'sgst' =>$proo->sgst,
                        'igst'=>$proo->igst,
                        'taxable_rate' =>$proo->taxable_rate,
                         'purchase_rate' =>$proo->purchase_rate,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                 }
                 else
                 {   
                      $pro=Product::where('id',$item->product_id)->first();
                     $orderProduct =[
                        'order_id' => $ordid,
                        'product_id' => $item->product_id,
                        'name' => $pro->name,
                        'price' => 0,
                        'mrp' => 0,
                         'hsn' => 0,
                        'qty' => 0,
                        'size'=>0,
                        'color' =>0,
                        'gst'=>0,
                        'tgst' =>0,
                        'cgst'=>0,
                        'sgst' =>0,
                        'igst'=>0,
                        'taxable_rate' =>0,
                        'purchase_rate' =>0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                 }
                      $orderProducts[] = $orderProduct;
                      $vendorProduct = AddCart::findOrFail($item->cart_id);
                      $vendorProduct->delete();
                   }
            OrderProduct::insert($orderProducts); // inserting order items in db
            //exit;
            $address =  UserAddress::findOrFail($data->user_address_id);
            
            OrderAddress::create([
                'order_id' => $ordid,
                'mobile' => $address->mobile,
                'name' => $address->name,
                'address' => $address->address,
                'title' => $address->title,
                'pincode' => $address->pincode,
                'state' => $address->state,
                'district' => $address->district,
                'lat' => $address->lat,
                'long' => $address->long,
            ]);

            $orderPaymentId = OrderPayment::create([
                'order_id'=>$ordid,
                'amount' => $data->totalprice,
            ]);
            $poid=$orderPaymentId->id;
            $paymentMethod = PaymentMethod::findOrFail($data->payment_method_id);
            $opmData =[
                'order_payment_id' =>$poid,
                'payment_method_id' =>$paymentMethod->id,
                'amount' =>$data->totalprice
            ];
            
            $orderPaymentMethodId =  OrderPaymentMethod::create($opmData)->id;
            //   $orderPaymentMethodId=$orderPaymentMethodId;
            $opmData['id'] = $orderPaymentMethodId;
           
            $paymentParams = NULL;
           
            $placedOrderStatus = OrderStatus::where(['code'=>'placed'])->pluck('id')->first();
            if($data->delivery_type=='Counter Billing')
            {
                $placedOrderStatus = OrderStatus::where(['code'=>'out_for_delivery'])->pluck('id')->first();
            }
            DB::table('orders')->where('id',$order)->update(['order_status_id'=>$placedOrderStatus, 'payment_status'=>1]);
            $orderPayment = OrderPayment::findorFail($poid);
            $orderPayment->status = 1;
            $orderPayment->update();
            //print_r($orderPayment);exit;
            $orderPaymentMethod = OrderPaymentMethod::findorFail($orderPaymentMethodId);
            $orderPaymentMethod->status = 1;
            $orderPaymentMethod->update();
            if($data->payment_method_id==3)
            {
                
                $q_data['status']= 1;
                $q_data['payment_method_id'] =$data->payment_method_id;
                $q_data['transaction_uid']= $data->payment_id;
                $q_data['order_payment_method_id']= $orderPaymentMethodId;
                $q_data['payment_status_id']= 1;
                $q_data['amount']= $data->totalprice;
                $q_data['payment_mode']= $this->GetFetchDatadddd($data->payment_id);
                $q_data['response_msg']= 'SUCCESS';
            }
            elseif($data->payment_method_id==2)
            {  
                $itemsz= json_decode($data->paytm_response);
                // print_r($itemsz[0]);
                $q_data['order_payment_method_id']= $orderPaymentMethodId;
                $q_data['payment_method_id'] =$data->payment_method_id;
                $q_data['payment_status_id']= 1;
                $q_data['amount']= $data->totalprice;
                $q_data['transaction_uid']= $itemsz[0]->TXNID;
                
                $q_data['bank_name']= $itemsz[0]->GATEWAYNAME;
                $q_data['bank_txn_id'] =$itemsz[0]->BANKTXNID;
                $q_data['response_object']=$data->paytm_response;
                $q_data['currency']= $itemsz[0]->CURRENCY;
                $q_data['gateway_name']= $itemsz[0]->GATEWAYNAME;
                
                $q_data['response_msg'] =$itemsz[0]->RESPMSG;
                $q_data['payment_mode']=$itemsz[0]->PAYMENTMODE;
                $q_data['status']= 1;
                $q_data['status_code']= $itemsz[0]->RESPCODE;
                
            }

            $opmr_status = OrderPaymentMethodResponse::create($q_data);
            // $orrrd = Order::findorFail($ordid);
            // $orrrd->payment_status = 1;
            // $orrrd->update();
            $this->PlacedOrderNotification($ordid);
            $this->makesalesreport($ordid);
            return [
                'order_id' => $order,
                'payment' => [
                    'payment_method' => $paymentMethod,
                    'params' => $paymentParams
                ],
                'message' => 'Order created Successfully'
            ];
        // }, 5); // transaction ends here
    }
    
    public function CheckNotification($id)
    {
          $order=Order::where('orders.id',$id)->join('order_statuses','order_statuses.id','=','orders.order_status_id')->select(['orders.*','order_statuses.name as order_statusn'])->first();
          $opr=OrderProduct::where('order_id',$id)->join('product_images', 'order_products.product_id','=', 'product_images.product_id')->limit(1)->select(['product_images.name as proimg'])->get();
          $user=DeliveryPerson::where('id',$order->delivery_person_id)->first();
          $utoken=DeliveryPersonDeviceToken::where('delivery_person_id', $order->delivery_person_id)->orderBy('created_at','DESC')->limit(1);
          $utoken = $utoken->pluck('token')->toArray();
          $text='Hi'.$user->name.'  Order has been placed successfully.';
          $mbdy="We have recevied payment from you and waiting for vendor to accept and confirm your order.";
          $fcm =new FirebaseCloudMessagingUtility($text);
          $fcm->sendData($utoken, ['mbody' => $mbdy,'order_id'=>$id,'image' => $opr[0]->proimg,'order_status'=>$order->order_statusn],2);
    }

    public function PlacedOrderNotification($id)
    {
          $order=Order::where('orders.id',$id)->join('order_statuses','order_statuses.id','=','orders.order_status_id')->select(['orders.*','order_statuses.name as order_statusn'])->first();
          $opr=OrderProduct::where('order_id',$id)->join('product_images', 'order_products.product_id','=', 'product_images.product_id')->limit(1)->select(['product_images.name as proimg'])->get();
          $user=User::where('id',$order->user_id)->first();
          $utoken=UserDeviceToken::where('user_id', $order->user_id)->orderBy('created_at','DESC');
          $utoken = $utoken->pluck('token')->toArray();
          $text='Hi '.$user->name.',  order has been placed successfully.';
          $mbdy="We have received payment from you and waiting for vendor to accept and confirm your order.";
          $fcm =new FirebaseCloudMessagingUtility($text);
          $fcm->sendData($utoken, ['mbody' => $mbdy,'order_id'=>$id,'image' => $opr[0]->proimg,'order_status'=>$order->order_statusn],2);
          $orderData = [
            'user_id' =>  $order->user_id,
            'vendor_id' => $user->vendor_id,
            'order_id'=>$id,
            'description'=>$mbdy,
            'title' => $text,
            'type' => 'order',
            'image' => $opr[0]->proimg,
        ];
        $orderData = Notification::create($orderData); 
    }
    
    public function AcceptOrderNotification($id)
    {
          $order=Order::where('orders.id',$id)->join('order_statuses','order_statuses.id','=','orders.order_status_id')->select(['orders.*','order_statuses.name as order_statusn'])->first();
          $opr=OrderProduct::where('order_id',$id)->join('product_images', 'order_products.product_id','=', 'product_images.product_id')->limit(1)->select(['product_images.name as proimg'])->get();
          $user=User::where('id',$order->user_id)->first();
          $utoken=UserDeviceToken::where('user_id', $order->user_id)->orderBy('created_at','DESC');
          $utoken = $utoken->pluck('token')->toArray();
          $text='Hi '.$user->name.',  order has been accepted.';
          $mbdy="We have an update on your order status, order has been confirmed by vendor.";
          $fcm =new FirebaseCloudMessagingUtility($text);
          $fcm->sendData($utoken, ['mbody' => $mbdy,'order_id'=>$id,'image' => $opr[0]->proimg,'order_status'=>$order->order_statusn],2);
          $orderData = [
            'user_id' =>  $order->user_id,
            'vendor_id' => $order->vendor_id,
            'order_id'=>$id,
            'description'=>$mbdy,
            'title' => $text,
            'type' => 'order',
            'image' => $opr[0]->proimg,
        ];
        $orderData = Notification::create($orderData); 
    }
    
    public function PackageOrderNotification($id)
    {
          $order=Order::where('orders.id',$id)->join('order_statuses','order_statuses.id','=','orders.order_status_id')->select(['orders.*','order_statuses.name as order_statusn'])->first();
          $opr=OrderProduct::where('order_id',$id)->join('product_images', 'order_products.product_id','=', 'product_images.product_id')->limit(1)->select(['product_images.name as proimg'])->get();
          $user=User::where('id',$order->user_id)->first();
          $utoken=UserDeviceToken::where('user_id', $order->user_id)->orderBy('created_at','DESC');
          $utoken = $utoken->pluck('token')->toArray();
          $text='Hi '.$user->name.',  order out for packaging.';
          $mbdy="We have an update on your order status, order is out for packaging by vendor.";
          $fcm =new FirebaseCloudMessagingUtility($text);
          $fcm->sendData($utoken, ['mbody' => $mbdy,'order_id'=>$id,'image' => $opr[0]->proimg,'order_status'=>$order->order_statusn],2);
          $orderData = [
            'user_id' =>  $order->user_id,
            'vendor_id' => $order->vendor_id,
            'order_id'=>$id,
            'description'=>$mbdy,
            'title' => $text,
            'type' => 'order',
            'image' => $opr[0]->proimg,
        ];
        $orderData = Notification::create($orderData); 
    }
    
    public function OutForOrderNotification($id)
    {
          $order=Order::where('orders.id',$id)->join('order_statuses','order_statuses.id','=','orders.order_status_id')->select(['orders.*','order_statuses.name as order_statusn'])->first();
          $opr=OrderProduct::where('order_id',$id)->join('product_images', 'order_products.product_id','=', 'product_images.product_id')->limit(1)->select(['product_images.name as proimg'])->get();
          $user=User::where('id',$order->user_id)->first();
          $utoken=UserDeviceToken::where('user_id', $order->user_id)->orderBy('created_at','DESC');
          $utoken = $utoken->pluck('token')->toArray();
          $text='Hi '.$user->name.',  order out for Delivery.';
          $mbdy="We have an update on your order status, order is out for Delivery. You can contact Delivery Boy for further assistance.";
          $fcm =new FirebaseCloudMessagingUtility($text);
          $fcm->sendData($utoken, ['mbody' => $mbdy,'order_id'=>$id,'image' => $opr[0]->proimg,'order_status'=>$order->order_statusn],2);
          $orderData = [
            'user_id' =>  $order->user_id,
            'vendor_id' => $order->vendor_id,
            'order_id'=>$id,
            'description'=>$mbdy,
            'title' => $text,
            'type' => 'order',
            'image' => $opr[0]->proimg,
        ];
        $orderData = Notification::create($orderData); 
    }
    
    public function DeliveredForOrderNotification($id)
    {
          $order=Order::where('orders.id',$id)->join('order_statuses','order_statuses.id','=','orders.order_status_id')->select(['orders.*','order_statuses.name as order_statusn'])->first();
          $opr=OrderProduct::where('order_id',$id)->join('product_images', 'order_products.product_id','=', 'product_images.product_id')->limit(1)->select(['product_images.name as proimg'])->get();
          $user=User::where('id',$order->user_id)->first();
          $utoken=UserDeviceToken::where('user_id', $order->user_id)->orderBy('created_at','DESC')->limit(10);
          $utoken = $utoken->pluck('token')->toArray();
        //   print_r($utoken);
          $text='Hi '.$user->name.',  order has been delivered.';
          $mbdy="We hope you are satisfied with our services. You can always rate purchased products and also delivery boy. We hope to see you soon shopping with us...";
          $fcm =new FirebaseCloudMessagingUtility($text);
          $fcm->sendData($utoken, ['mbody' => $mbdy,'order_id'=>$id,'image' => $opr[0]->proimg,'order_status'=>$order->order_statusn],2);
        //   $data = $request->only('user_id','vendor_id','title','type','product_id','category_id','sub_category_id','sub_sub_category_id','brand_id','image','is_send');
          $orderData = [
            'user_id' =>  $order->user_id,
            'vendor_id' => $order->vendor_id,
            'order_id'=>$id,
            'title' => $text,
            'description'=>$mbdy,
            'type' => 'order',
            'image' => $opr[0]->proimg,
        ];
        $orderData = Notification::create($orderData); 
    }
    public function GetDataForRazorpay()
    {  
        $amt=$_GET['amt'];
        $orderData = [
            'receipt'         => Str::random(20),
            'amount'          => $amt, // 2000 rupees in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];
        $ab=RazorpaySetting::where('is_active',1)->first();      
        $api = new Api($ab->rkey,  $ab->rsecret);
        // print_r($api);
        // exit;
        $dt=[];
        $razorpayOrder = $api->order->create($orderData);
        $dt['orderid'] = $razorpayOrder['id'];
        $dt['amount'] =$displayAmount = $amount = $orderData['amount'];
        $displayCurrency=$dt['currency']=$orderData['currency'];
        if ($displayCurrency !== 'INR')
        {
            $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
            $exchange = json_decode(file_get_contents($url), true);
            $dt['amount']=$displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
        }
        return $dt;
    }
    
    public function GetFetchDatadddd($id)
    {  
        $ab=RazorpaySetting::where('is_active',1)->first();      
        $api = new Api($ab->rkey,  $ab->rsecret);
        $cds=$api->payment->fetch($id);
        return $cds->method;
    }

    public function GenrateChecksum(Request $request)
    { 
        
        $ab=PaytmSetting::where('is_active',1)->first();    
        $paytmParams = array();    
        $paytmParams["MID"] = $ab->mid;
        $paytmParams["ORDERID"] = $request->orderid;
        $paytmChecksum = PaytmChecksum::generateSignature($paytmParams, 'YOUR_MERCHANT_KEY');
        echo sprintf("generateSignature Returns: %s\n", $paytmChecksum);
    }



    public function getgetneworderdata($id)
    {
        $order=Order::where('vendor_id',$id)->where('order_status_id',4)->where('makeabill',0)->where('is_close',0)->where('is_show',0)->get();
        foreach($order as $key)
        {   
            $category = Order::findOrFail($key->id);
            $data['is_show']=1;
            $data['is_close']=1;
            $category->update($data);
        }
        return ['new_order'=>count($order)];
    }

    public function Reportmake($id)
    {
       $order=Order::where('vendor_id',$id)->get();
       //print_r($order);
       foreach($order as $key)
       {
           $orderpro=OrderProduct::where('order_id',$key->id)->where('purchase_rate','0')->get();
           foreach($orderpro as $key2)
           {
             $stock=StockFetch::where('product_id',$key2->product_id)->where('size',$key2->size)->where('color',$key2->color)->where('sales_rate',$key2->price)->first();
            //print_r($stock);
            if(isset($stock))
            {
             $update=OrderProduct::findOrFail($key2->id);
             $update->purchase_rate=$stock->purchase_rate;
             $update->update();
            }
           }
       }
    }


    public function Reportmakenew($vendor_id)
    {  
        $order=Order::whereIn('id',[3411])->get();
       //   $order=Order::where('id',$vendor_id)->get();
        $pur_tax=0;
        $pur_val=0;
        $pur_gst=0;
        $taxable=0;
        $sales_sgst=0;
        $sales_cgst=0;
        $sales_igst=0;
        $sales_gst=0;
        $total_amount=0;
        $other=0;
        $finalamt=0;

        $fma=0;
        //gst
        $gst0=0;
        $cgst0=0;
        $sgst0=0;
        $tax0=0; 
        $tot0=0;

        $gst0_25=0;
        $cgst0_25=0;
        $sgst0_25=0;
        $tax0_25=0; 
        $gst0_25=0;
        
        $gst1=0;
        $cgst1=0;
        $sgst1=0;
        $tax1=0; 
        $tot1=0;

        $gst2=0;
        $cgst2=0;
        $sgst2=0;
        $tax2=0; 
        $tot2=0;

        $gst3=0;
        $cgst3=0;
        $sgst3=0;
        $tax3=0; 
        $tot3=0;
        
        $gst5=0;
        $cgst5=0;
        $sgst5=0;
        $tax5=0; 
        $tot5=0;

        $gst6=0;
        $cgst6=0;
        $sgst6=0;
        $tax6=0; 
        $tot6=0;

        $gst12=0;
        $cgst12=0;
        $sgst12=0;
        $tax12=0; 
        $tot12=0;

        $gst18=0;
        $cgst18=0;
        $sgst18=0;
        $tax18=0; 
        $tot18=0;

        $gst28=0;
        $cgst28=0;
        $sgst28=0;
        $tax28=0; 
        $tot28=0;

        //charges
        $pgdata=0;
        $pgactual=0;
        $pgdeduct=0;
        $remain=0;
        $pgdeldeduct=0;
        $tosettle=0;
        $vendrpro=0;
        $bankchar=0;
        $compchar=0;
        $pratet=0;
        $ptaxt=0;
        $pur_gstt =0;
        $ccc=0;
        foreach($order as $key)
        {  
           $other =$key->delivery_charges;
           $fma=$key->amount;
           if($fma!=0)
           {
           $opp=OrderProduct::where('order_id',$key->id)->get();
           foreach ($opp as $key2)
           {
               $gstp=$key2->gst;
               $ogstp=1+($gstp/100);
               $prate =$key2->purchase_rate;
               $ptax =($prate/$ogstp);
               $pur_gst=($ptax*($gstp/100));

               $pratet +=($key2->purchase_rate*$key2->qty);
               $ptaxt  +=($ptax*$key2->qty);
               $pur_gstt +=($pur_gst*$key2->qty);

               $taxable +=($key2->taxable_rate*$key2->qty);
               $sales_sgst +=($key2->sgst*$key2->qty);
               $sales_cgst +=($key2->cgst*$key2->qty);
               $sales_igst +=($key2->igst*$key2->qty);
               $sales_gst +=($key2->tgst*$key2->qty);

               if($gstp==0)
               {
                    $gst0 +=($key2->tgst*$key2->qty);
                    $cgst0 +=($key2->cgst*$key2->qty);
                    $sgst0 +=($key2->sgst*$key2->qty);
                    $tax0 +=($key2->taxable_rate*$key2->qty); 
                    $tot0 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp=='0.25')
               {
                    $gst0_25 +=($key2->tgst*$key2->qty);
                    $cgst0_25 +=($key2->cgst*$key2->qty);
                    $sgst0_25 +=($key2->sgst*$key2->qty);
                    $tax0_25 +=($key2->taxable_rate*$key2->qty); 
                    $tot0_25 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==1)
               {
                    $gst1 +=($key2->tgst*$key2->qty);
                    $cgst1 +=($key2->cgst*$key2->qty);
                    $sgst1 +=($key2->sgst*$key2->qty);
                    $tax1 +=($key2->taxable_rate*$key2->qty); 
                    $tot1 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==2)
               {
                    $gst2 +=($key2->tgst*$key2->qty);
                    $cgst2 +=($key2->cgst*$key2->qty);
                    $sgst2 +=($key2->sgst*$key2->qty);
                    $tax2 +=($key2->taxable_rate*$key2->qty); 
                    $tot2 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==3)
               {
                    $gst3 +=($key2->tgst*$key2->qty);
                    $cgst3 +=($key2->cgst*$key2->qty);
                    $sgst3 +=($key2->sgst*$key2->qty);
                    $tax3 +=($key2->taxable_rate*$key2->qty); 
                    $tot3 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==5)
               {
                    $gst5 +=($key2->tgst*$key2->qty);
                    $cgst5 +=($key2->cgst*$key2->qty);
                    $sgst5 +=($key2->sgst*$key2->qty);
                    $tax5 +=($key2->taxable_rate*$key2->qty); 
                    $tot5 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==6)
               {
                    $gst6 +=($key2->tgst*$key2->qty);
                    $cgst6 +=($key2->cgst*$key2->qty);
                    $sgst6+=($key2->sgst*$key2->qty);
                    $tax6 +=($key2->taxable_rate*$key2->qty); 
                    $tot6 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==12)
               {
                    $gst12 +=($key2->tgst*$key2->qty);
                    $cgst12 +=($key2->cgst*$key2->qty);
                    $sgst12 +=($key2->sgst*$key2->qty);
                    $tax12 +=($key2->taxable_rate*$key2->qty); 
                    $tot12 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==18)
               {
                    $gst18 +=($key2->tgst*$key2->qty);
                    $cgst18 +=($key2->cgst*$key2->qty);
                    $sgst18 +=($key2->sgst*$key2->qty);
                    $tax18 +=($key2->taxable_rate*$key2->qty); 
                    $tot18 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==28)
               {
                    $gst28 +=($key2->tgst*$key2->qty);
                    $cgst28 +=($key2->cgst*$key2->qty);
                    $sgst28 +=($key2->sgst*$key2->qty);
                    $tax28 +=($key2->taxable_rate*$key2->qty); 
                    $tot28 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
           }
           $purch_rate=round($pratet,2);
           $purch_tax=round($ptaxt,2);
           $purch_gst=round($pur_gstt,2);
           $total_amount=round(($taxable+$sales_gst),2);
           $finalamt=round(($taxable+$sales_gst+$other),2);
           $ordp=OrderPayment::where('order_id',$key->id)
           ->join('order_payment_methods','order_payment_methods.order_payment_id','=','order_payments.id')
           ->join('payment_methods','order_payment_methods.payment_method_id','=','payment_methods.id')
           ->join('order_payment_method_responses','order_payment_method_responses.order_payment_method_id','=','order_payment_methods.id')
           ->select('order_payment_method_responses.*','payment_methods.name as pname')->first();
            $pname=strtoupper($ordp->payment_mode);
            $pgdata=PgSetting::where('vendor_id',$key->vendor_id)->max('pg_charge');
            if($pname=='BANK')
            {
                $pgactual=0;
                $pg_val=0;
                $bank_val=0;
                $company_val=0;
                
            }
            elseif($pname=='UPI' || $pname=='CARD')
            {
                $pgdatax=PgSetting::where('vendor_id',$key->vendor_id)->where('pg_type',$pname)->first();
                $pgactual=$pgdatax->pg_charge;
                $pg_val=$pgdatax->pg_val;
                $bank_val=$pgdatax->bank_val;
                $company_val=$pgdatax->company_val;
                $pgdeduct=round(($fma-($fma/$pg_val)),2);
                $pgdeldeduct=round(($other/$pg_val),2);
                $bankchar=round(($fma-($fma/$bank_val)),2);
                $compchar=round(($fma-($fma/$company_val)),2);
            }
            //echo $pgdeldeduct;
            
           $remain=round(($fma-$pgdeduct),2);
           $tosettle=round(($remain-$pgdeldeduct),2);
           $vendrpro=$tosettle-$purch_rate;
        $orderData = [   
        'user_id'=>$key->user_id,
        'vendor_id'=>$key->vendor_id,
        'order_id'=>$key->id,
        'amount'=>$fma,
        'purchase_taxable'=>$purch_tax,
        'purchase_value'=>$purch_rate,
        'purchase_gst'=>$purch_gst,
        'taxable_value'=>round($taxable,2),
        'sales_sgst'=>round($sales_sgst,2),
        'sales_cgst'=>round($sales_cgst,2),
        'sales_igst'=>round($sales_igst,2),
        'sales_gst'=>round($sales_gst,2),
        'total_amount'=>$total_amount,
        'other_charges'=>$other,
        'final_amount'=>$finalamt,
        'taxable_value_0'=>round($tax0,2),
        'gst_0'=>round($gst0,2),
        'cgst_0'=>round($cgst0,2),
        'sgst_0'=>round($sgst0,2),
        'taxable_value_0.25'=>round($tax0_25,2),
        'gst_0.25'=>round($gst0_25,2),
        'cgst_0.25'=>round($cgst0_25,2),
        'sgst_0.25'=>round($sgst0_25,2),
        'taxable_value_1'=>round($tax1,2),
        'gst_1'=>round($gst1,2),
        'cgst_1'=>round($cgst1,2),
        'sgst_1'=>round($sgst1,2),
        'taxable_value_2'=>round($tax2,2),
        'gst_2'=>round($gst2,2),
        'cgst_2'=>round($cgst2,2),
        'sgst_2'=>round($sgst2,2),
        'taxable_value_3'=>round($tax3,2),
        'gst_3'=>round($gst3,2),
        'cgst_3'=>round($cgst3,2),
        'sgst_3'=>round($sgst3,2),
        'taxable_value_5'=>round($tax5,2),
        'gst_5'=>round($gst5,2),
        'cgst_5'=>round($cgst5,2),
        'sgst_5'=>round($sgst5,2),
        'taxable_value_6'=>round($tax6,2),
        'gst_6'=>round($gst6,2),
        'cgst_6'=>round($cgst6,2),
        'sgst_6'=>round($sgst6,2),
        'taxable_value_12'=>round($tax12,2),
        'gst_12'=>round($gst12,2),
        'cgst_12'=>round($cgst12,2),
        'sgst_12'=>round($sgst12,2),
        'taxable_value_18'=>round($tax18,2),
        'gst_18'=>round($gst18,2),
        'cgst_18'=>round($cgst18,2),
        'sgst_18'=>round($sgst18,2),
        'taxable_value_28'=>round($tax28,2),
        'gst_28'=>round($gst28,2),
        'cgst_28'=>round($cgst28,2),
        'sgst_28'=>round($sgst28,2),
        'pg_charges'=>$pgdata,
        'pg_mode'=>$pname,
        'pg_actual_charge'=>$pgactual,
        'pg_deduct'=>round($pgdeduct,2),
        'remain_amount'=>round($remain,2),
        'pg_delivery_charge'=>round($pgdeldeduct,2),
        'to_settle'=>round($tosettle,2),
        'vendor_profit'=>round($vendrpro,2),
        'bank'=>round($bankchar,2),
        'company'=>round($compchar,2),
        ];
        
         $order = SaleReport::create($orderData);
         $ccc=  $ccc+1;
        }
      }

        echo  $ccc;
    }



    public function changeinv($vendor_id)
    {  
        $order=Order::where('vendor_id',$vendor_id)->get();
        $i=1;
        foreach($order as $key)
        {   
            $category = Order::findOrFail($key->id);
            $data['invoice_no']=$vendor_id.'_'.$i;
            $category->update($data);
            $i++;
        }
    }

     public function makesalesreport($vendor_id)
    {  
        $order=Order::where('id',$vendor_id)->get();
        $pur_tax=0;
        $pur_val=0;
        $pur_gst=0;
        $taxable=0;
        $sales_sgst=0;
        $sales_cgst=0;
        $sales_igst=0;
        $sales_gst=0;
        $total_amount=0;
        $other=0;
        $finalamt=0;

        $fma=0;
        //gst
        $gst0=0;
        $cgst0=0;
        $sgst0=0;
        $tax0=0; 
        $tot0=0;

        $gst0_25=0;
        $cgst0_25=0;
        $sgst0_25=0;
        $tax0_25=0; 
        $gst0_25=0;
        
        $gst1=0;
        $cgst1=0;
        $sgst1=0;
        $tax1=0; 
        $tot1=0;

        $gst2=0;
        $cgst2=0;
        $sgst2=0;
        $tax2=0; 
        $tot2=0;

        $gst3=0;
        $cgst3=0;
        $sgst3=0;
        $tax3=0; 
        $tot3=0;
        
        $gst5=0;
        $cgst5=0;
        $sgst5=0;
        $tax5=0; 
        $tot5=0;

        $gst6=0;
        $cgst6=0;
        $sgst6=0;
        $tax6=0; 
        $tot6=0;

        $gst12=0;
        $cgst12=0;
        $sgst12=0;
        $tax12=0; 
        $tot12=0;

        $gst18=0;
        $cgst18=0;
        $sgst18=0;
        $tax18=0; 
        $tot18=0;

        $gst28=0;
        $cgst28=0;
        $sgst28=0;
        $tax28=0; 
        $tot28=0;

        //charges
        $pgdata=0;
        $pgactual=0;
        $pgdeduct=0;
        $remain=0;
        $pgdeldeduct=0;
        $tosettle=0;
        $vendrpro=0;
        $bankchar=0;
        $compchar=0;
        $pratet=0;
        $ptaxt=0;
        $pur_gstt =0;
        $ccc=0;
        foreach($order as $key)
        {  
           $other =$key->delivery_charges;
           $fma=$key->amount;
           if($fma!=0)
           {
           $opp=OrderProduct::where('order_id',$key->id)->get();
           foreach ($opp as $key2)
           {
               $gstp=$key2->gst;
               $ogstp=1+($gstp/100);
               $prate =$key2->purchase_rate;
               $ptax =($prate/$ogstp);
               $pur_gst=($ptax*($gstp/100));

               $pratet +=($key2->purchase_rate*$key2->qty);
               $ptaxt  +=($ptax*$key2->qty);
               $pur_gstt +=($pur_gst*$key2->qty);

               $taxable +=($key2->taxable_rate*$key2->qty);
               $sales_sgst +=($key2->sgst*$key2->qty);
               $sales_cgst +=($key2->cgst*$key2->qty);
               $sales_igst +=($key2->igst*$key2->qty);
               $sales_gst +=($key2->tgst*$key2->qty);

               if($gstp==0)
               {
                    $gst0 +=($key2->tgst*$key2->qty);
                    $cgst0 +=($key2->cgst*$key2->qty);
                    $sgst0 +=($key2->sgst*$key2->qty);
                    $tax0 +=($key2->taxable_rate*$key2->qty); 
                    $tot0 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp=='0.25')
               {
                    $gst0_25 +=($key2->tgst*$key2->qty);
                    $cgst0_25 +=($key2->cgst*$key2->qty);
                    $sgst0_25 +=($key2->sgst*$key2->qty);
                    $tax0_25 +=($key2->taxable_rate*$key2->qty); 
                    $tot0_25 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==1)
               {
                    $gst1 +=($key2->tgst*$key2->qty);
                    $cgst1 +=($key2->cgst*$key2->qty);
                    $sgst1 +=($key2->sgst*$key2->qty);
                    $tax1 +=($key2->taxable_rate*$key2->qty); 
                    $tot1 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==2)
               {
                    $gst2 +=($key2->tgst*$key2->qty);
                    $cgst2 +=($key2->cgst*$key2->qty);
                    $sgst2 +=($key2->sgst*$key2->qty);
                    $tax2 +=($key2->taxable_rate*$key2->qty); 
                    $tot2 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==3)
               {
                    $gst3 +=($key2->tgst*$key2->qty);
                    $cgst3 +=($key2->cgst*$key2->qty);
                    $sgst3 +=($key2->sgst*$key2->qty);
                    $tax3 +=($key2->taxable_rate*$key2->qty); 
                    $tot3 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==5)
               {
                    $gst5 +=($key2->tgst*$key2->qty);
                    $cgst5 +=($key2->cgst*$key2->qty);
                    $sgst5 +=($key2->sgst*$key2->qty);
                    $tax5 +=($key2->taxable_rate*$key2->qty); 
                    $tot5 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==6)
               {
                    $gst6 +=($key2->tgst*$key2->qty);
                    $cgst6 +=($key2->cgst*$key2->qty);
                    $sgst6+=($key2->sgst*$key2->qty);
                    $tax6 +=($key2->taxable_rate*$key2->qty); 
                    $tot6 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==12)
               {
                    $gst12 +=($key2->tgst*$key2->qty);
                    $cgst12 +=($key2->cgst*$key2->qty);
                    $sgst12 +=($key2->sgst*$key2->qty);
                    $tax12 +=($key2->taxable_rate*$key2->qty); 
                    $tot12 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==18)
               {
                    $gst18 +=($key2->tgst*$key2->qty);
                    $cgst18 +=($key2->cgst*$key2->qty);
                    $sgst18 +=($key2->sgst*$key2->qty);
                    $tax18 +=($key2->taxable_rate*$key2->qty); 
                    $tot18 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==28)
               {
                    $gst28 +=($key2->tgst*$key2->qty);
                    $cgst28 +=($key2->cgst*$key2->qty);
                    $sgst28 +=($key2->sgst*$key2->qty);
                    $tax28 +=($key2->taxable_rate*$key2->qty); 
                    $tot28 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
           }
           $purch_rate=round($pratet,2);
           $purch_tax=round($ptaxt,2);
           $purch_gst=round($pur_gstt,2);
           $total_amount=round(($taxable+$sales_gst),2);
           $finalamt=round(($taxable+$sales_gst+$other),2);
           $ordp=OrderPayment::where('order_id',$key->id)
           ->join('order_payment_methods','order_payment_methods.order_payment_id','=','order_payments.id')
           ->join('payment_methods','order_payment_methods.payment_method_id','=','payment_methods.id')
           ->join('order_payment_method_responses','order_payment_method_responses.order_payment_method_id','=','order_payment_methods.id')
           ->select('order_payment_method_responses.*','payment_methods.name as pname')->first();
            $pname=strtoupper($ordp->payment_mode);
            $pgdata=PgSetting::max('pg_charge');
            if($pname=='BANK')
            {
                $pgactual=0;
                $pg_val=0;
                $bank_val=0;
                $company_val=0;
                
            }
            elseif($pname=='UPI' || $pname=='CARD')
            {
                $pgdatax=PgSetting::where('pg_type',$pname)->first();
                $pgactual=$pgdatax->pg_charge;
                $pg_val=$pgdatax->pg_val;
                $bank_val=$pgdatax->bank_val;
                $company_val=$pgdatax->company_val;
                $pgdeduct=round(($fma-($fma/$pg_val)),2);
                $pgdeldeduct=round(($other/$pg_val),2);
                $bankchar=round(($fma-($fma/$bank_val)),2);
                $compchar=round(($fma-($fma/$company_val)),2);
            }
            //echo $pgdeldeduct;
            
           $remain=round(($fma-$pgdeduct),2);
           $tosettle=round(($remain-$pgdeldeduct),2);
           $vendrpro=$tosettle-$purch_rate;
        $orderData = [   
        'user_id'=>$key->user_id,
        'vendor_id'=>$key->vendor_id,
        'order_id'=>$key->id,
        'amount'=>$fma,
        'purchase_taxable'=>$purch_tax,
        'purchase_value'=>$purch_rate,
        'purchase_gst'=>$purch_gst,
        'taxable_value'=>round($taxable,2),
        'sales_sgst'=>round($sales_sgst,2),
        'sales_cgst'=>round($sales_cgst,2),
        'sales_igst'=>round($sales_igst,2),
        'sales_gst'=>round($sales_gst,2),
        'total_amount'=>$total_amount,
        'other_charges'=>$other,
        'final_amount'=>$finalamt,
        'taxable_value_0'=>round($tax0,2),
        'gst_0'=>round($gst0,2),
        'cgst_0'=>round($cgst0,2),
        'sgst_0'=>round($sgst0,2),
        'taxable_value_0.25'=>round($tax0_25,2),
        'gst_0.25'=>round($gst0_25,2),
        'cgst_0.25'=>round($cgst0_25,2),
        'sgst_0.25'=>round($sgst0_25,2),
        'taxable_value_1'=>round($tax1,2),
        'gst_1'=>round($gst1,2),
        'cgst_1'=>round($cgst1,2),
        'sgst_1'=>round($sgst1,2),
        'taxable_value_2'=>round($tax2,2),
        'gst_2'=>round($gst2,2),
        'cgst_2'=>round($cgst2,2),
        'sgst_2'=>round($sgst2,2),
        'taxable_value_3'=>round($tax3,2),
        'gst_3'=>round($gst3,2),
        'cgst_3'=>round($cgst3,2),
        'sgst_3'=>round($sgst3,2),
        'taxable_value_5'=>round($tax5,2),
        'gst_5'=>round($gst5,2),
        'cgst_5'=>round($cgst5,2),
        'sgst_5'=>round($sgst5,2),
        'taxable_value_6'=>round($tax6,2),
        'gst_6'=>round($gst6,2),
        'cgst_6'=>round($cgst6,2),
        'sgst_6'=>round($sgst6,2),
        'taxable_value_12'=>round($tax12,2),
        'gst_12'=>round($gst12,2),
        'cgst_12'=>round($cgst12,2),
        'sgst_12'=>round($sgst12,2),
        'taxable_value_18'=>round($tax18,2),
        'gst_18'=>round($gst18,2),
        'cgst_18'=>round($cgst18,2),
        'sgst_18'=>round($sgst18,2),
        'taxable_value_28'=>round($tax28,2),
        'gst_28'=>round($gst28,2),
        'cgst_28'=>round($cgst28,2),
        'sgst_28'=>round($sgst28,2),
        'pg_charges'=>$pgdata,
        'pg_mode'=>$pname,
        'pg_actual_charge'=>$pgactual,
        'pg_deduct'=>round($pgdeduct,2),
        'remain_amount'=>round($remain,2),
        'pg_delivery_charge'=>round($pgdeldeduct,2),
        'to_settle'=>round($tosettle,2),
        'vendor_profit'=>round($vendrpro,2),
        'bank'=>round($bankchar,2),
        'company'=>round($compchar,2),
        ];
        
        
         $order = SaleReport::create($orderData);
         $rtgdt=round(($sales_gst)/2,2);
         DB::table('orders')->where('id',$vendor_id)->update(['total_rtgst'=>$rtgdt]);
        //  //$vendorx=VendorWallet::where('vendor_id',$key->vendor_id)->first();
        //  $datac['total_order']=$vendorx->total_order + 1;
        //  $datac['subtotal']=$vendorx->subtotal + $total_amount;
        //  $datac['delivery_charges']=$vendorx->delivery_charges + $other;
        //  $datac['total_amount']=$vendorx->total_amount + $finalamt;
        //  $datac['pg_charges']=$vendorx->pg_charges + round($pgdeduct,2);
        //  $datac['to_delivery']=$vendorx->to_delivery + round($pgdeldeduct,2);
        //  $datac['to_settle']=$vendorx->to_settle + round($tosettle,2);
        //  $datac['pending']=$vendorx->pending + round($tosettle,2);
        //  $vendorx->update($datac);
        }
      }

    }

    
    public function Checktmpdata($vendor_id)
    {
        $order = SaleReport::where('vendor_id',11)->get();
        $orderc=0;
        $subtotal=0;
        $delivery=0;
        $total=0;
        $pg=0;
        $to_delivery=0;
        $to_settle=0;
        $pending=0;
        foreach($order as $key)
        {
            $ordercc=$orderc+1;
            $subtotal +=$key->total_amount;
            $delivery +=$key->other_charges;
            $total +=$key->final_amount;
            $pg +=$key->pg_deduct;
            $to_delivery +=$key->pg_delivery_charge;
            $to_settle +=$key->to_settle;
            $orderc++;
        }

        $vendor=new VendorWallet();
        $vendor->vendor_id=11;
        $vendor->total_order=$ordercc;
        $vendor->subtotal=$subtotal;
        $vendor->delivery_charges=$delivery;
        $vendor->total_amount=$total;
        $vendor->pg_charges=$pg;
        $vendor->to_delivery=$to_delivery;
        $vendor->to_settle=$to_settle;
        $vendor->pending=$to_settle;
        $vendor->save();
    }

    public function Checktdeldata($vendor_id)
    {   
        $vendor=DeliveryPerson::get();
        echo '<pre>';
       // print_r($vendor);exit;
        foreach($vendor as $key)
        {
        $order = SaleReport::join('orders','orders.id','=','sale_reports.order_id')->where('orders.order_status_id',9)->where('orders.delivery_person_id',$key->id)->select(['sale_reports.*','orders.*'])->get();
        $orderc=0;
        $delivery=0;
        $tosettle=0;
        $toc=0;
        foreach($order as $keys)
        {   $ordercc=$orderc+1;
            //print_r($keys->delivery_charges);
            if($keys->delivery_charges!='0')
            {
                $tc=10;
            $delivery +=$keys->pg_delivery_charge;
            $tsd=$keys->pg_delivery_charge-$tc;
            $toc+=$tc;
            $tosettle +=$tsd;
            }
            $orderc++;
        }

        $vendor=new DeliveryPersonWallet();
        $vendor->delivery_person_id=$key->id;
        $vendor->vendor_id=$key->vendor_id;
        $vendor->total_order=$ordercc;
        $vendor->delivery_charges=$delivery;
        $vendor->to_charge=$toc;
        $vendor->to_settle= $tosettle;
        $vendor->pending= $tosettle;
        $vendor->save();
     }    
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
            $pad = ord($text(strlen($text) - 1));
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
        $paytmss=PaytmSetting::where('is_active',1)->first();
        define('PAYTM_ENVIRONMENT', $paytmss->env); // PROD
        define('PAYTM_MERCHANT_KEY',$paytmss->mkey); //Change this constant's value with Merchant key downloaded from portal
        define('PAYTM_MERCHANT_MID', $paytmss->mid); //Change this constant's value with MID (Merchant ID) received from Paytm
        define('PAYTM_MERCHANT_WEBSITE', $paytmss->website); //Change this constant's value with Website name received from Paytm
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

    public function get_checksum(Request $request)
    {    
         $paytmss=PaytmSetting::where('is_active',1)->first();
         $user_id=$request->user_id;
         $total=$request->total_amount;
         $callback_url = config('app.PAYTM.CALLBACK_URL');
         $this->getAllEncdecFunc(); // loading all paytm functions
         $this->getConfigPaytmSettings(); // loading paytm config settings
         $checkSum = "";
         $paramList = array();
         $order=Order::orderBy('id','desc')->first();
         $order2=rand(10000,999999999999).$order->invoice_no;
         $user=User::where('id',$user_id)->first();
         $INDUSTRY_TYPE_ID = $paytmss->type;
         $CHANNEL_ID = $paytmss->channel_id; //WEB OR WAP
        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = PAYTM_MERCHANT_MID;
        $paramList["ORDER_ID"] = $order2;
        $paramList["CUST_ID"] = $user_id;
        $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = $CHANNEL_ID;
        $paramList["TXN_AMOUNT"] =$total ;
        $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;

        $paramList["CALLBACK_URL"] = $callback_url.$order2;
        $paramList["MSISDN"] = $user->mobile != '' ? $user->mobile : '9999999999'; //Mobile number of customer
        $paramList["EMAIL"] = $user->email != '' ? $user->email : ''; //Email ID of customer
        //["VERIFIED_BY"] = "MOBILE"; //
        //$paramList["IS_USER_VERIFIED"] = "YES"; //
        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);
        $paramList['CHECKSUM'] = $checkSum;
        $data['params']=$paramList;
        $data['order_id']=$order2;
        return $data;
    }
    
    
}
 