<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Guarded(['id'])]
#[Table('orders')]
class Order extends Model
{
    protected function casts(): array
    {
        return [
            'subtotal'     => 'decimal:2',
            'tax'          => 'decimal:2',
            'discount'     => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function recalcTotal(): void
    {
        $this->subtotal     = $this->items()->sum('subtotal');
        $this->tax          = round($this->subtotal * 0.15, 2);
        $this->total_amount = $this->subtotal + $this->tax - $this->discount;
        $this->save();
    }

    public static function generateNumber(): string
    {
        $prefix = 'ORD-' . now()->format('ymd');
        $last   = static::where('number', 'like', $prefix . '-%')
                        ->orderByDesc('id')
                        ->value('number');

        $seq = $last ? ((int) substr($last, -3)) + 1 : 1;
        return $prefix . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
}
