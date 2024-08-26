<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\DeliveryStatus;
class DeliveryStatusController extends Controller
{
    public function index(){
        return DeliveryStatus::get();
    }
}
