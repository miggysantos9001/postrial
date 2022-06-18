<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function details(){
    	return $this->hasMany('App\Order_detail', 'order_id', 'id')->orderBy('position');
    }

    public function detail(){
    	return $this->hasOne('App\Order_detail', 'order_id', 'id');
    }

    public function term(){
    	return $this->hasOne('App\Term', 'id', 'term_id');
    }

    public function client(){
        return $this->hasOne('App\Client', 'id', 'client_id');
    }
}
