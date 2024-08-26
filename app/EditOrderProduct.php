<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EditOrderProduct extends Model
{   
    use SoftDeletes;
    protected $fillable=[
        'order_id',
        'product_id',
        'qty',
        'old_qty',
        'price',
        'mrp',
        'size',
        'color',
        'gst',
        'size',
        'hsn',
        'tgst',
        'cgst',
        'sgst',
        'igst',
        'taxable_rate',
        'updated_at',
        'created_at'
    ];
  
    protected $dates = ['deleted_at'];
}
