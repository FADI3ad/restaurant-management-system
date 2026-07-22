<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Guarded(['id'])]
#[Table('items')]
class Item extends Model
{
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }
            
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
