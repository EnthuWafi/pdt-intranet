<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Module extends Model
{
    //
    protected $guarded = [
        'is_internal',

    ];

    protected $fillable = [
        'name',
        'code',
        'description',
        'url_path',
        'icon',
        'is_active'
    ];

    //module requests
    public function moduleRequests()
    {
        return $this->hasMany(ModuleRequest::class);
    }

    protected static function booted(): void
    {
        // No delete internal
        static::deleting(function (Module $module) {
            if ($module->is_internal) {
                throw new \Exception("Internal modules cannot be deleted.");
            }
        });

        // Prevent changing is_internal 
        static::updating(function (Module $module) {
            if ($module->isDirty('is_internal')) {
                $module->getOriginal('is_internal');
                throw new \Exception("The internal status of this module cannot be modified.");
            }
        });
    }
}
