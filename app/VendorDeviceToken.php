<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorDeviceToken extends Model
{

    protected $fillable =[
        'token',
        'vendor_id'
    ];
}
