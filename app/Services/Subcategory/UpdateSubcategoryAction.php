<?php

namespace App\Services\Subcategory;

use App\Models\Subcategory;

class UpdateSubcategoryAction
{
    public function __invoke(Subcategory $modelInstance, array $data): bool
    {
        return $modelInstance->update($data);
    }
}