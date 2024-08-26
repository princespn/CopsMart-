<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminOrder extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'date',
        'order_status_id',
        'delivery_status_id',
        'status_updated',
        'payment_status',
        'scheduled_delivery_date',
        'delivery_charges_for_cust',
        'admin_service_area_id',
    ];

    public function products(){
        return $this->hasMany('App\AdminOrderProduct');
    }
    public function payment(){
        return $this->hasOne('App\PaymentMethod','id','payment_status');
    }

    public function address(){
        return $this->hasOne('App\AdminOrderAddress','order_id');
    }

    public function admin_service_area(){
        return $this->belongsTo('App\AdminServiceArea');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function status(){
        return $this->belongsTo('App\OrderStatus', 'order_status_id');
    }

    public function delivery_status(){
        return $this->belongsTo('App\DeliveryStatus', 'delivery_status_id');
    }

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];
}
