<?php

namespace App\Filament\Schemas\Components;

use App\Contracts\IsActionable;
use App\Enums\ActionType;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionRuleBuilder extends Builder
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Pool Access Rules')
            ->helperText('Define which permissions an officer must hold to view and claim this task.')
            ->blocks([
                Block::make('permission_group')
                    ->label('Permission Group')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        ToggleButtons::make('operator')
                            ->label('Match Condition')
                            ->options([
                                'or'  => 'OR (Any permission matches)',
                                'and' => 'AND (All permissions required)',
                            ])
                            ->inline()
                            ->default('or')
                            ->required(),

                        Select::make('rules')
                            ->label('Required Permissions')
                            ->options(fn () => Permission::query()->pluck('name', 'name'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
            ])
            ->minItems(1)
            ->maxItems(2)
            ->default([
                [
                    'type' => 'permission_group',
                    'data' => [
                        'operator' => 'or',
                        'rules'    => [],
                    ],
                ],
            ]);
    }

    /**
     * Convenience helper to pre-fill defaults directly from an actionable model.
     */
    public function forActionable(ActionType $actionType = ActionType::REVIEW): static
    {
        return $this->default(function (?Model $record) use ($actionType): array {
            if ($record instanceof IsActionable) {
                $rules = $record->getRequiredPermissionsFor($actionType);
                if (!empty($rules)) {
                    return $rules;
                }
            }

            return [
                [
                    'type' => 'permission_group',
                    'data' => [
                        'operator' => 'or',
                        'rules'    => [],
                    ],
                ],
            ];
        });
    }
}
