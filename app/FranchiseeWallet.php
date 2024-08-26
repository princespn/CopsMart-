<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseeWallet extends Model
{
    protected $fillable = [
        'order_id',
        'vendor_id',
        'franchisee_id',
        'amount',
        'new_balance',
        'is_collectable',
        'is_adjustable',
        'created_at',
        'updated_at',
        'description',
        'profit',
    ];

  
}