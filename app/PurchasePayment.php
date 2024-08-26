<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasePayment extends Model
{
    protected $fillable=[ 
                           'vendor_id', 
                           'p_vendor_id', 
                           'invoice_no',
                           'invoice_date',
                           'purchase_id',
                           'payment_date',
                           'status', 
                           'paid_amount',
                           'remaining_amount',
                           'tansaction_id',
                           'payment_mode',
                           'is_active',
                           'created_at',
                           'updated_at',
                           'deleted_at',
                           'total_amount',
                           'other_charges',
                           'pending'
                        ];

    
}
