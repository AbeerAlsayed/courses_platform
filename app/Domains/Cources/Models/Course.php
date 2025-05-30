<?php

namespace App\Domains\Cources\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // Course.php
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

// Section.php
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

// Lesson.php
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

}
