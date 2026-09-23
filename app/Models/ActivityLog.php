<?php

namespace App\Models;

use App\Enums\ActivityEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
class ActivityLog extends Model
{
    //
    protected $guarded = [];

    protected $casts = [
        'properties' => 'array',
        'event' => ActivityEvent::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public static function log(ActivityEvent $event, string $description, ?Model $subject = null, array $properties = []): self
    {
        return static::create([
            'user_id' => auth()->id() ?? 1,
            'event' => $event,
            'description' => $description,
            'subject_type' => $subject ? $subject->getMorphClass() : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'properties' => $properties,
        ]);
    }
}
