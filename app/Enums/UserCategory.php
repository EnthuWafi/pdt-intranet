<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
enum UserCategory: string implements HasLabel, HasColor, HasIcon
{    
    //
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';

    public function getLabel(): ?string
    {
        return __('portal.user.category.' . $this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::INTERNAL => 'primary',
            self::EXTERNAL => 'secondary',
        };
    }

    public function getIcon(): string|\BackedEnum|\Illuminate\Contracts\Support\Htmlable|null
    {
        return match ($this) {
            self::INTERNAL => 'm-css-internal',
            self::EXTERNAL => 'm-css-external',
        };
    }
}
