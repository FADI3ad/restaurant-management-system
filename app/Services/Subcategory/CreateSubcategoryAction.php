<?php 

namespace App\Services\Subcategory;

use App\Models\Subcategory;

class CreateSubcategoryAction
{
    public function __invoke(array $data): Subcategory
    {
        return Subcategory::create($data);
    }
}
