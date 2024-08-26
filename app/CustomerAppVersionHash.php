<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAppVersionHash extends Model
{
    protected $fillable =[
        'version_code',
        'hash'
    ];
}
