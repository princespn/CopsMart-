<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPaymentMethodResponse extends Model
{
    protected $fillable =[
        'order_payment_method_id',
        'payment_method_id',
        'payment_status_id',
        'amount',
        'transaction_uid',
        'bank_name',
        'bank_txn_id',
        'response_object',
        'currency',
        'gateway_name',
        'response_msg',
        'payment_mode',
        'status',
        'status_code',
        'ref_transaction_id'
    ];
    
    protected $dates = ['deleted_at'];
}
