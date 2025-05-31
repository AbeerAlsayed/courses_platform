<?php

namespace App\Domains\Courses\Actions\Categories;

use App\Domains\Courses\Models\Category;

class DeleteCategoryAction
{
    public function execute(Category $category): void
    {
        $category->delete();
    }
}
