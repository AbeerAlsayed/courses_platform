<?php

namespace App\Domains\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Lesson extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'section_id', 'title', 'description', 'order', 'duration', 'is_free',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('lesson_media')->singleFile();
    }


}
