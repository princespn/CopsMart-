<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketingAppVersionHash extends Model
{
    protected $fillable =[
        'version_code',
        'hash'
    ];
}
