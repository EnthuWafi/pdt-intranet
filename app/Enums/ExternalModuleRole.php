<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
enum ExternalModuleRole : int implements HasLabel
{
    //
    case SUPER_ADMIN = 0;
    case ADMIN = 1;
    case USER = 2;
    case VIEWER = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SUPER_ADMIN => __('roles.super_admin'),
            self::ADMIN       => __('roles.admin'),
            self::USER        => __('roles.user'),
            self::VIEWER      => __('roles.viewer'),
        };
    }
}
