<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{   
    use SoftDeletes;
    protected $fillable = 
    [
        'vendor_id','name','image','created_at','updated_at','is_active','is_deleted','deleted_at',
    ];

}
