<?php

namespace App\Traits;

use Illuminate\Contracts\View\View;
trait HasSpecialHeader
{
    //
    public function getHeaderCategory(): ?string
    {
        return property_exists($this, 'headerCategory') ? $this->headerCategory : null;
    }

    public function getHeaderDescription(): ?string
    {
        return property_exists($this, 'headerDescription') ? $this->headerDescription : null;
    }

    public function getHeaderIcon(): string
    {
        return property_exists($this, 'headerIcon') ? $this->headerIcon : 'heroicon-m-squares-2x2';
    }

    public function getHeader(): ?View
    {
        return view('filament.components.portal-page-header', [
            'title' => $this->getTitle(),
            'category' => $this->getHeaderCategory(),
            'description' => $this->getHeaderDescription(),
            'icon' => $this->getHeaderIcon(),
            'actions' => $this->getCachedHeaderActions(),
        ]);
    }
}
