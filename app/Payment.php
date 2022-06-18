<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function customer(){
    	return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    public function lists(){
    	return $this->hasMany('App\Booking_list', 'payment_id', 'id');
    }
}
