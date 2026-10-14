<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

#[Fillable(['name', 'email', 'password', 'last_seen_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasMedia, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia, HasRecursiveRelationships;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class
        ];
    }

    //units
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    //position relation
    public function positionRelation()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    //department
    public function department()
    {
        return $this->hasOneThrough(Department::class, Unit::class);
    }

    //module requests
    public function moduleRequests()
    {
        return $this->hasMany(ModuleRequest::class);
    }

    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getParentKeyName(): string
    {
        return 'manager_id';
    }


    //avatar upload
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useDisk('public')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']);
    }

    //thumb compress
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->quality(80)
            ->nonQueued();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if ($avatar = $this->getFirstMediaUrl('avatar', 'thumb')) {
            return $avatar;
        }

        $colors = $this->getAvatarColors();

        // PHP_QUERY_RFC3986 for encoding
        return config('services.ui_avatars.url') . '?' . http_build_query([
            'name' => $this->name,
            'background' => $colors['background'],
            'color' => $colors['color'],
            'format' => 'svg',
        ], encoding_type: PHP_QUERY_RFC3986);
    }

    //colors
    private function getAvatarColors(): array
    {
        $bg = substr(md5($this->name), 0, 6);

        // Hex to RGB
        $r = hexdec(substr($bg, 0, 2));
        $g = hexdec(substr($bg, 2, 2));
        $b = hexdec(substr($bg, 4, 2));

        // YIQ contrast formula
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        $text = ($yiq >= 128) ? '000000' : 'ffffff';

        return [
            'background' => $bg,
            'color' => $text,
        ];
    }
}
