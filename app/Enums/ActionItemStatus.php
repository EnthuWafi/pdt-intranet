<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
enum ActionItemStatus: string implements HasLabel, HasColor, HasIcon
{
    //
    case PENDING_APPROVAL = "pending_approval"; ///default
    // case PENDING_REVIEW = "pending_review"; //not to be confused for pending approval, 
                                            // this is only to confirm review (actually scrap this)
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function getLabel(): ?string
    {
        return __('action_inbox.status.' . $this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING_APPROVAL   => 'warning',
            self::COMPLETED => 'success',
            self::CANCELLED => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING_APPROVAL   => 'heroicon-m-clock',
            self::COMPLETED => 'heroicon-m-check-circle',
            self::CANCELLED => 'heroicon-m-x-circle',
        };
    }
}