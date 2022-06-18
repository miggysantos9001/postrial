<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public function details(){
    	return $this->hasMany('App\Booking_detail', 'booking_id', 'id');
    }

    public function client(){
    	return $this->hasOne('App\Client', 'id', 'client_id');
    }

    
}
