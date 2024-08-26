<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaytmSetting extends Model
{
    protected $fillable= [
        'mid',
        'mkey',
        'website',
        'type',
        'channel_id',
        'env',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
