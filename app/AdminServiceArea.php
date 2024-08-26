<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminServiceArea extends Model
{
    protected $fillable= ['name','contact_no','alternate_number', 'service_area_id', 'category_id', 'is_active'];

    public function vendorProducts(){
        return $this->hasMany('App\AdminServiceAreaProduct');
    }
    
    public function vendorCategory(){
        return $this->belongsTo('App\AdminCategory','category_id','id');
    }
    
}
