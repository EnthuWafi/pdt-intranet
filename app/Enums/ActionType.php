<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
enum ActionType : string implements HasLabel, HasColor, HasIcon
{
    // case EDIT = 'edit'; // waiting for an edit
    case APPROVE = 'approve'; // waiting for approval
    case REVIEW = 'review'; // subordinate needs to review
    case REVISE = 'revise';
    case ACKNOWLEDGE = 'acknowledge';

    public function getLabel(): ?string
    {
        return __('action_inbox.types.' . $this->value );
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            // self::EDIT    => 'info',
            self::REVIEW  => 'warning',
            self::APPROVE => 'success',
            self::REVISE      => 'warning',
            self::ACKNOWLEDGE => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            // self::EDIT    => 'heroicon-m-pencil-square',
            self::REVIEW  => 'heroicon-m-clipboard-document-check',
            self::APPROVE => 'heroicon-m-check-badge',
            self::REVISE      => 'heroicon-m-arrow-path',
            self::ACKNOWLEDGE => 'heroicon-m-eye',
        };
    }
}