<?php

namespace App\Domains\Cources\Actions;

use App\Domains\Cources\Models\Category;
use App\Domains\Cources\DTOs\CategoryData;

class CreateCategoryAction
{
    public function execute(CategoryData $data): Category
    {
        return Category::create($data->toArray());
    }
}
