<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_no',
        'amount',
        'date',
        'delivery_type',
        'order_status_id',
        'status_updated',
        'payment_status',
        'scheduled_delivery_date',
        'delivery_charges_for_cust',
        'vendor_id',
        'vendor_status_id',
        'delivery_person_id',
        'delivery_status_id',
        'delivery_charges',
        'commodity_type_id',
        'marketing_person_id',
        'marketing_charges',
        'vendeo_accept_time',
        'delivery_accept_time',
        'vendor_slot',
        'delivery_slot',
        'coupon_id',
        'vendor_slab_time',
        'delivery_slab_time',
        'vendor_ready_time',
        'delivered_time',
        'cancel_amt',
        'cancel_date',
        'before_cancel_amt',
        'vend_del_discount',
        'earnings',
        'tip',
        'cost_price_amount',
        'itemtotal',
        'tmp_delivery_id',
        'ocean_del_dis',
        'applied_ocean_discount',
        'applied_vendor_discount',
        'vendor_discount',
        'ocean_discount',
        'makeabill',
        'is_show',
        'is_close',
        'is_edit',
        'after_edit_amount',
        'before_edit_amount',
        'total_rtgst'
    ];

    public function products(){
        return $this->hasMany('App\OrderProduct');
    }

    public function address(){
        return $this->hasOne('App\OrderAddress');
    }

    public function payment(){
        return $this->hasOne('App\OrderPayment');
    }

    public function vendor(){
        return $this->belongsTo('App\Vendor');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function status(){
        return $this->belongsTo('App\OrderStatus', 'order_status_id');
    }

    public function vendorStatus(){
        return $this->belongsTo('App\VendorStatus');
    }

    public function deliveryStatus(){
        return $this->belongsTo('App\DeliveryStatus');
    }


    public function deliveryPerson(){
        return $this->belongsTo('App\DeliveryPerson');
    }

    public function rating(){
        return $this->hasOne('App\OrderUserRating');
    }

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];
}
