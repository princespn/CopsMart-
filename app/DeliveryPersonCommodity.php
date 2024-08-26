<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryPersonCommodity extends Model
{
    protected $fillable =[
        'delivery_person_id', 'commodity_type_id'
    ];

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    function commodity(){
       return $this->belongsTo('App\CommodityType', 'commodity_type_id', 'id');
    }

    public function deliveryPerson(){
        return $this->belongsTo('App\DeliveryPerson');
    }
}
