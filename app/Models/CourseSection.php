<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSection extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'position',
        'course_id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function sectionContents(): HasMany
    {
        return $this->hasMany(SectionContent::class);
    }
}
