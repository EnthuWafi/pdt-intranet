<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ActivityEvent: string implements HasLabel, HasColor, HasIcon
{
    case CREATED   = 'created';
    case UPDATED   = 'updated';
    case DELETED   = 'deleted';
    case REQUESTED = 'requested';
    case APPROVED  = 'approved';
    case REJECTED  = 'rejected';

    public function getLabel(): ?string
    {
        return __('my_activity.events.' . $this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::CREATED   => 'info',
            self::UPDATED   => 'gray',
            self::DELETED   => 'danger',
            self::REQUESTED => 'warning',
            self::APPROVED  => 'success',
            self::REJECTED  => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CREATED   => 'heroicon-m-plus-circle',
            self::UPDATED   => 'heroicon-m-pencil-square',
            self::DELETED   => 'heroicon-m-trash',
            self::REQUESTED => 'heroicon-m-paper-airplane',
            self::APPROVED  => 'heroicon-m-check-circle',
            self::REJECTED  => 'heroicon-m-x-circle',
        };
    }
}