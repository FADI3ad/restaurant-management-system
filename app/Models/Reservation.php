<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'number_of_guests',
        'code',
        'start_time',
        'duration',
        'date',
        'status',
        'table_id',
    ];

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
}
