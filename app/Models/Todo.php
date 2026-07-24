<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'text',
        'is_completed',
        'priority',
        'due_text',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];
}
