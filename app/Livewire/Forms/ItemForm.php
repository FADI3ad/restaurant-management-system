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

    public $image = null;

    public function setData(Item $item)
    {
        $this->item = $item;

        $this->name = $item->name;

        $this->price = $item->price;

        $this->display_order = $item->display_order;

        $this->description = $item->description;

        $this->status = (bool) $item->status;

        $this->subcategory_id = $item->subcategory_id;

        $item->loadMissing('subcategory.category');
        if ($item->subcategory) {
            $this->category_id = $item->subcategory->category_id;
            if ($item->subcategory->category) {
                $this->section_id = $item->subcategory->category->section_id;
            }
        }
        
        $this->image = null;
    }
}
