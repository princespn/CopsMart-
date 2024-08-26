<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingPersonWallet extends Model
{
    protected $fillable =[
        'amount',
        'description',
        'is_collectable',
        'is_adjustment',
        'marketing_person_id',
    ];


    protected $dates = [
        'deleted_at'
    ];
}
