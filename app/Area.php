<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public $timestamps = false;

    public function customers()
    {
    	return $this->hasMany(Customer::class, 'area_id', 'id');
    }

    public function childs()
    {
    	return $this->hasMany(Area::class, 'parent', 'id');
    }
}
