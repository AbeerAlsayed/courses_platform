<?php

namespace App\Domains\Cources\Actions;

use App\Domains\Cources\Models\Category;
use App\Domains\Cources\DTOs\CategoryData;

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
