<?php 

namespace App\Services\Item;

use App\Models\Item;

class CreateItemAction
{
    public function __invoke(array $data): Item
    {
        return Item::create($data);
    }
}
