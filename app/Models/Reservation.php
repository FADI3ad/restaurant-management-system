<?php

namespace App\Models;

use App\Models\Table;
use Illuminate\Database\Eloquent\Model;


class Reservation extends Model
{
    protected $fillable = [
        'number',
        'customer_name',
        'customer_phone',
        'number_of_guests',
        'start_time',
        'duration',
        'date',
        'notes',
        'status',
        'table_id',
    ];
    protected function casts(): array
    {
        return [
            'number_of_guests' => 'integer',
            'duration' => 'integer',
        ];
    }

    public function getStartTimeAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('H:i') : null;
    }

    public function getEndTimeAttribute()
    {
        if (!$this->start_time) {
            return null;
        }

        return \Carbon\Carbon::createFromFormat('H:i', $this->start_time, 'UTC')
            ->addMinutes((int) ($this->duration ?? 60))
            ->format('H:i');
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
