<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminOrderAddress extends Model
{
    protected $fillable = [
        'name', 'address', 'landmark', 'mobile', 'latitude', 'longitude', 'service_area_id', 'order_id'
    ];

    public function order(){
        return $this->belongsTo('App\AdminOrder');
    }

    protected $dates = [ 'deleted_at'];
}
