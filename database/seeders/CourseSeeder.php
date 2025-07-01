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
                'title' => 'laravel Beginner',
                'slug' => 'laravel-Beginner',
                'description' => 'Learn the basics of Laravel framework.',
                'category_id' => $categoryId,
                'instructor_id' => $instructorId,
                'status' => CourseStatus::Approved->value,
                'price' => 11,
                'duration' => 120,
                'stripe_price_id' =>'price_1RZZQdPKLto9bYECSwtBx4Zm',
            ],
            [
                'title' => 'livewere',
                'slug' => 'livewere',
                'description' => 'Deep dive into Vue.js for frontend development.',
                'category_id' => $categoryId,
                'instructor_id' => $instructorId,
                'status' => CourseStatus::Approved->value,
                'price' => 12,
                'duration' => 150,
                'stripe_price_id' =>'price_1RZZRKPKLto9bYECeohdivnv',
            ],
            [
                'title' => 'laravel APIs',
                'slug' => 'laravel-APIs',
                'description' => 'Explore advanced features of PHP.',
                'category_id' => $categoryId,
                'instructor_id' => $instructorId,
                'status' => CourseStatus::Approved->value,
                'price' => 9,
                'duration' => 100,
                'stripe_price_id' =>'price_1RZZQBPKLto9bYECdL5Og7lZ',
            ],

        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
