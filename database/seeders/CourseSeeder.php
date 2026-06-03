<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Courses\Models\Course;
use App\Domains\Courses\Enums\CourseStatus;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Laravel Beginner',
                'slug' => 'laravel-beginner',
                'description' => 'Learn the basics of Laravel framework.',
                'price' => 11.00,
                'duration' => 120,
            ],
            [
                'title' => 'Livewire Essentials',
                'slug' => 'livewire-essentials',
                'description' => 'Deep dive into Livewire for Laravel.',
                'price' => 12.00,
                'duration' => 150,
            ],
            [
                'title' => 'Laravel APIs',
                'slug' => 'laravel-apis',
                'description' => 'Build RESTful APIs with Laravel.',
                'price' => 9.00,
                'duration' => 100,
            ],
        ];

        foreach ($courses as $course) {

            Course::firstOrCreate(
                [
                    'slug' => $course['slug'],
                ],
                [
                    'title' => $course['title'],
                    'description' => $course['description'],
                    'category_id' => 1,
                    'instructor_id' => 1,
                    'status' => CourseStatus::Approved,
                    'price' => $course['price'],
                    'duration' => $course['duration'],

                    'stripe_product_id' => null,
                    'stripe_price_id' => null,
                ]
            );
        }
    }
}
