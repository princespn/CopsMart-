<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{   
    use SoftDeletes;
    protected $fillable=[
        'order_id',
        'product_id',
        'vendor_product_id',
        'name',
        'qty',
        'hsn',
        'price',
        'mrp',
        'cost_price',
        'discount',
        'tax',
        'final_price',
        'vendor_dis_price',
         'size',
        'color',
        'vendor_dis_cost',
        'dis_price','gst','tgst','cgst','sgst','igst','taxable_rate','purchase_rate'
    ];

    function order(){
        return $this->belongsTo('App\Order');
    }
        
    protected $dates = ['deleted_at'];
}
