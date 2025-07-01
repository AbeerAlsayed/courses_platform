<?php

namespace App\Domains\Courses\Models;

use App\Domains\Auth\Models\Instructor;
use App\Domains\Auth\Models\User;
use App\Domains\Payments\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Domains\Courses\Enums\CourseStatus;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title', 'slug', 'description', 'category_id', 'instructor_id', 'status', 'price', 'duration',];

    protected $casts = [
        'status' => CourseStatus::class,
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function sections(){
        return $this->hasMany(Section::class);
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('course_image')->singleFile();
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps()->withPivot('enrolled_at');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
