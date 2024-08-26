<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorCharge extends Model
{
    protected $fillable =[
        'id',
        'vendor_id',
        'upi',
        'debit',
        'total',
        'crerated_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];
}
