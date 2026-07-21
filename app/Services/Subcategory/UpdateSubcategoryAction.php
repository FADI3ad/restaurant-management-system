<?php

namespace App\Services\Subcategory;

use App\Models\Subcategory;
use Illuminate\Support\Arr;

class UpdateSubcategoryAction
{
    public function __invoke(Subcategory $modelInstance, array $data): bool
    {
        return $modelInstance->update(Arr::except($data, ['section_id']));
    }
}