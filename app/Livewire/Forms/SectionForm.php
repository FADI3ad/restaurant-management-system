<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class SectionForm extends Form
{
    public ?string $name = null;

    public ?int $display_order = null;

    public ?string $description = null;

    public bool $status = true;

}
