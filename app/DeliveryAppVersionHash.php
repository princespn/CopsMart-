<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryAppVersionHash extends Model
{
    protected $fillable =[
        'version_code',
        'hash'
    ];
}
