<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceArea extends Model
{
    protected $fillable=[ 'name', 'range', 'latitude', 'longitude', 'is_active'];

    public function addresses(){
        return $this->hasMany('App\UserAddress');
    }
}
