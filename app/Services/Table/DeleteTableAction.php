<?php

namespace App\Services\Table;

use App\Models\Table;

class DeleteTableAction
{
    public function __invoke(Table $table): void
    {
        $table->delete();
    }
}
