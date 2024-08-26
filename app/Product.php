<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Product extends Model
{
    use LaravelVueDatatableTrait;
    use SoftDeletes;
    protected $fillable = [
        'is_active','is_featured','name','admin_id','vendor_id', 'is_active','description','sub_category_id','sub_sub_category_id','category_id','barcode', 'hsn','gst','maxqty','weight','brand_id','other','tags','size','color','hide','mrp'
    ];

    protected $dataTableColumns = [
        'products.name' => [
            'searchable' => true,
        ],
        'products.package' => [
            'searchable' => true,
        ],
    ];

    public function subCategory(){
        return $this->belongsTo('App\SubCategory');
    }

    // public function vendorProducts(){
    //     return $this->hasMany('App\VendorProduct');
    // }
    // public function packages(){
    //     return $this->hasMany('App\ProductPackage','product_id','id')
    //                 ->leftjoin('packages','product_packages.package_id','=','packages.id')
    //                 ->select('product_packages.*','packages.name as package_name');
    // }
}
