<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
class VendorProduct extends Model
{
    use LaravelVueDatatableTrait;
    protected $fillable = [
       'product_id','vendor_id','price','mrp','size','cost_price','offer_price','offer_status','daily_needs','is_active','is_featured'
    ];


    protected $dataTableColumns = [


    ];
    public function product(){
       return $this->belongsTo('App\Product');
    }

    public function vendor(){
        return $this->belongsTo('App\Vendor');
    }
    public function product_package(){
        return $this->hasMany('App\VendorProduct','product_id','product_id')
                     ->leftjoin('products', 'products.id', '=', 'vendor_products.product_id')
                    ->leftjoin('packages','vendor_products.package_id','=','packages.id')
                    ->join('vendors', 'vendors.id', '=', 'vendor_products.vendor_id')
                    ->where('vendor_products.is_active',1)
                    ->select('vendor_products.id','vendor_products.id as vendor_product_id','products.name as product_name','vendor_products.package_id as package','products.category_id as category_id','products.image','products.description','products.long_description','vendor_products.vendor_id','vendors.is_active as vendor_status','vendor_products.product_id','vendor_products.package_id','vendor_products.price','vendor_products.mrp','vendor_products.cost_price','vendor_products.offer_price','vendor_products.offer_status','vendor_products.daily_needs','vendor_products.is_active','vendor_products.is_featured','packages.name as package_name');
                    //->groupBy('packages.id');
    }
     public function vendor_product_package(){
        return $this->hasMany('App\VendorProduct','product_id','product_id');
                    //->groupBy('packages.id');
    }
}
