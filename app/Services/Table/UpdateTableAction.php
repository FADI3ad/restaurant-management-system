<?php

namespace App\Services\Table;

use App\Models\Table;

class UpdateTableAction
{
    public function __invoke(Table $table, array $data): Table
    {
        $table->update($data);
        return $table;
    }
}
