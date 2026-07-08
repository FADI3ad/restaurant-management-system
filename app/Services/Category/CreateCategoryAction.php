<?php 

namespace App\Services\Category;

use App\Models\Category;

class CreateCategoryAction
{
    public function __invoke(array $data): Category
    {
        return Category::create($data);
    }
}
