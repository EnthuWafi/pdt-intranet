<?php

namespace App\Filament\Widgets;

use App\Enums\ModuleStatus;
use App\Models\Module;
use App\Models\ModuleRequest;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class ModuleRequestWidget extends Widget
{
    protected string $view = 'filament.widgets.module-request-widget';

    public function requestAccess($moduleId)
    {
        $user = auth()->user();

        $alreadyRequested = ModuleRequest::where('user_id', $user->id)
            ->where('module_id', $moduleId)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyRequested) {
            Notification::make()->title(__('portal.messages.already_applied'))->warning()->send();
            return;
        }

        ModuleRequest::create([
            'user_id' => $user->id,
            'module_id' => $moduleId,
            'status' => ModuleStatus::PENDING,
        ]);

        Notification::make()->title(__('portal.messages.request_submitted'))->success()->send();
    }

    protected function getViewData(): array
    {
        // TODO: Test this feature out later, add some module to make some request, if theres no module
        // It should display no module or something
        return [
            'modules' => Module::where('is_active', true)->get(), 
            'user' => auth()->user(), 
        ];
    }
}
