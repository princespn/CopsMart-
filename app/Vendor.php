<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{    
    use SoftDeletes;
    protected $fillable= [
    'admin_id',
    'name',
    'business_name',
    'gstin',
    'address', 
    'pincode',
    'district',
    'state',
    'latitude', 
    'longitude', 
    'contact_person_name', 
    'contact_person_mobile', 
    'email',
    'contact_no',
    'app_name',
    'shop_image',
    'work_order',
    'gstin_certificate',
    'shop_act',
    'app_icon',
    'bank_document',
    'bank_account_name',
    'bank_ifsc',
    'ifscverify',
    'ifscverifybank',
    'account_v_name',
    'bank_account_number',
    'account_verification',
    'delivery_for_base_city',
    'delivery_service_for_district',
    'online',
    'offline',
    'sales_percent',
    'pg_charges',
    'pickup_charges',
    'is_active',
    'open_time',
    'close_time',
    'created_at',
    'updated_at',
    'deleted_at',
    'closed',
    'open_time',
    'close_time',
];

    public function vendorProducts(){
        return $this->hasMany('App\VendorProduct');
    }
    
    public function vendorCategory(){
        return $this->belongsTo('App\Category','category_id','id');
    }
    
}
