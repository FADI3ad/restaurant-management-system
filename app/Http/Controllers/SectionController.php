<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Http\Requests\Section\StoreSectionRequest;
use App\Http\Requests\Section\UpdateSectionRequest;
use App\Services\Section\CreateSectionAction;
use App\Services\Section\UpdateSectionAction;
use App\Services\Section\DeleteSectionAction;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::withCount('categories')->orderBy('display_order')->get();
        
        return response()->json([
            'success' => true,
            'data' => $sections,
            'message' => 'Operation successful'
        ]);
    }

    public function store(StoreSectionRequest $request, CreateSectionAction $action)
    {
        $section = $action($request->validated());

        return response()->json([
            'success' => true,
            'data' => $section,
            'message' => 'تم إضافة القسم بنجاح'
        ], 201);
    }

    public function show(Section $section)
    {
        return response()->json([
            'success' => true,
            'data' => $section->load('categories'),
            'message' => 'Operation successful'
        ]);
    }

    public function update(UpdateSectionRequest $request, Section $section, UpdateSectionAction $action)
    {
        $action($section, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $section->fresh(),
            'message' => 'تم تعديل القسم بنجاح'
        ]);
    }

    public function destroy(Section $section, DeleteSectionAction $action)
    {
        $action($section);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'تم حذف القسم بنجاح'
        ]);
    }
}
