<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    protected $fillable =[
        'id',
        'code',
        'name',
        'description'
    ];

    protected $dates = ['deleted_at'];

    function orders(){
         return $this->hasMany('App\Order');
    }
}
