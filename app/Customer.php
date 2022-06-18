<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function group(){
    	return $this->hasOne('App\Customergroup', 'id', 'group_id');
    }

    public function bookings(){
    	return $this->hasMany('App\Booking', 'customer_id', 'id');
    }

    public function lists(){
        return $this->hasMany('App\Booking_list', 'customer_id', 'id');
    }

    public function pricings(){
    	return $this->hasMany('App\Customer_pricing', 'customer_id', 'id');
    }
}
