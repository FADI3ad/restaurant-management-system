<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Order\CreateOrUpdateOrderAction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $date = $request->get('date');

        $query = Order::with(['reservation.table', 'items.item']);

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Operation successful'
        ]);
    }

    public function store(Request $request, CreateOrUpdateOrderAction $action)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'cart_items' => 'required|array|min:1',
            'cart_items.*.item_id' => 'required|exists:items,id',
            'cart_items.*.qty' => 'required|integer|min:1',
            'cart_items.*.notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $order = $action(
            (int) $validated['reservation_id'],
            $validated['cart_items'],
            $validated['notes'] ?? ''
        );

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'تم إنشاء/تحديث الطلب بنجاح'
        ], 201);
    }

    public function show(Order $order)
    {
        return response()->json([
            'success' => true,
            'data' => $order->load(['reservation.table', 'items.item']),
            'message' => 'Operation successful'
        ]);
    }

    public function update(Request $request, Order $order, CreateOrUpdateOrderAction $action)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,preparing,ready,completed,cancelled',
            'cart_items' => 'nullable|array',
            'cart_items.*.item_id' => 'required_with:cart_items|exists:items,id',
            'cart_items.*.qty' => 'required_with:cart_items|integer|min:1',
            'cart_items.*.notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['cart_items'])) {
            $order = $action(
                (int) $order->reservation_id,
                $validated['cart_items'],
                $validated['notes'] ?? $order->notes ?? ''
            );
        }

        if (isset($validated['status'])) {
            $order->status = $validated['status'];
            $order->save();
        }

        return response()->json([
            'success' => true,
            'data' => $order->load(['reservation.table', 'items.item']),
            'message' => 'تم تحديث الطلب بنجاح'
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'تم حذف الطلب بنجاح'
        ]);
    }
}
