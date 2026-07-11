<?php

namespace App\Livewire\Forms;

use App\Models\Item;
use Livewire\Form;

class ItemForm extends Form
{
    public ?Item $item = null;

    public ?string $name = null;

    public ?float $price = null;

    public ?int $display_order = null;

    public ?string $description = null;

    public bool $status = true;

    public ?int $section_id = null;

    public ?int $category_id = null;

    public ?int $subcategory_id = null;

    public function setData(Item $item)
    {
        $this->item = $item;

        $this->name = $item->name;

        $this->price = $item->price;

        $this->display_order = $item->display_order;

        $this->description = $item->description;

        $this->status = $item->status;

        $this->section_id = $item->section_id;

        $this->category_id = $item->category_id;

        $this->subcategory_id = $item->subcategory_id;
    }
}
