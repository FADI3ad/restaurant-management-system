<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index()
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
                                    ->orderBy('display_order')
                                    ->select('id', 'name', 'price', 'description', 'subcategory_id');
                            }]);
                    }]);
            }])
            ->orderBy('display_order')
            ->get(['id', 'name']);

        $allItems = Item::where('status', true)
            ->select('id', 'name', 'price', 'description', 'subcategory_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'sections' => $sections,
                'allItems' => $allItems,
            ],
            'message' => 'Operation successful'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart'           => 'required|array|min:1',
            'cart.*.id'      => 'required|exists:items,id',
            'cart.*.qty'     => 'required|integer|min:1',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cash,card',
            'discount'       => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $cart          = $request->cart;
            $discount      = (float) ($request->discount ?? 0);
            $paymentMethod = $request->payment_method;
            $phone         = $request->customer_phone;

            $itemIds    = collect($cart)->pluck('id');
            $dbItems    = Item::whereIn('id', $itemIds)->get()->keyBy('id');
            $subtotal   = 0;

            foreach ($cart as $cartItem) {
                $item      = $dbItems[$cartItem['id']];
                $subtotal += $item->price * $cartItem['qty'];
            }

            $tax         = round($subtotal * 0.15, 2);
            $totalAmount = $subtotal + $tax - $discount;

            $order = Order::create([
                'number'         => Order::generateNumber(),
                'customer_phone' => $phone,
                'type'           => 'takeaway',
                'payment_method' => $paymentMethod,
                'status'         => 'paid',
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'discount'       => $discount,
                'total_amount'   => $totalAmount,
            ]);

            foreach ($cart as $cartItem) {
                $item     = $dbItems[$cartItem['id']];
                $qty      = (int) $cartItem['qty'];
                $subTotal = round($item->price * $qty, 2);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'item_id'    => $item->id,
                    'quantity'   => $qty,
                    'unit_price' => $item->price,
                    'subtotal'   => $subTotal,
                ]);
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'data' => [
                    'order_number' => $order->number,
                    'total'        => $order->total_amount,
                    'order'        => $order->load('items.item'),
                ],
                'message' => 'تم حفظ الطلب بنجاح'
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
