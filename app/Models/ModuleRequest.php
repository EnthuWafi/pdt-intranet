<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ModuleStatus;

class ModuleRequest extends Model
{
    //
    protected $guarded = [];

    // Cast the status string to your Enum automatically
    protected $casts = [
        'status' => ModuleStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
