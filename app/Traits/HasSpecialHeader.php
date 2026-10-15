<?php

namespace App\Traits;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
trait HasSpecialHeader
{
    //
    public function getHeaderCategory(): string | Htmlable | null
    {
        // Check if this is a Page that belongs to a Resource
        if (method_exists(static::class, 'getResource')) {
            return static::getResource()::getNavigationGroup();
        }

        // Check if this is a Resource itself
        if (method_exists(static::class, 'getNavigationGroup')) {
            return static::getNavigationGroup();
        }

        return null;
    }

    public function getHeaderDescription(): string | Htmlable | null
    {
        return null;
    }

    public function getHeaderIcon(): string | BackedEnum | Htmlable | null
    {
        // Check if this is a Page that belongs to a Resource
        if (method_exists(static::class, 'getResource')) {
            return static::getResource()::getNavigationIcon();
        }

        // Check if this is a Resource itself
        if (method_exists(static::class, 'getNavigationGroup')) {
            return static::getNavigationIcon();
        }

        return null;

    }

    public function getHeader(): ?View
    {
        return view('filament.components.portal-page-header', [
            'title' => $this->getHeading(),
            'category' => $this->getHeaderCategory(),
            'description' => $this->getHeaderDescription(),
            'icon' => $this->getHeaderIcon(),
            'actions' => $this->getCachedHeaderActions(),
            'breadcrumbs' => $this->getBreadcrumbs(),
        ]);
    }
}
