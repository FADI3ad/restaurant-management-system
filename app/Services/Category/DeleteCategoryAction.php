<?php

namespace App\Services\Category;

use App\Models\Category;

class DeleteCategoryAction
{
    public function __invoke(Category $modelInstance): ?bool
    {
        return $modelInstance->delete();
    }
}