<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    protected $guarded = [];

    public function product(){
    	return $this->hasOne('App\Product', 'id', 'product_id');
    }
}
