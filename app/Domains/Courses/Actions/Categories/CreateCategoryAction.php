<?php

namespace App\Domains\Courses\Actions\Categories;

use App\Domains\Courses\DTOs\CategoryData;
use App\Domains\Courses\Models\Category;

class CreateCategoryAction
{
    public function execute(CategoryData $data): Category
    {
        return Category::create($data->toArray());
    }
}
