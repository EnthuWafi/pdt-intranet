<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    //department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
