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
            self::SUPER_ADMIN => __('portal.roles.super_admin'),
            self::ADMIN       => __('portal.roles.admin'),
            self::USER        => __('portal.roles.user'),
            self::VIEWER      => __('portal.roles.viewer'),
        };
    }
}
