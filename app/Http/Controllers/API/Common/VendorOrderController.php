<?php
namespace App\Http\Controllers\API\Common;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderProduct;
use App\OrderAddress;
use App\VendorStatus;
use App\OrderStatus;
use App\DeliveryStatus;
use App\OrderPayment;
use App\User;
use App\DeliveryPersonDeviceToken;
use App\UserDeviceToken;
use DB;
use App\Utilities\FirebaseCloudMessagingUtility;
class VendorOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($vendorId)
    {

        $query = DB::table('orders')->where('vendor_id',$vendorId)
        ->join('users', 'user_id','=', 'users.id')
        ->where('payment_status', 1)
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*','users.name', 'users.email', 'users.mobile');


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
    
    ///////////
    public function indextest($vendorId)
    {

        $query = DB::table('orders')->where('vendor_id',$vendorId)
        ->join('users', 'user_id','=', 'users.id')
       
        ->where('payment_status', 1)
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*','users.name', 'users.email', 'users.mobile');


        $type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : 'all';
        switch ($type) {
            case 'active':
              $query->whereRaw("vendor_status_id IS NOT NULL AND vendor_status_id='1' ");
            break;
            
            case 'ready':
                $query->whereRaw("vendor_status_id IS NOT NULL AND (vendor_status_id='3' OR vendor_status_id='4') AND (delivery_status_id!='3' OR  delivery_person_id is NULL)");
            break;
            
            case 'picked':
                $query->whereRaw("vendor_status_id IS NOT NULL AND delivery_status_id='3' ");
            break;
            
            case 'pending':
                $query->where("vendor_status_id", null);
                 $query->whereRaw("order_status_id!='3' ");
            break;

            default:

                break;
        }
        
         $data=$query->get();
       //exit;
        if(count($data)==0)
        {
           return []; 
        }
        else
        { 
          if($_GET['type']=='pending')
          {   
            //   $data->vendor_status='';
               ;
              foreach ($data as $key)
              {
                  $key->vendor_status='';
                  
                  $key->total_time=$key->vendor_slot+$key->delivery_slot;
                  $products = DB::table('order_products')->where('order_id',$key->id)
            ->join('products', 'products.id', '=', 'order_products.product_id')->join('vendor_products','vendor_products.id','=','order_products.vendor_product_id')->join('packages','packages.id','=','vendor_products.package_id')->select('order_products.*','products.*','vendor_products.package_id','packages.name as package_name')->get();
            $key->products=$products;  //product
             if($key->delivery_person_id==null)
             {
                 $key->Delivery=[];
             }
             else
             {
                 $deld= DB::table('delivery_people')->where('id',$key->delivery_person_id)->get();
                 $key->Delivery=$deld;
             }
               $dataa[]=$key;
              }
              
              return $dataa;
            //echo count($data);exit;
          }
          else
          {
          foreach($data as $key)
          {   
            $vendorstatus=DB::table('vendor_statuses')->where('id',$key->vendor_status_id)->select('code')->get();
            $key->vendor_status=$vendorstatus[0]->code;
            $key->total_time=$key->vendor_slot+$key->delivery_slot;
            $key->status_updated=date('d-m-Y h:iA',strtotime($key->status_updated));
            $key->delivery_accept_time=date('d-m-Y H:iA',strtotime($key->delivery_accept_time));
            $products = DB::table('order_products')->where('order_id',$key->id)
            ->join('products', 'products.id', '=', 'order_products.product_id')->join('vendor_products','vendor_products.id','=','order_products.vendor_product_id')->join('packages','packages.id','=','vendor_products.package_id')->select('order_products.*','products.*','vendor_products.package_id','packages.name as package_name')->get();
            $key->products=$products;  //product
            $deld= DB::table('delivery_people')->where('id',$key->delivery_person_id)->get();
            $key->Delivery=$deld;  //product
                //$dataa[]=$key;
                $dataa[]=$key;
            
        }
          return $dataa;
          }
          
        }
    }
    

    //time
    public function time($vendorId,$id)
    {
        $query = DB::table('orders')->where('vendor_id',$vendorId)
        ->join('users', 'user_id','=', 'users.id')
        ->where('payment_status', 1)
        ->where('vendor_status_id', 1)
        ->where('orders.id',$id)
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*','users.name', 'users.email', 'users.mobile');

        $data=$query->get();
        if(count($data)>0)
        {
            
       
        // print_r($data);
        // exit;
            $time=$data[0]->vendor_slab_time;
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($vendorId, $id)
    {
        $order = DB::table('orders')->where('vendor_id',$vendorId)
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
        $order->amount = $order->amount-$order->delivery_charges;

        $status = VendorStatus::where('id', $order->vendor_status_id);
        $address = OrderAddress::where('order_id',$id)->first();
        $products = DB::table('order_products')->where('order_id',$id)
        ->join('products', 'products.id', '=', 'order_products.product_id')->join('vendor_products','vendor_products.id','=','order_products.vendor_product_id')->join('packages','packages.id','=','vendor_products.package_id')->select('order_products.*','products.*','vendor_products.package_id','packages.name as package_name')->get();

        return compact(
            'order',
            'address',
            'products',
            'status'
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $vendorId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $vendorId , $id)
    {
        $order = Order::findOrFail($id);
        if($order->vendor_id != $vendorId){
            return [
                'success' => false,
                'message' => 'Order does not belong to vendor'
            ];
        }
        $this->validate($request, [
            'vendor_status_id' => 'sometimes|numeric',
            'vendor_slab' => 'sometimes|numeric'
        ]);
         
        if($request->vendor_status_id==1 && $order->vendor_status_id!=null)
        {
           return [
            'success' =>  false,
            'message' =>  'Already Accepted',
           ]; 
        }
        elseif($request->vendor_status_id==2 && $order->vendor_status_id!=null)
        {
           return [
            'success' =>  false,
            'message' =>  'Already Accepted',
           ]; 
        }
        else
        {
        $vendorStatus = VendorStatus::findOrFail($request->vendor_status_id);
        $deliveryStatusId = null;
        //getiing diffrent status based on vendor status
        switch ($vendorStatus->code) {
            case 'accepted':
                $orderStatus = OrderStatus::where('code', 'confirmed')->first();
                $orderStatusId= $orderStatus->id;
                break;

            case 'rejected':
                $orderStatus = OrderStatus::where('code', 'cancelled')->first();
                $orderStatusId= $orderStatus->id;
                break;

            case 'ready':
                $orderStatus = OrderStatus::where('code', 'ready')->first();
                $orderStatusId= $orderStatus->id;
                $deliveryStatus =  DeliveryStatus::where('code', 'packed')->first();
                $deliveryStatusId= $deliveryStatus->id;

                break;

            case 'picked':
                $orderStatus = OrderStatus::where('code', 'out_for_delivery')->first();
                $orderStatusId= $orderStatus->id;
                break;

            default:
                $orderStatusId = null;
                break;
        }
        if($request->vendor_status_id==1)
        {
        $queryData['vendor_status_id'] = $request->vendor_status_id;
        $selectedTime = date('Y-m-d H:i:s');
        $endTime = strtotime("+".$request->vendor_slab." minutes", strtotime($selectedTime));
        $queryData['vendor_slab_time'] = date('Y-m-d H:i:s', $endTime);
        $queryData['vendeo_accept_time'] =$selectedTime;
        $queryData['vendor_slot'] =$request->vendor_slab;
        }
        elseif($request->vendor_status_id==3)
        {
            $queryData['vendor_status_id'] = $request->vendor_status_id;
            $selectedTime = date('Y-m-d H:i:s');
            $queryData['vendor_ready_time'] =$selectedTime;
        }
        else
        {
            $queryData['vendor_status_id'] = $request->vendor_status_id;
        }
        if($orderStatusId){
            $queryData['order_status_id'] = $orderStatusId;
            $userToken = UserDeviceToken::where('user_id', $order->user_id)->pluck('token')->toArray();
            // if($userToken){
            //     $fcm = new FirebaseCloudMessagingUtility('#'.$order->id.' '.$orderStatus->name, $orderStatus->description);
            //     $fcm->send($userToken,3);
            // }
        }
        if($deliveryStatusId && !empty($order->delivery_person_id)){
            $queryData['delivery_status_id'] = $deliveryStatusId;
            $deliveryToken = DeliveryPersonDeviceToken::where('delivery_person_id', $order->delivery_person_id)->pluck('token')->toArray();
            // if($deliveryToken){
            //     $fcm = new FirebaseCloudMessagingUtility('#'.$order->id.' '.$deliveryStatus->description, "Scheduled delivery : ".$order->scheduled_delivery_date);
            //     $fcm->send($deliveryToken,1);
            // }
        }
        $queryData['status_updated'] = date('Y-m-d H:i:s');
       //print_r($queryData);exit;
      
        $updated = $order->update($queryData);
        return [
            'success' => $updated ? true : false ,
            'message' => $updated ? 'Updated' : 'Something went wrong' ,

        ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
