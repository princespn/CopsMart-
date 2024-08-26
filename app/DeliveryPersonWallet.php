<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryPersonWallet extends Model
{
    protected $fillable =[
        'amount',
        'delivery_charges_for_cust',
        'description',
        'is_collectable',
        'is_adjustment',
        'delivery_person_id',
        'new_balance',
    ];


    protected $dates = [
        'deleted_at'
    ];
}
