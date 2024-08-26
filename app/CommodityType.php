<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommodityType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'priority',
        'is_active',
        'admin_id',
    ];

    protected $dates= ['deleted_at'];

    function deliveryCommodity(){
        return $this->hasMany('App\DeliveryPersonCommodity');
    }

}
