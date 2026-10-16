<?php

namespace App\Filament\Clusters\DataMaster\Resources\Positions\Pages;

use App\Filament\Clusters\DataMaster\Resources\Positions\PositionResource;
use App\Models\Position;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Set;

class ListPositions extends ListRecords
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            self::getCustomPermissionAction()
        ];
    }

    protected static function getCustomPermissionAction(): Action
    {
        return Action::make('promoteCustomPositions')
            ->label(__('position.actions.review_custom'))
            ->icon('heroicon-o-Sparkles')
            ->color('warning')
            ->badge(fn (): ?int => User::whereNull('position_id')->whereNotNull('position')->count() ?: null)
            ->schema([
                Select::make('custom_position_name')
                    ->label(__('position.fields.select_custom'))
                    ->options(
                        fn () => User::query()
                            ->whereNull('position_id')
                            ->whereNotNull('position')
                            ->distinct()
                            ->pluck('position', 'position')
                    )
                    ->live()
                    ->afterStateUpdated(fn (?string $state, Set $set) => $set('title', $state))
                    ->required(),

                TextInput::make('title')
                    ->label(__('position.fields.title'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('grade')
                    ->label(__('position.fields.grade'))
                    ->maxLength(50),
            ])
            ->action(function (array $data): void {
                $position = Position::firstOrCreate(
                    ['title' => $data['title']],
                    ['grade' => $data['grade'] ?? null]
                );

                // Link all users who typed this custom position to the new Position record
                User::query()
                    ->whereNull('position_id')
                    ->where('position', $data['custom_position_name'])
                    ->update([
                        'position_id' => $position->id,
                        'position' => null,
                    ]);
            });
    }
}
