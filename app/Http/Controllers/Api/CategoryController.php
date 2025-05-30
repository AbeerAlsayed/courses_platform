<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Domains\Cources\Models\Category;
use App\Domains\Cources\DTOs\CategoryData;
use App\Domains\Cources\Actions\CreateCategoryAction;
use App\Domains\Cources\Actions\UpdateCategoryAction;
use App\Domains\Cources\Actions\DeleteCategoryAction;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;

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
