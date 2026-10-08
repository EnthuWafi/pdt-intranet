<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
enum UserStatus: string implements HasLabel, HasColor, HasIcon
{
    //
    case PENDING = "pending";
    case ACTIVE = "active";
    case SUSPENDED = "suspended";

      public function getLabel(): ?string
    {
        return __('user.status.' . $this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::ACTIVE => 'success',
            self::SUSPENDED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-m-clock',
            self::ACTIVE => 'heroicon-m-check-circle',
            self::SUSPENDED => 'heroicon-m-x-circle',
        };
    }
}
