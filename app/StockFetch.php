<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockFetch extends Model
{ use SoftDeletes;
    protected $fillable = 
    [
        'purchase_rate','vendor_id','product_id','sales_rate','quantity','size','color','variable','created_at','updated_at','is_active',
    ];

}
