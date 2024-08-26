<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseeReport extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'vendor_id',
        'franchisee_id',
        'delivery_person_id',
        'delivery_charges',
        'vendor_delivery_chargers',
        'order_amount',
        'vendor_amount',
        'franchisee_profit',
        'commision'	,
        'profit',
        'description',
        'is_collectable', 
        'new_balance',
        'created_at',
        'updated_at',
    ];

  
}