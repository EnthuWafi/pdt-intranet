<?php

namespace App\Models;

use App\Contacts\HasActionUrl;
use App\Enums\ActionType;
use App\Filament\Resources\ModuleRequests\ModuleRequestResource;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ModuleStatus;
use Illuminate\Validation\ValidationException;

class ModuleRequest extends Model implements HasActionUrl
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
                    'module_id' => __('modules.messages.module_request_exist'),
                ]);
            }
        });
    }

    public function getActionUrl(ActionItem $item): string
    {
        $parameters = [
            'tableActionRecord' => $this->id,
        ];
        $actionName = match ($item->action_type) {
            ActionType::APPROVE => 'approve',
            ActionType::REVIEW  => 'approve',
            default             => null,
        };

        if ($actionName) {
            $parameters['tableAction'] = $actionName;
        }

        if ($this->relationLoaded('user') && $this->user) {
            $parameters['tableSearch'] = $this->user->name;
        }

        return ModuleRequestResource::getUrl('index', $parameters);
    }
}
