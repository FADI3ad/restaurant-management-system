<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\Category\CreateCategoryAction;
use App\Services\Category\UpdateCategoryAction;
use App\Services\Category\DeleteCategoryAction;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('section')->orderBy('display_order')->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Operation successful'
        ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryAction $action)
    {
        $category = $action($request->validated());

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'تم إضافة الصنف بنجاح'
        ], 201);
    }

    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'data' => $category->load(['section', 'subcategories']),
            'message' => 'Operation successful'
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action)
    {
        $action($category, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $category->fresh(),
            'message' => 'تم تعديل الصنف بنجاح'
        ]);
    }

    public function destroy(Category $category, DeleteCategoryAction $action)
    {
        $action($category);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'تم حذف الصنف بنجاح'
        ]);
    }
}
