<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    use LogsActivity;

    protected $fillable = ['course_id', 'user_id', 'enrolled_at', 'status'];

    protected function casts(): array
    {
        return [
            'enrolled_at' => 'date',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function progressPercent(): int
    {
        $total = $this->course->lessons()->count();

        if ($total === 0) {
            return 0;
        }

        return (int) round($this->completions()->count() / $total * 100);
    }
}
