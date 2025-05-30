<?php

namespace Database\Seeders;

use App\Domains\Cources\Models\Category;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development', 'description' => 'All about web development'],
            ['name' => 'Data Science', 'description' => 'Learn about data analysis and machine learning'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}
