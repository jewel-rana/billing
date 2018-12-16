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
}
