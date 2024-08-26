<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryDeliverySlab extends Model
{
   protected $fillable = [ 'limit_start', 'limit_end', 'category_id', 'charges', 'type','admin_id',];
}
