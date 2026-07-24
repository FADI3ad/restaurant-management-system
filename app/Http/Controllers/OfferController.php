<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Item;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::with('items')->orderBy('id', 'desc')->get();
        $items = Item::where('status', true)->orderBy('name')->get();

        // Calculate KPI Stats
        $stats = [
            'total_offers' => Offer::count(),
            'active_offers' => Offer::where('status', true)->count(),
            'average_discount' => Offer::avg('discount_price') ?: 0,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'offers' => $offers,
                'items' => $items,
                'stats' => $stats,
            ],
            'message' => 'Operation successful'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'discount_price' => 'required|numeric|min:0',
            'status' => 'nullable|boolean',
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'exists:items,id',
        ]);

        $status = $request->has('status') ? (bool) $request->input('status') : true;

        $offer = Offer::create([
            'name' => $validated['name'],
            'duration' => $validated['duration'],
            'discount_price' => $validated['discount_price'],
            'status' => $status,
        ]);

        $offer->items()->sync($validated['item_ids']);

        return response()->json([
            'success' => true,
            'data' => $offer->load('items'),
            'message' => 'تم إضافة العرض بنجاح'
        ], 201);
    }

    public function show(Offer $offer)
    {
        return response()->json([
            'success' => true,
            'data' => $offer->load('items'),
            'message' => 'Operation successful'
        ]);
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'discount_price' => 'required|numeric|min:0',
            'status' => 'nullable|boolean',
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'exists:items,id',
        ]);

        $status = $request->has('status') ? (bool) $request->input('status') : true;

        $offer->update([
            'name' => $validated['name'],
            'duration' => $validated['duration'],
            'discount_price' => $validated['discount_price'],
            'status' => $status,
        ]);

        $offer->items()->sync($validated['item_ids']);

        return response()->json([
            'success' => true,
            'data' => $offer->load('items'),
            'message' => 'تم تعديل العرض بنجاح'
        ]);
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'تم حذف العرض بنجاح'
        ]);
    }
}
