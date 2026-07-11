<?php

namespace App\Livewire\Forms;

use App\Models\Subcategory;
use Livewire\Form;

class SubcategoryForm extends Form
{
    public ?Subcategory $subcategory = null;

    public ?string $name = null;

    public ?int $display_order = null;

    public ?string $description = null;

    public bool $status = true;

    public ?int $section_id = null;

    public ?int $category_id = null;

    public function setData(Subcategory $subcategory)
    {
        $this->subcategory = $subcategory;

        $this->name = $subcategory->name;

        $this->display_order = $subcategory->display_order;

        $this->description = $subcategory->description;

        $this->status = $subcategory->status;

        $this->section_id = $subcategory->section_id;

        $this->category_id = $subcategory->category_id;
    }
}
