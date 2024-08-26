<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Order;
use App\User;
use App\Product;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
class DashboardController extends Controller
{
    function getDashboardCounts($a)
    {   
        $orderAmount =  DB::table('orders')->where('vendor_id',$a)->whereRaw('order_status_id > 3')->selectRaw('SUM(amount) as total')->first()->total;
        $newOrders = DB::table('orders')->where('vendor_id',$a)->whereRaw('order_status_id = 3')->selectRaw('Count(id) as total')->first()->total;
        $users = DB::table('users')->where('vendor_id',$a)->selectRaw('Count(id) as total')->first()->total;
        $products = DB::table('products')->where('vendor_id',$a)->selectRaw('Count(id) as total')->first()->total;

        return [
            'sales' => $orderAmount,
            'new_orders' => $newOrders,
            'customers' => --$users,
            'products' => $products,
        ];
    }
}
