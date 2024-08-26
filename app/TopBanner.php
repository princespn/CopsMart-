<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopBanner extends Model
{   
    use SoftDeletes;
    protected $fillable = 
    [
        'vendor_id','title','type','product_id','category_id','sub_category_id','sub_sub_category_id','brand_id','image','till_date','created_at','updated_at','is_active','is_deleted','deleted_at',
    ];

}
