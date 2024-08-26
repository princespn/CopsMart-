<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingPerson extends Model
{
    protected $fillable =[
        'name','mobile','email' ,'last_activity' ,'is_active','driving_license_no','aadhar_no' ,'service_area_id', 'lat', 'long'
    ];

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
   protected $dates = ['deleted_at'];
}
