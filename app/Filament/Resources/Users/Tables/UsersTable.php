<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Department;
class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('portal.user.columns.name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('portal.user.columns.email'))
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label(__('portal.user.columns.email_verified_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('portal.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('portal.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('position')
                    ->label(__('portal.user.columns.position'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('portal.user.columns.phone'))
                    ->searchable(),
                TextColumn::make('unit_id')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
                SelectFilter::make('department')
                    ->label(__('portal.user.columns.department'))
                    ->options(function() { Department::pluck('name', 'id')->toArray();})
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('unit', function (Builder $query) use ($data) {
                                $query->where('department_id', $data['value']);
                            });
                        }
        }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
