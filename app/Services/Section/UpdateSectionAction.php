<?php

namespace App\Services\Section;

use App\Models\Section;

class UpdateSectionAction
{
    public function __invoke(Section $modelInstance, array $data): bool
    {
        return $modelInstance->update($data);
    }
}