<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donation_detail extends Model
{
    protected $guarded = [];

    public function product(){
    	return $this->hasOne('App\Product', 'id', 'product_id');
    }

    public function donation(){
    	return $this->hasOne('App\Donation', 'id', 'donation_id');
    }
}
