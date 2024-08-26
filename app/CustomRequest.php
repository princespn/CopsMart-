<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomRequest extends Model
{ use SoftDeletes;
    protected $fillable = 
    [
        'approve_qty','user_id','product_id','product_name','size','color','size_name','color_name','expected_date','qty','status','is_active','created_at','updated_at','type','additional_msg','brand_id','brand_name','image'
    ];

}
