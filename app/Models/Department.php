<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    public function unit()
    {
        return $this->hasMany(Unit::class);
    }
}
