<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderUserRating extends Model
{
    protected $fillable = [
        'order_id',
        'rating',
        'comment',
    ];

    protected $dates = ['deleted_at'];

    function order(){
        return $this->belongsTo('App\Order');
    }
}
