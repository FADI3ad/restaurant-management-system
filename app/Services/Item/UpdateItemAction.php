<?php

namespace App\Services\Item;

use App\Models\Item;

class UpdateItemAction
{
    public function __invoke(Item $modelInstance, array $data): bool
    {
        return $modelInstance->update($data);
    }
}