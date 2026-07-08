<?php

namespace App\Services\Section;

use App\Models\Section;

class DeleteSectionAction
{
    public function __invoke(Section $modelInstance): ?bool
    {
        return $modelInstance->delete();
    }
}