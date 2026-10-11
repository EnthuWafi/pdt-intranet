<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'blocks',
    ];

    protected $casts = [
        'blocks' => 'array',
    ];
}
