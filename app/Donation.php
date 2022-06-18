<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $guarded = [];

    public function client(){
    	return $this->hasOne('App\Client', 'id', 'client_id');
    }

    public function details(){
    	return $this->hasMany('App\Donation_detail', 'donation_id', 'id');
    }
}
