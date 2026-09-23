<?php

namespace App\Contacts;

use App\Models\ActionItem;

interface HasActionUrl
{
    //
    public function getActionUrl(ActionItem $item): string;
}
