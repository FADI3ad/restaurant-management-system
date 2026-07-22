<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table as TableAttr;
use Illuminate\Database\Eloquent\Model;

#[Guarded(['id'])]
#[TableAttr('tables')]
class Table extends Model
{
    protected function casts(): array
    {
        return [
            'min_capacity' => 'integer',
            'max_capacity' => 'integer',
        ];
    }


    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
