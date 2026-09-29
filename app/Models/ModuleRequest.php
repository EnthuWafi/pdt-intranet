<?php

namespace App\Models;

use App\Contracts\IsActionable;
use App\Enums\ActionType;
use App\Filament\Resources\ModuleRequests\ModuleRequestResource;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ModuleStatus;
use Illuminate\Validation\ValidationException;

class ModuleRequest extends Model implements IsActionable
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

    public function handleActionApproval(ActionItem $item, User $user): void
    {
        if (!empty($item->payload)) {
            $this->update($item->payload);
        }

        $this->update(['status' => ModuleStatus::APPROVED]);
    }

    public function handleActionRejection(ActionItem $item, User $user, ?string $reason = null): void
    {
        $this->update(['status' => ModuleStatus::REJECTED]);
    }

    public function getRequiredPermissionsFor(ActionType $actionType): array
    {
        $rules = match ($actionType) {
            ActionType::APPROVE => ['Approve:ModuleRequest'],
            ActionType::REVIEW  => ['Review:ModuleRequest'],
            default             => [],
        };

        return [
            [
                'type' => 'permission_group',
                'data' => [
                    'operator' => 'or',
                    'rules'    => $rules,
                ],
            ],
        ];
    }

    public function getActionFormSchema(ActionItem $item): array
    {
        return ModuleRequestResource::getFormSchema();
    }
}
