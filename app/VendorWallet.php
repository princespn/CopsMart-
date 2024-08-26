<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorWallet extends Model
{
    protected $fillable =[
        'amount',
        'description',
        'is_collectable',
        'is_adjustment',
        'vendor_id',
        'new_balance',
    ];


    protected $dates = [
        'deleted_at'
    ];
}
