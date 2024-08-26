<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddCart extends Model
{   
    use SoftDeletes;
    protected $fillable = 
    [
        'vendor_id','user_id','product_id','size','color','qty','is_active','created_at','deleted_at','updated_at','size_index','color_index','price','stockfetch_id'
    ];

}
