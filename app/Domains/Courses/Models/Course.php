<?php

namespace App\Domains\Courses\Models;

use App\Domains\Auth\Models\Instructor;
use App\Domains\Auth\Models\User;
use App\Domains\Payments\Models\Payment;
use App\Domains\Courses\Enums\CourseStatus;
use Illuminate\Database\Eloquent\Model;
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
        'price' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    // جميع الدروس التابعة للكورس (سواء مرتبطة بقسم أم لا)
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    // الدروس غير المرتبطة بأي قسم
    public function standaloneLessons()
    {
        return $this->hasMany(Lesson::class)
            ->whereNull('section_id')
            ->orderBy('order');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('enrolled_at');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Media
    |--------------------------------------------------------------------------
    */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('course_image')->singleFile();
    }
}
