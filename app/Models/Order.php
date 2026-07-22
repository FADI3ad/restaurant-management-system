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
            'total_amount' => 'decimal:2',
        ];
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function recalcTotal(): void
    {
        $this->total_amount = $this->items()->sum('subtotal');
        $this->save();
    }
}
