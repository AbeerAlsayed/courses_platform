<?php

namespace App\Http\Controllers\Api;


use App\Domains\Courses\Actions\Categories\CreateCategoryAction;
use App\Domains\Courses\Actions\Categories\DeleteCategoryAction;
use App\Domains\Courses\Actions\Categories\UpdateCategoryAction;
use App\Domains\Courses\DTOs\CategoryData;
use App\Domains\Courses\Http\Resources\CategoryResource;
use App\Domains\Courses\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return successResponse('Category list', CategoryResource::collection($categories));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = app(CreateCategoryAction::class)->execute(
            CategoryData::fromArray($request->validated())
        );

        return successResponse('Category created successfully', new CategoryResource($category));
    }

    public function show(Category $category)
    {
        return successResponse('Category details', new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updatedCategory = app(UpdateCategoryAction::class)->execute(
            $category,
            CategoryData::fromArray($request->validated())
        );

        return successResponse('Category updated successfully', new CategoryResource($updatedCategory));
    }

    public function destroy(Category $category)
    {
        app(DeleteCategoryAction::class)->execute($category);
        return successResponse('Category deleted successfully');
    }
}
