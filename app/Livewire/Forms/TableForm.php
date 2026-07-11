<?php

namespace App\Livewire\Forms;

use App\Models\Table;
use Livewire\Form;

class TableForm extends Form
{
    public ?Table $table = null;

    public ?string $table_number = null;

    public ?string $type = null;

    public ?int $min_capacity = null;

    public ?int $max_capacity = null;

    public ?string $status = null;

    public ?string $notes = null;

    public function setData(Table $table)
    {
        $this->table = $table;

        $this->table_number = $table->table_number;

        $this->type = $table->type;

        $this->min_capacity = $table->min_capacity;

        $this->max_capacity = $table->max_capacity;

        $this->status = $table->status;

        $this->notes = $table->notes;
    }
}
