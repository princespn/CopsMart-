<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminOrderProduct extends Model
{
    protected $fillable=[
        'order_id',
        'product_id',
        'admin_service_area_product_id',
        'name',
        'qty',
        'sell_price',
        'mrp',
        'discount',
        'final_price',
    ];

    function order(){
        return $this->belongsTo('App\AdminOrder');
    }   
    
}
