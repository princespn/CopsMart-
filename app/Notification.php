<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{   
    use SoftDeletes;
    protected $fillable = 
    [
        'vendor_id',
        'user_id',
        'title',
        'type',
        'product_id',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id',
        'brand_id',
        'order_id',
        'image',
        'is_read',
        'created_at',
        'updated_at',
        'is_active',
        'is_send',
        'deleted_at',
    ];

}
