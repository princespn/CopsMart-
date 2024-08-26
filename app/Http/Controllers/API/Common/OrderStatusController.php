<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrderStatus;
class OrderStatusController extends Controller
{
    public function index(){
        return OrderStatus::get();
    }
}
