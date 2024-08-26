<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wishlist extends Model
{ use SoftDeletes;
    protected $fillable = 
    [
        'vendor_id','product_id','user_id','is_active','created_at','updated_at',
    ];

}
