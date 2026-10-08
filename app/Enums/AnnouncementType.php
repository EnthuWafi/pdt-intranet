<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
enum AnnouncementType: string implements HasLabel, HasColor, HasIcon
{
    //
    //
    case INFO = "info";
    case WARNING = "warning";
    case DANGER = "danger";
    case SUCCESS = 'success';

      public function getLabel(): ?string
    {
        return __('announcement.type.' . $this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::WARNING => 'warning',
            self::SUCCESS => 'success',
            self::DANGER => 'danger',
            self::INFO => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::WARNING => 'heroicon-o-exclamation-triangle',
            self::SUCCESS => 'heroicon-o-check-circle',
            self::DANGER  => 'heroicon-o-x-circle',
            self::INFO    => 'heroicon-o-information-circle',
        };
    }
}
