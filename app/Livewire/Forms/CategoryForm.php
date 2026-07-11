<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Livewire\Form;

class CategoryForm extends Form
{
    public ?Category $category = null;

    public ?string $name = null;

    public ?int $display_order = null;

    public ?string $description = null;

    public bool $status = true;

    public ?int $section_id = null;

    public function setData(Category $category)
    {
        $this->category = $category;

        $this->name = $category->name;

        $this->display_order = $category->display_order;

        $this->description = $category->description;

        $this->status = $category->status;

        $this->section_id = $category->section_id;
    }
}
