<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VendorStatus;
class VendorStatusController extends Controller
{
    public function index(){
        return VendorStatus::get();
        // return [
        //     'success' => true
        // ];
    }
}
