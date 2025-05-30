<?php

namespace App\Domains\Cources\Actions;

use App\Domains\Cources\Models\Category;

class DeleteCategoryAction
{
    public function execute(Category $category): void
    {
        $category->delete();
    }
}
