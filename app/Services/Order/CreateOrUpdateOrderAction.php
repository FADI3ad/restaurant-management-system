<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;

class CreateOrUpdateOrderAction
{
    /**
     * Create or update an order for a reservation.
     *
     * @param  int    $reservationId
     * @param  array  $cartItems   [{item_id, qty, notes?}, ...]
     * @param  string $notes       Order-level notes
     * @return Order
     */
    public function __invoke(int $reservationId, array $cartItems, string $notes = ''): Order
    {
        // Get or create the order for this reservation
        $order = Order::firstOrCreate(
            ['reservation_id' => $reservationId],
            [
                'number' => 'ORD-' . str_pad(Order::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'status' => 'pending',
                'notes'  => $notes,
                'total_amount' => 0,
            ]
        );

        // Update notes if provided
        $order->notes = $notes;
        $order->save();

        // Sync the cart items — rebuild order_items from scratch
        $order->items()->delete();

        $total = 0;

        foreach ($cartItems as $cartItem) {
            $item = Item::findOrFail($cartItem['item_id']);
            $qty  = max(1, (int) ($cartItem['qty'] ?? 1));
            $subtotal = $item->price * $qty;

            OrderItem::create([
                'order_id'   => $order->id,
                'item_id'    => $item->id,
                'quantity'   => $qty,
                'unit_price' => $item->price,
                'subtotal'   => $subtotal,
                'notes'      => $cartItem['notes'] ?? null,
            ]);

            $total += $subtotal;
        }

        $order->total_amount = $total;
        $order->save();

        return $order->fresh(['items.item']);
    }
}
