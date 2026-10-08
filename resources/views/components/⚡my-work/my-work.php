<?php

use App\Enums\ActivityEvent;
use App\Enums\ModuleStatus;
use App\Models\ActivityLog;
use App\Models\ModuleRequest;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    //
    use WithPagination;

    public string $activeTab = 'my-action'; //my-action / notification / 

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function approveRequest(int $id): void
    {
        abort_unless(Auth::user()->can('approve_module_requests'), 403);

        $record = ModuleRequest::findOrFail($id);
        $record->update(['status' => ModuleStatus::APPROVED]);

        ActivityLog::log(
            event: ActivityEvent::APPROVED,
            description: __('modules.approving_module', 
            ['modulename' => $record->module->name, 'username' => $record->user->name]),
            subject: $record
        );

        Notification::make()
            ->title(__('modules.messages.access_approved'))
            ->success()
            ->send();
    }

    public function markNotificationAsRead(string $id): void
    {
        Auth::user()->unreadNotifications->where('id', $id)->markAsRead();
    }

    public function render()
    {
        $user = Auth::user();

        // 1. Peti Tindakan: Pending requests for admins
        $pendingRequests = $user->can('approve_module_requests')
            ? ModuleRequest::with(['user', 'module'])
                ->where('status', ModuleStatus::PENDING)
                ->latest()
                ->paginate(4, ['*'], 'tindakan_page')
            : collect();

        // 2. Notifikasi: Personal user alerts
        $notifications = $user->notifications()
            ->paginate(4, ['*'], 'notifikasi_page');

        // 3. Aktiviti Saya: Action history for this user
        $activities = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->paginate(4, ['*'], 'aktiviti_page');

        return view('livewire.dashboard.kerja-saya', [
            'pendingRequests' => $pendingRequests,
            'notifications' => $notifications,
            'activities' => $activities,
            'pendingCount' => $user->can('approve_module_requests') 
                ? ModuleRequest::where('status', ModuleStatus::PENDING)->count() 
                : 0,
            'unreadNotificationCount' => $user->unreadNotifications->count(),
        ]);
    }
};