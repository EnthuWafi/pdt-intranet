<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
enum ActionType : string implements HasLabel, HasColor, HasIcon
{
    case VIEW = 'view';
    case EDIT = 'edit';
    case APPROVE = 'approve';
    case REVIEW = 'review';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::VIEW    => __('action_inbox.types.view'),
            self::EDIT    => __('action_inbox.types.edit'),
            self::APPROVE => __('action_inbox.types.approve'),
            self::REVIEW  => __('action_inbox.types.review'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::VIEW    => 'gray',
            self::EDIT    => 'info',
            self::REVIEW  => 'warning',
            self::APPROVE => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::VIEW    => 'heroicon-m-eye',
            self::EDIT    => 'heroicon-m-pencil-square',
            self::REVIEW  => 'heroicon-m-clipboard-document-check',
            self::APPROVE => 'heroicon-m-check-badge',
        };
    }
}