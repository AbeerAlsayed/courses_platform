<?php

namespace App\Domains\Courses\Actions\Categories;

use App\Domains\Courses\DTOs\CategoryData;
use App\Domains\Courses\Models\Category;

class UpdateCategoryAction
{
    public function execute(Category $category, CategoryData $data): Category
    {
        $category->update([
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
        ]);

        return $category;
    }
}
