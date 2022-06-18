<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking_detail extends Model
{
    protected $guarded = [];

    public function product(){
    	return $this->hasOne('App\Product', 'id', 'product_id');
    }

    public function customer(){
    	return $this->hasOne('App\Customer', 'id', 'customer_id');
    }
}
