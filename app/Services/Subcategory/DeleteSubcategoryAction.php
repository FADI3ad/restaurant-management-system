<?php

namespace App\Services\Subcategory;

use App\Models\Subcategory;

class DeleteSubcategoryAction
{
    public function __invoke(Subcategory $modelInstance): ?bool
    {
        return $modelInstance->delete();
    }
}