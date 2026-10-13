<?php

namespace App\Models;

use App\Enums\FeedbackCategory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'category',
        'title',
        'message',
        'rating',
        'email',
    ];

    protected $casts = [
        'category' => FeedbackCategory::class,
        'rating' => 'integer',
    ];
}
