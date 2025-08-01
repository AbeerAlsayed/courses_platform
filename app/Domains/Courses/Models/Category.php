<?php

namespace App\Domains\Courses\Models;

use App\Domains\Courses\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
