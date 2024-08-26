<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RemoveStock extends Model
{ use SoftDeletes;
    protected $fillable = 
    [
        'reason','purchase_rate','price','pg_charges','gst','tgst','cgst','sgst','igst','taxable_rate','stock','qty','vendor_id','stock_id','product_id','price','quantity','size','color','created_at','updated_at','deleted_at'
    ];

}
