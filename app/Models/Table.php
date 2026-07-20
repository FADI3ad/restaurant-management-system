<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'number',
        'type',
        'min_capacity',
        'max_capacity',
        'location',
        'status',
        'notes',
    ];


    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
