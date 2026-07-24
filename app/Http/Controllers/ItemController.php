<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Services\Item\CreateItemAction;
use App\Services\Item\UpdateItemAction;
use App\Services\Item\DeleteItemAction;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('subcategory.category')->orderBy('display_order')->get();

        return response()->json([
            'success' => true,
            'data' => $items,
            'message' => 'Operation successful'
        ]);
    }

    public function store(StoreItemRequest $request, CreateItemAction $action)
    {
        $item = $action($request->validated());

        return response()->json([
            'success' => true,
            'data' => $item,
            'message' => 'تم إضافة الوجبة بنجاح'
        ], 201);
    }

    public function show(Item $item)
    {
        return response()->json([
            'success' => true,
            'data' => $item->load('subcategory'),
            'message' => 'Operation successful'
        ]);
    }

    public function update(UpdateItemRequest $request, Item $item, UpdateItemAction $action)
    {
        $action($item, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $item->fresh(),
            'message' => 'تم تعديل الوجبة بنجاح'
        ]);
    }

    public function destroy(Item $item, DeleteItemAction $action)
    {
        $action($item);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'تم حذف الوجبة بنجاح'
        ]);
    }
}
