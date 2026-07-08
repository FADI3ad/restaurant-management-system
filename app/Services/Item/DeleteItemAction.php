<?php

namespace App\Services\Item;

use App\Models\Item;

class DeleteItemAction
{
    public function __invoke(Item $modelInstance): ?bool
    {
        return $modelInstance->delete();
    }
}