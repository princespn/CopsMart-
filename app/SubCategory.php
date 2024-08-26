<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{   
    use SoftDeletes;
    protected $fillable = [
        'name','super_category_id','vendor_id','category_id','parent_id', 'image', 'is_active','co_sub_category','admin_id','vendor_id',
    ];

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function co_subcategory(){
        return $this->hasMany('App\CoSubCategory','sub_category_id','id');
    }
    
    public function products(){
        return $this->hasMany('App\Product');
    }
     public function subcategory(){
        return $this->hasMany('App\SubCategory','parent_id','id')->with('cosubcategory');
    }
    public function cosubcategory(){
        return $this->hasMany('App\CoSubCategory','sub_category_id','id');
    }
}
