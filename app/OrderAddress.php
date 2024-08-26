<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAddress extends Model
{
    protected $fillable = [
        'name', 'address', 'landmark', 'mobile', 'lat', 'long', 'service_area_id', 'order_id','state','district','pincode','title'
    ];

    public function order(){
        return $this->belongsTo('App\Order');
    }

    protected $dates = [ 'deleted_at'];
}
