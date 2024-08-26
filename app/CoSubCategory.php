<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoSubCategory extends Model
{
    protected $fillable = [
        'co_sub_category_name','super_sub_category_id','sub_category_id','admin_id',
    ];

    public function subcategory(){
        return $this->belongsTo('App\SubCategory');
    }
}