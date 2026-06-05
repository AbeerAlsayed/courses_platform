<?php

namespace App\Domains\Courses\Models;

use App\Domains\Auth\Models\Instructor;
use App\Domains\Auth\Models\User;
use App\Domains\Payments\Models\Payment;
use App\Domains\Courses\Enums\CourseStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'instructor_id',
        'status',
        'price',
        'duration',
        'stripe_price_id',
        'stripe_product_id',
    ];

    protected $casts = [
        'status' => CourseStatus::class,
        'price'  => 'decimal:2',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    /** All lessons belonging to this course (with or without a section) */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /** Lessons not attached to any section */
    public function standaloneLessons(): HasMany
    {
        return $this->hasMany(Lesson::class)
            ->whereNull('section_id')
            ->orderBy('order');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('enrolled_at');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // -------------------------------------------------------------------------
    // Media
    // -------------------------------------------------------------------------

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('course_image')->singleFile();
    }
}
