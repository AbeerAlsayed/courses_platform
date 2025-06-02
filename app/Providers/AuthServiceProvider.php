<?php

namespace App\Providers;

use App\Domains\Courses\Models\Section;
use App\Policies\SectionPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Domains\Courses\Models\Course;
use App\Policies\CoursePolicy;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Section::class, SectionPolicy::class);
    }
}
