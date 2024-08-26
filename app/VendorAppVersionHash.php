<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorAppVersionHash extends Model
{
    protected $fillable =[
        'version_code',
        'hash'
    ];
}
