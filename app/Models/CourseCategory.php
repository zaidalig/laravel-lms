<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseCategory extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'slug', 'description', 'status'];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
