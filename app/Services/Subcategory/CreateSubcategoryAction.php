<?php

namespace App\Services\Subcategory;

use App\Models\Subcategory;
use Illuminate\Support\Arr;

class CreateSubcategoryAction
{
    public function __invoke(array $data): Subcategory
    {
        return Subcategory::create(Arr::except($data, ['section_id']));
    }
}
