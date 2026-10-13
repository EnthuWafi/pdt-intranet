<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum FeedbackCategory: string implements HasLabel, HasColor, HasIcon
{
    case GENERAL = 'general';
    case BUG = 'bug';
    case FEATURE = 'feature';
    case COMPLAINT = 'complaint';
    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return __('feedback.category.' . $this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::GENERAL => 'info',
            self::BUG => 'danger',
            self::FEATURE => 'success',
            self::COMPLAINT => 'warning',
            self::OTHER => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::GENERAL => 'heroicon-o-chat-bubble-bottom-center-text',
            self::BUG => 'heroicon-o-bug-ant',
            self::FEATURE => 'heroicon-o-light-bulb',
            self::COMPLAINT => 'heroicon-o-exclamation-triangle',
            self::OTHER => 'heroicon-o-ellipsis-horizontal-circle',
        };
    }
}
