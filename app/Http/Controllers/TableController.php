<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Http\Requests\Table\StoreTableRequest;
use App\Http\Requests\Table\UpdateTableRequest;
use App\Services\Table\CreateTableAction;
use App\Services\Table\UpdateTableAction;
use App\Services\Table\DeleteTableAction;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('number')->get();

        return response()->json([
            'success' => true,
            'data' => $tables,
            'message' => 'Operation successful'
        ]);
    }

    public function store(StoreTableRequest $request, CreateTableAction $action)
    {
        $table = $action($request->validated());

        return response()->json([
            'success' => true,
            'data' => $table,
            'message' => 'تم إضافة الطاولة بنجاح'
        ], 201);
    }

    public function show(Table $table)
    {
        return response()->json([
            'success' => true,
            'data' => $table->load('reservations'),
            'message' => 'Operation successful'
        ]);
    }

    public function update(UpdateTableRequest $request, Table $table, UpdateTableAction $action)
    {
        $action($table, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $table->fresh(),
            'message' => 'تم تعديل الطاولة بنجاح'
        ]);
    }

    public function destroy(Table $table, DeleteTableAction $action)
    {
        $action($table);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'تم حذف الطاولة بنجاح'
        ]);
    }
}
