<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
class Category extends Model
{
    use LaravelVueDatatableTrait;
    use SoftDeletes;
    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'name' => [
            'searchable' => true,
        ]
    ];
    protected $fillable = [
        'name', 'image', 'is_active',  'discount_percentage', 'delivery_calculation_type','rank','admin_id','vendor_id',
    ];

    public function subCategories(){
        return $this->hasMany('App\SubCategory');
    }
    public function category(){
        return $this->hasMany('App\Category', 'parent_id', 'id');
    }

    function commodity(){
        return $this->belongsTo('App\CommodityType');
    }

    // protected $dates = ['deleted_at'];
}
