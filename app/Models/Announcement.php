<?php

namespace App\Models;

use App\Enums\AnnouncementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
class Announcement extends Model
{
    //
    protected $guarded = [];

    protected function casts(): array {
      return [
        'type' => AnnouncementType::class
      ];
    }
    //old way
    // public function scopeActive($query)
    // {
    //     return $query->where('is_active', true)
    //         ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
    //         ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
    // }

    #[Scope] 
    protected function active(Builder $query): Builder
    {
          return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
    }
}
