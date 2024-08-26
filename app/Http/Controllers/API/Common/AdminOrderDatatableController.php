<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\AdminOrder;
class AdminOrderDatatableController extends Controller
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

        $totalData = AdminOrder::count();

        $totalFiltered = $totalData;

        $limit = (int)$req['length'];
        $start = (int)$req['start'];
        $order = $columns[(int)$req['order'][0]['column']];
        $dir = $req['order'][0]['dir'];
        $search = $req['search']['value'];

        // dd($limit, $start, $order, $dir, $search);
        if(empty($search))
        {
            $orders = AdminOrder::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {

            $orders =  AdminOrder::where('id','LIKE',"%{$search}%")
                            ->orWhere('user_id', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = AdminOrder::where('id','LIKE',"%{$search}%")
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

        $query = AdminOrder::with(['address','status','payment', 'user','delivery_status'])->where('payment_status', 1)->orderBy('id','desc');
        $type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : 'all';
        switch ($type) {
            case 'active':
                $query->whereRaw("admin_orders.order_status_id <> 9");
                break;

            case 'pending':
                $query->whereRaw('admin_orders.order_status_id > 3');
                break;

            case 'cancelled':
                $query->where("admin_orders.order_status_id",3);
                break;

            case 'delivered':
                $query->where("admin_orders.order_status_id", 9);
                break;

            default:
                break;
        }
        return $query->get();
    }

    public function show($id)
    {
        $order = AdminOrder::with(['address','status','payment', 'user','delivery_status'])->where('id',$id)->first();
        //dd($order);exit;
        $products = DB::table('admin_order_products')->where('order_id',$id)
        ->join('admin_products', 'admin_products.id', '=', 'admin_order_products.product_id')->get();
        $order->products = $products;
        $order->payment_method = DB::table('order_payment_methods')->leftJoin('payment_methods', 'payment_method_id', '=', 'payment_methods.id')->where([['order_payment_id', $order->payment_status],['type','Admn']])->first();
        $order->deliveryStatus = DB::table('delivery_statuses')->get();
        $order->orderStatus = DB::table('order_statuses')->get();
        return $order;
    }

    public function changeOrderStatus(Request $request, $orderId){
        $this->validate($request , [
            'order_status_id'=> 'required|numeric',
            'delivery_status_id'=> 'sometimes'
        ]);
        $order = AdminOrder::findOrFail($orderId);
        $data['order_status_id'] = $request->order_status_id;
        $data['delivery_status_id'] = $request->delivery_status_id;
        $data['status_updated'] = date('Y-m-d H:i:s');
        return [
            'success' =>   $order->update($data),
            'msg' => 'Updated'
        ];
    }

    


}
