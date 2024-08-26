<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseVendor extends Model
{   use SoftDeletes;
    protected $fillable= 
    [
        'vendor_id',
        'name',
        'mobile_no',
        'contact_person',
        'emp_post', 
        'address',
        'pincode',
        'district', 
        'state',
        'gst',
        'bankname',
        'account_name',
        'account_no',
        'ifsc',
        'is_active',
        'created_at',
        'deleted_at',
        'updated_at',
        'is_block',
        'ifscverifybank',
        'ifscverify',
    ];

    
    
}
