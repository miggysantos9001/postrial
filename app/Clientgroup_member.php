<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientgroup_member extends Model
{
    protected $guarded = [];

    public function client(){
    	return $this->hasOne('App\Client', 'id', 'client_id');
    }
}
