<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    protected $fillable = [
        'name',
        'marketing_person_id',
        'discount_type',
        'discount_value',
        'category_id',
        'vendor_id',
        'min_order_amount',
        'max_discount_amount',
        'per_user_limit',
        'is_active'
    ];

      /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function marketingPerson(){
        return $this->belongsTo('App\MarketingPerson');
    }
}
