<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function package()
    {
    	return $this->belongsTo(Package::class);
    }

    public function area()
    {
    	return $this->belongsTo(Area::class);
    }

    public function dues()
    {
    	return $this->belongsTo(Billing::class, 'customer_id', 'id');
    }

    public function due(){
    	return $this->belongsTo(Billing::class, 'customer_id', 'id');
    }
}
