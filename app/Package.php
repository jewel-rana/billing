<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public $timestamps = false;

    public function customers()
    {
    	return $this->hasMany(Customer::class, 'package_id', 'id');
    }
}
