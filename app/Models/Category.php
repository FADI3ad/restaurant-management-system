<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;

#[Guarded(['id'])]
#[Table('categories')]
class Category extends Model
{
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }


    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
