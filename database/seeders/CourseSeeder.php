<?php

namespace Database\Seeders;

use App\Domains\Courses\Enums\CourseStatus;
use Illuminate\Database\Seeder;
use App\Domains\Courses\Models\Course;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // إعداد مفتاح Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        $instructorId = 1;
        $categoryId = 1;

        $courses = [
            [
                'title' => 'Laravel Beginner',
                'slug' => 'laravel-beginner',
                'description' => 'Learn the basics of Laravel framework.',
                'price' => 11,
                'duration' => 120,
            ],
            [
                'title' => 'Livewire Essentials',
                'slug' => 'livewire-essentials',
                'description' => 'Deep dive into Livewire for Laravel.',
                'price' => 12,
                'duration' => 150,
            ],
            [
                'title' => 'Laravel APIs',
                'slug' => 'laravel-apis',
                'description' => 'Build RESTful APIs with Laravel.',
                'price' => 9,
                'duration' => 100,
            ],
        ];

        foreach ($courses as $course) {
            // إنشاء المنتج في Stripe
            $product = Product::create([
                'name' => $course['title'],
            ]);

            // إنشاء السعر في Stripe
            $price = Price::create([
                'product' => $product->id,
                'unit_amount' => intval($course['price'] * 100), // Stripe يتعامل مع العملات بالـ "سنت"
                'currency' => 'usd',
            ]);

            // إنشاء الكورس في قاعدة البيانات
            Course::create([
                'title' => $course['title'],
                'slug' => $course['slug'],
                'description' => $course['description'],
                'category_id' => $categoryId,
                'instructor_id' => $instructorId,
                'status' => CourseStatus::Approved->value,
                'price' => $course['price'],
                'duration' => $course['duration'],
                'stripe_product_id' => $product->id,
                'stripe_price_id' => $price->id,
            ]);
        }
    }
}
