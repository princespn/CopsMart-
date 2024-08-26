<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    protected $fillable = [
        'name', 'address', 'pincode', 'mobile', 'district', 'state', 'title' ,'user_id', 'lat', 'long', 'is_active', 'is_default'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function serviceArea(){
        return $this->belongsTo('App\ServiceArea');
    }
}
