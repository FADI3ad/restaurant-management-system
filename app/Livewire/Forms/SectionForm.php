<?php

namespace App\Livewire\Forms;

use App\Models\Section;
use Livewire\Form;

class SectionForm extends Form
{
    public ?Section $section = null;

    public ?string $name = null;

    public ?int $display_order = null;

    public ?string $description = null;

    public bool $status = true;



    public function setData(Section $section)
    {
        $this->section = $section;

        $this->name = $section->name;

        $this->display_order = $section->display_order;

        $this->description = $section->description;

        $this->status = $section->status;
    }
}
