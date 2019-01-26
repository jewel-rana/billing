<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    public function customer(){
    	return $this->belongsTo(\App\Customer::class, 'customer_id', 'id');
    }
}
