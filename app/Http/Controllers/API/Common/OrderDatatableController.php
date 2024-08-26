<?php

namespace App\Http\Controllers\API\Common;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Order;
use App\Vendor;
use App\DeliveryPerson;
use App\Utilities\FirebaseCloudMessagingUtility;
class OrderDatatableController extends Controller
{
    public function allOrders()
    {
        $req= $_GET;
        // dd($req);
        $columns = array(
                            0 =>'id',
                            1 =>'user_id',
                            2=> 'amount',
                            3=> 'created_at',
                            4=> 'id',
                        );

        $totalData = Order::count();

        $totalFiltered = $totalData;

        $limit = (int)$req['length'];
        $start = (int)$req['start'];
        $order = $columns[(int)$req['order'][0]['column']];
        $dir = $req['order'][0]['dir'];
        $search = $req['search']['value'];

        // dd($limit, $start, $order, $dir, $search);
        if(empty($search))
        {
            $orders = Order::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {

            $orders =  Order::where('id','LIKE',"%{$search}%")
                            ->orWhere('user_id', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Order::where('id','LIKE',"%{$search}%")
                             ->orWhere('user_id', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($orders))
        {
            foreach ($orders as $order)
            {
            //     $show =  route('orders.show',$order->id);
            //     $edit =  route('orders.edit',$order->id);

                $nestedData['id'] = $order->id;
                $nestedData['user_id'] = $order->user_id;
                $nestedData['amount'] = $order->amount;
                $nestedData['created_at'] = date('j M Y h:i a',strtotime($order->created_at));
                // $nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                //                           &emsp;<a href='{$edit}' title='EDIT' ><span class='glyphicon glyphicon-edit'></span></a>";
                $data[] = $nestedData;

            }
        }
        // dd($data);
        $json_data = array(
                    "draw"            => intval($req['draw']),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                    );

        echo json_encode($json_data);

    }

    public function nonPaginated()
    {
        $id=$_GET['ad'];
        // if($id=='1')
        $vendors=Vendor::where('admin_id',$id)->select('id')->get();
        $key ='';
        foreach($vendors as $k){$key .="'$k->id'".',';}
        $query = Order::with(['user','status','vendorStatus','deliveryStatus'])->where('payment_status', 1)->orderBy('id','desc');
        $type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : 'all';
        switch ($type) {
            case 'active':
                $query->whereRaw("vendor_status_id IS NOT NULL AND vendor_status_id<>'2' AND orders.order_status_id <> 9");
                break;
            case 'pending':
                $query->where("vendor_status_id", null)->whereRaw('orders.order_status_id >= 3');
                break;
            case 'cancelled':
                $query->where("vendor_status_id", 2);
                break;
            case 'delivered':
                $query->where("order_status_id", 9);
                break;
            default:
                break;
        }
        if($id==1)
        {  
            return $query->get();
        }
        else
        {   
            $key= rtrim($key,",");
            $query->whereRaw("vendor_id IN ($key)");
            return $query->get();
        }
        
    }

    public function show($id)
    {   
        //echo $id;//exit;
        $order = Order::with(['address','status', 'vendorStatus', 'deliveryStatus', 'payment', 'user', 'vendor', 'deliveryPerson'])->where('id',$id)->first();
        $products = DB::table('order_products')->where('order_id',$id)
        ->join('products', 'products.id', '=', 'order_products.product_id')->get();
        $order->products = $products;
        $order->payment_method = DB::table('order_payment_methods')->leftJoin('payment_methods', 'payment_method_id', '=', 'payment_methods.id')->where('order_payment_id', $order->payment->id)->first();
        return $order;
    }

    public function assignDeliveryPerson(Request $request, $orderId){
        $this->validate($request , [
            'delivery_person_id'=> 'required|numeric'
        ]);
        $delivery = DeliveryPerson::findOrFail($request->delivery_person_id);
        $order = Order::findOrFail($orderId);
        if($order->vendor_status_id=='3' || $order->vendor_status_id=='4')
        {
            $data['delivery_status_id'] = 6;
        }
        else
        {
            $data['delivery_status_id'] = 1;
        }
        $data['delivery_person_id'] = $request->delivery_person_id;
        $data['tmp_delivery_id'] = $request->delivery_person_id;
        $data['delivery_slot'] = $delivery->slab;
        $selectedTime = date('Y-m-d H:i:s');
        $endTime = strtotime("+".$delivery->slab." minutes", strtotime($selectedTime));
        $data['delivery_slab_time'] = date('Y-m-d H:i:s', $endTime);
        $data['delivery_accept_time'] = date('Y-m-d H:i:s');
        //$orderdone=;
        // if($orderdone)
        // {
            // $deliveryToken = DeliveryPersonDeviceToken::where('delivery_person_id', $request->delivery_person_id)->pluck('token')->toArray();
            // if($deliveryToken){
            //     $fcm = new FirebaseCloudMessagingUtility('#'.$orderId, "Order Assign By Admin");
            //     $fcm->send($deliveryToken,1);
            // }
      
        return [
            'success' =>   $order->update($data),
            'msg' => 'Updated'
        ];
        //}
    }
    
     public function DeliveryRemove(Request $request, $orderId){
        //echo $orderId;
        $this->validate($request , [
            'order_id'=> 'required|numeric'
        ]);
        // $delivery = DeliveryPerson::findOrFail($request->delivery_person_id);
         $order = Order::findOrFail($orderId);
         $data['delivery_person_id'] = null;
         $data['tmp_delivery_id'] = null;
         $data['delivery_status_id'] = null;
         $data['delivery_slot'] = null;
        // $selectedTime = date('Y-m-d H:i:s');
        // $endTime = strtotime("+".$delivery->slab." minutes", strtotime($selectedTime));
         $data['delivery_slab_time'] = null;
         $data['delivery_accept_time'] = null;
        return [
            'success' =>   $order->update($data),
            'msg' => 'Updated'
        ];
    }


}
