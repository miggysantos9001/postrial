<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = [];

    public function expensetype(){
    	return $this->hasOne('App\Expensetype', 'id', 'expensetype_id');
    }
}
