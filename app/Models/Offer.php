<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Guarded(['id'])]
#[Table('offers')]
class Offer extends Model
{
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'discount_price' => 'decimal:2',
        ];
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'offer_item');
    }
}
