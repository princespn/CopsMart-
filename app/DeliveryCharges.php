<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
class DeliveryCharges extends Model
{
	use LaravelVueDatatableTrait;
	protected $table='delivery_charges';
	public $timestamps = false;
    protected $fillable = ['admin_id','pincode','start_limit','end_limit','delivery_charge','extra_charges_per_km','vendor_id','type'];
}
