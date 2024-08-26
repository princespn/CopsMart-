<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GlobalSetting;
class GlobalSettingController extends Controller
{
    public function vendorMinVersion(){
        return[ 'min_version' => GlobalSetting::pluck('vendor_app_min_version')->first() ];
    }
    public function deliveryMinVersion(){
        return [ 'min_version' =>  GlobalSetting::pluck('delivery_app_min_version')->first() ];
    }
    public function marketingMinVersion(){
        return [ 'min_version' => GlobalSetting::pluck('marketing_app_min_version')->first() ];
    }
}
