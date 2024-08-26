<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
class AdminCategory extends Model
{
    use LaravelVueDatatableTrait;

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'name' => [
            'searchable' => true,
        ]
    ];
    protected $fillable = [
        'parent_id','name', 'image', 'is_active'
    ];

    
    public function sub_category(){
        return $this->hasMany('App\AdminCategory', 'parent_id', 'id');
    }

    // protected $dates = ['deleted_at'];
}
