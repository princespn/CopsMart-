<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryReview extends Model
{
	protected $table='delivery_review';
	public $timestamps = true;
    protected $fillable = [
        'delivery_person_id',
        'order_id',
        'user_id',
        'vendor_id',
        'star',
        'review',
        'is_active',
        'created_at',
        'deleted_at',
    ];
}
