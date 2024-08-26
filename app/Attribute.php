<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{ use SoftDeletes;
    protected $fillable = 
    [
        'vendor_id','name','type','created_at','updated_at','is_active','is_deleted',
    ];

}
