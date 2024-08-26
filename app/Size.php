<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{ use SoftDeletes;
    protected $fillable = 
    [
        'vendor_id','name','created_at','updated_at','is_active','deleted_at',
    ];

}
