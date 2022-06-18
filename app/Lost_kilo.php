<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lost_kilo extends Model
{
    protected $guarded = [];

    public function booking(){
    	return $this->belongsTo('App\Booking_detail', 'booking_detail_id', 'id');
    }

    public function customer(){
    	return $this->belongsTo('App\Customer', 'customer_id', 'id');
    }
}
