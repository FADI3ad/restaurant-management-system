<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::where('status', true)
            ->with(['categories' => function ($cQuery) {
                $cQuery->where('status', true)
                    ->orderBy('display_order')
                    ->with(['subcategories' => function ($scQuery) {
                        $scQuery->where('status', true)
                            ->orderBy('display_order')
                            ->with(['items' => function ($iQuery) {
                                $iQuery->where('status', true)
                                    ->orderBy('display_order');
                            }]);
                    }]);
            }])
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sections,
            'message' => 'Operation successful'
        ]);
    }
}
