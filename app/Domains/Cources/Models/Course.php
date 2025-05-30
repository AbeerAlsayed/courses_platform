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

}
