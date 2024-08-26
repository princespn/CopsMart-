<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryPerson extends Model
{
    protected $fillable =[
        'name',
        'password',
        'vendor_id',
        'admin_id',
        'mobile',
        'email',
        'last_activity',
        'is_active',
        'driving_license_no',
        'aadhar_no',
        'service_area_id',
        'lat', 
        'longi', 
        'commodity_type_id', 
        'available', 
        'working_type',
        'slab',
        'acount_no',
        'account_name',
        'bank',
        'ifsc',
        'cash_limit',
        'address',
        'pincode',
        'dob',
        'district',
        'state',
        'blood_group',
        'accverify',
        'ifscverify',
        'date_of_joining',
        'identification_mark',
        'delivery_boy_img',
        'adhar_front_img',
        'adhar_back_img',
        'pan_img',
        'pan_no',
        'client_id'
        ];

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    function commodity(){
        return $this->hasMany('App\DeliveryPersonCommodity');
    }

    public function orders(){
        return $this->hasMany('App\Order');
    }
}
