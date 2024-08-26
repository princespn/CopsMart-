<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorStatus extends Model
{
    protected $fillable =[
        'id',
        'code',
        'name',
        'description'
    ];

    protected $dates = ['deleted_at'];
}
