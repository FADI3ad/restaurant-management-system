<?php

namespace App\Services\Section;

use App\Models\Section;

class CreateSectionAction
{
    public function __invoke(array $data): Section
    {
        return Section::create($data);
    }
}
