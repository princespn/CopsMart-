<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryPersonDeviceToken extends Model
{
    protected $fillable =[
        'token',
        'delivery_person_id'
    ];
}
