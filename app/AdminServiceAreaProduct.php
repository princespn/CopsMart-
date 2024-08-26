<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
class AdminServiceAreaProduct extends Model
{
    use LaravelVueDatatableTrait;
    protected $fillable = [
       'product_id','service_area_id','package_id','sale_price','mrp','delivery_charges','delivery_in_days','replacement_in_days','available_finance','is_active'
    ];


    protected $dataTableColumns = [


    ];
    public function product(){
       return $this->belongsTo('App\AdminProduct');
    }

    public function vendor(){
        return $this->belongsTo('App\Vendor');
    }
    
    public function packages(){
        return $this->hasMany('App\Package','id','package_id');
                     
    }
     public function admin_product_package(){
        return $this->hasMany('App\AdminServiceAreaProduct','product_id','product_id');
                    //->groupBy('packages.id');
    }
}
