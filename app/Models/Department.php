<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $guarded = [];
    
    public function unit()
    {
        return $this->hasMany(Unit::class);
    }
}
