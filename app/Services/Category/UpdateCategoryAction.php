<?php

namespace App\Services\Category;

use App\Models\Category;

class UpdateCategoryAction
{
    public function __invoke(Category $modelInstance, array $data): bool
    {
        return $modelInstance->update($data);
    }
}