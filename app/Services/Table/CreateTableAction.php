<?php

namespace App\Services\Table;

use App\Models\Table;

class CreateTableAction
{
    public function __invoke(array $data): Table
    {
        return Table::create($data);
    }
}
