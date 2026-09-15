<?php

namespace App\Filament\Pages;

use App\Enums\ModuleStatus;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use App\Models\Module;
use App\Models\ModuleRequest;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MyAccess extends Page
{
    protected string $view = 'filament.pages.my-access';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    public ?array $data = [];
    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    //check box list for pickin shit
                    CheckboxList::make('module_ids')
                        ->label(__('portal.module.list_available'))
                        ->options(
                            Module::where('is_active', true)
                            ->whereDoesntHave('moduleRequests', function ($query) {
                                $query->where('user_id', auth()->id())
                                ->whereIn('status', [ModuleStatus::PENDING, ModuleStatus::APPROVED]);
                            })
                            ->pluck('name', 'id'))
                        ->descriptions(Module::where('is_active', true)->pluck('description', 'id'))
                        ->columns(3)
                        ->required(),
                        
                    Textarea::make('purpose')
                        ->label(__('portal.module.purpose_request'))
                        ->required()
                        ->rows(3),
                ])
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label(__('portal.actions.apply'))
                            ->submit('save')
                            ->color('primary')
                            ->extraAttributes(['class' => 'mb-6']), 
                    ]),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        try {
            DB::transaction(function() use ($data) {

                foreach ($data['module_ids'] as $moduleId) {
                    //check for each if request exists

                    $exists = ModuleRequest::query()
                        ->where('user_id', auth()->id())
                        ->where('module_id', $moduleId)
                        ->where('status', [ModuleStatus::PENDING, ModuleStatus::APPROVED])
                        ->exists();

                    if ($exists) {
                        throw ValidationException::withMessages([
                            'module_ids' => __('portal.messages.already_applied')
                        ]);
                    }

                    ModuleRequest::create([
                        'user_id' => auth()->id(),
                        'module_id' => $moduleId,
                        'purpose' => $data['purpose'],
                        'status' => ModuleStatus::PENDING->value, 
                    ]);
                }
                    
                $this->form->fill();

                Notification::make()
                    ->title(__('portal.messages.request_submitted'))
                    ->success()
                    ->send();
            });
        }
        catch (ValidationException $e) {
            Notification::make()
                ->title(__('portal.messages.already_applied'))
                ->danger()
                ->send();
        }
    }
}
