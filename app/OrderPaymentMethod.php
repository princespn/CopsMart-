<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPaymentMethod extends Model
{
    protected $fillable =[
        'order_payment_id',
        'payment_method_id',
        'payment_status_id',
        'amount',
        'transaction_uid',
        'status',
    ];
    
    protected $dates = ['deleted_at'];
}
