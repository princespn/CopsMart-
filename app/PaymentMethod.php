<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable= [
        'name',
        'code',
        'is_postpaid',
        'is_active'
    ];
}
