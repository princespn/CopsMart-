<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class AdminProduct extends Model
{
    use LaravelVueDatatableTrait;
    protected $fillable = [
        'name', 'image', 'is_active', 'description', 'sub_category_id','category_id','is_featured'];

    protected $dataTableColumns = [
        'admin_products.name' => [
            'searchable' => true,
        ],
        'admin_products.package' => [
            'searchable' => true,
        ],
    ];

    public function subCategory(){
        return $this->belongsTo('App\AdminCategory');
    }

    
    public function packages(){
        return $this->hasMany('App\AdminProductPackage','product_id','id')
                    ->leftjoin('packages','admin_product_packages.package_id','=','packages.id')
                    ->select('admin_product_packages.*','packages.name as package_name');
    }
}
