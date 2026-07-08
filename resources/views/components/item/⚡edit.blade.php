<?php

use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Item;
use App\Services\Item\UpdateItemAction;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $item = '';

    public $name = '';
    public $section_id = '';
    public $category_id = '';
    public $subcategory_id = '';
    public $price = '';
    public $display_order = 0;
    public $description = '';
    public $status = 1;

    #[On('edit-item-details')]
    public function getItemDetails($id)
    {
        $item = Item::findOrfail($id);
        $this->item = $item;
        $this->setData();
    }

    public function setData()
    {
        $this->name = $this->item->name;
        $this->section_id = $this->item->section_id;
        $this->category_id = $this->item->category_id;
        $this->subcategory_id = $this->item->subcategory_id;
        $this->price = $this->item->price;
        $this->display_order = $this->item->display_order;
        $this->description = $this->item->description;
        $this->status = (int) $this->item->status;
    }

    public function update(UpdateItemAction $updateItem)
    {
        $validated = $this->validate(\App\Http\Requests\Item\UpdateItemRequest::rulesArray($this->item->id ?? null));

        $updateItem($this->item, $validated);
        $this->dispatch('close-edit-modal');
        $this->dispatch('item-changed');
    }

    public function sections()
    {
        return Section::all();
    }

    public function categories($id)
    {
        if (!$id) {
            return [];
        }
        return Category::where('section_id', $id)->get();
    }

    public function subcategories($id)
    {
        if (!$id) {
            return [];
        }
        return Subcategory::where('category_id', $id)->get();
    }
};
?>


<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل الوجبة" />

        <form id="form-edit" wire:submit.prevent="update">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم الوجبة <span class="req">*</span></label>
                    <input type="text" class="input" id="edit-name" required value="{{ $this->name }}"
                        wire:model="name">
                    @error('name')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">السعر <span class="req">*</span></label>
                    <input type="number" step="0.01" class="input" id="edit-price" required
                        value="{{ $this->price }}" wire:model="price">
                    @error('price')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">القسم</label>
                    <select wire:model.live="section_id" class="select" id="edit-section">
                        <option value="" wire:click="section_id = null">اختر القسم</option>
                        @foreach ($this->sections() as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('section_id')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>
                @if ($this->section_id)
                    <div class="field">
                        <label class="field-label">الصنف الرئيسي</label>
                        <select wire:model.live="category_id" class="select" id="edit-category">
                            <option value="" wire:click="category_id = null">اختر الصنف الرئيسي</option>
                            @foreach ($this->categories($this->section_id) as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14"
                                    height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg> {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                @if ($this->category_id)
                    <div class="field">
                        <label class="field-label">الصنف الفرعي</label>
                        <select wire:model="subcategory_id" class="select" id="edit-subcategory">
                            <option value="" wire:click="subcategory_id = null">اختر الصنف الفرعي</option>
                            @foreach ($this->subcategories($this->category_id) as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                        @error('subcategory_id')
                            <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14"
                                    height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg> {{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="field">
                    <label class="field-label">ترتيب العرض</label>
                    <input type="number" class="input" id="edit-order" min="0"
                        value="{{ $this->display_order }}" wire:model="display_order">
                    @error('display_order')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">الوصف</label>
                    <textarea class="textarea" id="edit-description" wire:model="description">{{ $this->description }}</textarea>
                    @error('description')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">حالة التنشيط</label>
                    <select class="select" id="edit-status" wire:model="status">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                    @error('status')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="editOpen = false">إلغاء</button>
                <button type="submit" class="btn btn--primary" @close-edit-modal.window="editOpen = false">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
