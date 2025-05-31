<?php

namespace Database\Seeders;

use App\Domains\Courses\Enums\CourseStatus;
use Illuminate\Database\Seeder;
use App\Domains\Courses\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {

        $instructorId = 1;
        $categoryId = 1;

        $courses = [
            [
                'title' => 'Laravel for Beginners',
                'slug' => 'laravel-for-beginners',
                'description' => 'Learn the basics of Laravel framework.',
                'category_id' => $categoryId,
                'instructor_id' => $instructorId,
                'status' => CourseStatus::Approved->value,
                'price' => 49.99,
                'duration' => 120,
            ],
            [
                'title' => 'Mastering Vue.js',
                'slug' => 'mastering-vuejs',
                'description' => 'Deep dive into Vue.js for frontend development.',
                'category_id' => $categoryId,
                'instructor_id' => $instructorId,
                'status' => CourseStatus::Approved->value,
                'price' => 59.99,
                'duration' => 150,
            ],
            [
                'title' => 'Advanced PHP Techniques',
                'slug' => 'advanced-php-techniques',
                'description' => 'Explore advanced features of PHP.',
                'category_id' => $categoryId,
                'instructor_id' => $instructorId,
                'status' => CourseStatus::Approved->value,
                'price' => 39.99,
                'duration' => 100,
            ],
            [
                'title' => 'Database Design Essentials',
                'slug' => 'database-design-essentials',
                'description' => 'Understand the core of database design.',
                'category_id' => $categoryId,
                'instructor_id' => $instructorId,
                'status' => CourseStatus::Approved->value,
                'price' => 29.99,
                'duration' => 90,
            ],
            [
                'title' => 'Building APIs with Laravel',
                'slug' => 'building-apis-with-laravel',
                'description' => 'Create powerful APIs using Laravel.',
                'category_id' => $categoryId,
                'instructor_id' => $instructorId,
                'status' => CourseStatus::Approved->value,
                'price' => 54.99,
                'duration' => 130,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
