<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPayment extends Model
{
    protected $fillable=[
        'order_id',
        'amount',
        'status',
    ];

    protected $dates = ['deleted_at'];
}
