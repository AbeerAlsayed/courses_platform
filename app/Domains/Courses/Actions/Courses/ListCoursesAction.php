<?php

namespace App\Domains\Courses\Actions\Courses;

use App\Domains\Courses\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListCoursesAction
{

    public function execute(): LengthAwarePaginator
    {
        return Course::query()
            ->with([
                'category',
                'instructor.user',
            ])
            ->latest()
            ->paginate(10);
    }}
