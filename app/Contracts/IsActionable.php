<?php

namespace App\Contracts;

use App\Enums\ActionType;
use App\Models\ActionItem;
use App\Models\User;

interface IsActionable
{
    /**
     * Resolve the Filament or application URL for this task.
     */
    public function getActionUrl(ActionItem $item): string;

    /**
     * Handle domain-specific logic when this task is approved.
     */
    public function handleActionApproval(ActionItem $item, User $user): void;

    /**
     * Handle domain-specific logic when this task is rejected.
     */
    public function handleActionRejection(ActionItem $item, User $user, ?string $reason = null): void;

    /**
     * Provide permission requirements for a given workflow step.
     */
    public function getRequiredPermissionsFor(ActionType $actionType): array;
    /**
     * Return the Filament form components for reviewing/editing this task.
     */
    public function getActionFormSchema(ActionItem $item): array;
}
