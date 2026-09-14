<?php

namespace App\Filament\Resources\ModuleRequests\Tables;

use App\Models\ModuleRequest;
use Exception;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

use App\Enums\ModuleStatus;
use Spatie\Permission\Models\Role;

class ModuleRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('portal.user.columns.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('module.name')
                    ->label(__('portal.module.columns.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('purpose')
                    ->label('Tujuan Permohonan')
                    ->wrap()
                    ->size(TextSize::Small)
                    ->searchable(),

                TextColumn::make('status')
                    ->label(__('portal.module.columns.status'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('portal.module.columns.created_at'))
                    ->dateTime('d/m/Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([

                Action::make('approve')
                    ->label('Lulus')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form(function (ModuleRequest $record): array {
                        // Internal: show role selection
                        if ($record->module->is_internal) {
                            return [
                                Select::make('roles')
                                    ->label('Pilih Peranan')
                                    ->multiple()
                                    ->options(
                                        Role::where('name', 'like', strtoupper($record->module->code) . ':%')
                                            ->pluck('name', 'name')
                                    )
                                    ->preload()
                                    ->required(),
                            ];
                        }

                        // External: show notice or external role field
                        return [
                            Select::make('role')
                            ->label('Pilih Peranan')
                            ->options([0=>'Super Admin', 1=>'Admin', 2=>'User', 3=>'Viewer'])
                            ->required()
                        ];
                    })
                    ->visible(fn ($record) => $record->status === ModuleStatus::PENDING)
                    ->action(function (ModuleRequest $record, array $data) {
                        // check if internal or not
                        if ($record->module->is_internal) {
                            //try assign role and things
                            try {
                                $record->user->assignRole($data['roles'] ?? []);

                                $record->update(['status' => ModuleStatus::APPROVED]);

                                Notification::make()
                                    ->title(__('portal.messages.access_approved'))
                                    ->success()
                                    ->send();
                            }
                            catch (Exception $e) {
                                Notification::make()
                                ->title(__('portal.messages.action_failed'))
                                ->danger()
                                ->send();
                            }                
                        }
                        else {
                            $record->update([
                                'status' => ModuleStatus::APPROVED,
                                'role_assigned' => $data['role'],
                            ]);

                            Notification::make()
                                    ->title(__('portal.messages.access_approved'))
                                    ->success()
                                    ->send();
                        }
                               
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === ModuleStatus::PENDING)
                    ->action(function ($record) {
                        $record->update(['status' => ModuleStatus::REJECTED]);

                        Notification::make()
                            ->title(__('portal.messages.access_rejected'))
                            ->danger()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
