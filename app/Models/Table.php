<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'table_number',
        'type',
        'min_capacity',
        'max_capacity',
        'status',
        'notes',
    ];


    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
