<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ModuleStatus;
use Illuminate\Validation\ValidationException;

class ModuleRequest extends Model
{
    //
    protected $guarded = [];

    // Cast the status string to your Enum automatically
    protected $casts = [
        'role_assigned' => ExternalModuleRole::class,
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
    protected static function booted(): void
    {
        static::creating(function (ModuleRequest $request) {
            $exists = static::query()
                ->where('user_id', $request->user_id)
                ->where('module_id', $request->module_id)
                ->whereIn('status', [ModuleStatus::PENDING, ModuleStatus::APPROVED])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'module_id' => __('portal.messages.module_request_exist'),
                ]);
            }
        });
    }
}
