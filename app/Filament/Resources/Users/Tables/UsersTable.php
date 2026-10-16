<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserCategory;
use App\Enums\UserStatus;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->label(__('user.columns.user_profile'))
                    ->collection('avatar')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => $record->getFilamentAvatarUrl()),
                TextColumn::make('name')
                    ->label(__('user.columns.name'))
                    ->searchable()
                    ->sortable()
                    ->description(fn (User $record): string => $record->email),
                TextColumn::make('email')
                    ->label(__('user.columns.email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email_verified_at')
                    ->label(__('user.columns.email_verified_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('common.timestamps.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('getPositionLabelAttribute')
                    ->label(__('user.columns.position'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('user.columns.phone'))
                    ->searchable(),
                TextColumn::make('unit_id')
                    ->numeric()
                    ->sortable(),
                    TextColumn::make('user_category')
                    ->label(__('user.fields.category'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('position.name')
                    ->label(__('user.fields.position'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('department.name')
                    ->label(__('user.fields.department_unit'))
                    ->description(fn (User $record): ?string => $record->unit?->name)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label(__('user.fields.roles'))
                    ->badge()
                    ->color('primary'),
                TextColumn::make('updated_at')
                    ->label(__('common.timestamps.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('last_seen_at')
                    ->label(__('user.fields.last_seen'))
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('status')
                    ->label(__('user.columns.status'))
                    ->options(UserStatus::class),

                SelectFilter::make('category')
                    ->label(__('user.columns.user_category'))
                    ->options(UserCategory::class),

                SelectFilter::make('department_id')
                    ->label(__('user.columns.department'))
                    ->relationship('department', 'name'),

                SelectFilter::make('unit_id')
                    ->label(__('user.columns.unit'))
                    ->relationship('unit', 'name'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label(__('user.actions.approve'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (User $record): bool => $record->status !== UserStatus::ACTIVE)
                    ->action(function (User $record): void {
                        $record->update([
                            'status' => UserStatus::ACTIVE,
                            'approved_at' => now(),
                            'approved_by' => Auth::id(),
                        ]);
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
