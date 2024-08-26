<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable, LaravelVueDatatableTrait,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = 
    [
       'vendor_id',
       'name',
       'email',
       'email_verified_at',
       'mobile',
       'mobile_verified_at',
       'card_no',
       'bukkle_no',
       'gender',
       'dob',
       'employee_post',
       'pincode',
       'district',
       'state',
       'blood_group',
       'date_of_joining',
       'date_of_retirement',
       'identification_mark',
       'address',
       'image',
       'type',
       'last_activity',
       'password',
       'deleted_at',
       'is_active',
       'remember_token',
       'created_at',
       'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'name' => [
            'searchable' => true,
        ],
        'mobile' => [
            'searchable' => true,
        ],
        'gender' => [
            'searchable' => true,
        ],
    ];
}
