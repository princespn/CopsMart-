<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{ use SoftDeletes;
    protected $fillable = 
    [
        'before_remove_qty','after_remove_quantity','remove_qty','purchase_rate','gst','tgst','cgst','sgst','igst','taxable_rate','old_quantity','vendor_id','tmp_purchase_id','product_id','price','quantity','size','color','created_at','updated_at',
    ];

}
