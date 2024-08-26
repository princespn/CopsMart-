<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    protected $fillable=[ 
                           'vendor_id', 
                           'p_vendor_id', 
                           'invoice_no',
                           'invoice_date',
                           'tmp_purchase_id',
                           'other_charges',
                           'final_amount', 
                           'taxable_rate',
                           'total_gst',
                           'paid_amount',
                           'pending_amount',
                           'is_active',
                           'created_at',
                           'updated_at',
                           'deleted_at',
                           'pending',
                        ];

    
}
