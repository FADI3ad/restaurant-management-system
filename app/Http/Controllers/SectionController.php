<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function index()
    {
        return view('section');
    }


    public function create()
    {
        return view('section');
    }


    public function store(StoreSectionRequest $request)
    {
        Section::create($request->validated());
        return to_route('sections.index')->with('success', 'Section created successfully.');
    }


    public function show(Section $section)
    {
        //
    }

    public function edit(Section $section)
    {
        //
    }


    public function update(Request $request, Section $section)
    {
        //
    }


    public function destroy(Section $section)
    {
        //
    }
}
