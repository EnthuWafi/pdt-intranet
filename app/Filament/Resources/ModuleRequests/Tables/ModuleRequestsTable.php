<?php

namespace App\Filament\Resources\ModuleRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

use App\Enums\ModuleStatus;

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
                    ->visible(fn ($record) => $record->status === ModuleStatus::PENDING)
                    ->action(function ($record) {
                        $record->update(['status' => ModuleStatus::APPROVED]);
                        
                        $record->user->assignRole($record->module->code);

                        Notification::make()
                            ->title('Akses Diluluskan')
                            ->success()
                            ->send();
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
                            ->title('Akses Ditolak')
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
