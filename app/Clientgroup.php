<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientgroup extends Model
{
    protected $guarded = [];

    public function members(){
        return $this->hasMany('App\Clientgroup_member', 'clientgroup_id', 'id');
    }
}
