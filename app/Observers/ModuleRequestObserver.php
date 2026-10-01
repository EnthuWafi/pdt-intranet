<?php

namespace App\Observers;

use App\Enums\ActionItemStatus;
use App\Enums\ActionType;
use App\Enums\ModuleStatus;
use App\Models\ActionItem;
use App\Models\ModuleRequest;
use Illuminate\Validation\ValidationException;

class ModuleRequestObserver
{
    /**
     * Handle the ModuleRequest "created" event.
     */

    public function creating(ModuleRequest $request) {
        $exists = ModuleRequest::query()
            ->where('user_id', $request->user_id)
            ->where('module_id', $request->module_id)
            ->whereIn('status', [ModuleStatus::PENDING, ModuleStatus::APPROVED])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'module_id' => __('modules.messages.module_request_exist'),
            ]);
        }
    }
    public function created(ModuleRequest $moduleRequest): void
    {
        //
        $applicant = $moduleRequest->user;

        $rules = $moduleRequest->getRequiredPermissionsFor(ActionType::APPROVE);

        ActionItem::create([
            'assigned_to_user_id'  => null,
            'assigned_by_user_id'  => $moduleRequest->user_id,
            'actionable_type'      => ModuleRequest::class,
            'actionable_id'        => $moduleRequest->id,
            'title'                => __('modules.actions.title_request') . $moduleRequest->module->name,
            'description'          => __('modules.actions.desc_request', ['applicantname'=>$applicant->name, 
                                                                        'justification'=>$moduleRequest->purpose]),
            'action_type'          => ActionType::APPROVE,
            'status'               => ActionItemStatus::PENDING_APPROVAL,
            'required_permissions' => $rules,
        ]);
    }

    /**
     * Handle the ModuleRequest "updated" event.
     */
    public function updated(ModuleRequest $moduleRequest): void
    {
        //
    }

    /**
     * Handle the ModuleRequest "deleted" event.
     */
    public function deleted(ModuleRequest $moduleRequest): void
    {
        //
    }

    /**
     * Handle the ModuleRequest "restored" event.
     */
    public function restored(ModuleRequest $moduleRequest): void
    {
        //
    }

    /**
     * Handle the ModuleRequest "force deleted" event.
     */
    public function forceDeleted(ModuleRequest $moduleRequest): void
    {
        //
    }
}
