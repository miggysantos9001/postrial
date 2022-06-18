<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking_list extends Model
{
    protected $guarded = [];

    public function booking(){
    	return $this->belongsTo('App\Booking', 'booking_id', 'id');
    }

    public function customer(){
    	return $this->belongsTo('App\Customer', 'customer_id', 'id');
    }

    public function payment(){
    	return $this->hasOne('App\Payment', 'id', 'payment_id');
    }

    public function b(){
        return $this->hasOne('App\Booking', 'id', 'booking_id');
    }
}
