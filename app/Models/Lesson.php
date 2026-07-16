<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use LogsActivity;

    protected $fillable = ['course_id', 'title', 'content', 'video_url', 'position', 'duration_minutes'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
