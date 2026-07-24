<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Http\Requests\Subcategory\StoreSubcategoryRequest;
use App\Http\Requests\Subcategory\UpdateSubcategoryRequest;
use App\Services\Subcategory\CreateSubcategoryAction;
use App\Services\Subcategory\UpdateSubcategoryAction;
use App\Services\Subcategory\DeleteSubcategoryAction;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category.section')->orderBy('display_order')->get();

        return response()->json([
            'success' => true,
            'data' => $subcategories,
            'message' => 'Operation successful'
        ]);
    }

    public function store(StoreSubcategoryRequest $request, CreateSubcategoryAction $action)
    {
        $subcategory = $action($request->validated());

        return response()->json([
            'success' => true,
            'data' => $subcategory,
            'message' => 'تم إضافة الصنف الفرعي بنجاح'
        ], 201);
    }

    public function show(Subcategory $subcategory)
    {
        return response()->json([
            'success' => true,
            'data' => $subcategory->load(['category', 'items']),
            'message' => 'Operation successful'
        ]);
    }

    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory, UpdateSubcategoryAction $action)
    {
        $action($subcategory, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $subcategory->fresh(),
            'message' => 'تم تعديل الصنف الفرعي بنجاح'
        ]);
    }

    public function destroy(Subcategory $subcategory, DeleteSubcategoryAction $action)
    {
        $action($subcategory);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'تم حذف الصنف الفرعي بنجاح'
        ]);
    }
}
