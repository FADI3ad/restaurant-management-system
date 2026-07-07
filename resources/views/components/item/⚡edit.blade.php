<?php

use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Item;
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

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|max:255|min:3|unique:items,name' . ($this->item->id ? ',' . $this->item->id : ''),
            'section_id' =>'required|exists:sections,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        $this->item->update($validated);
        $this->dispatch('close-edit-modal');
        $this->dispatch('item-changed');
    }

    public function sections()
    {
        return Section::all();
    }

    public function categories($id)
    {
       if(!$id) return [];
       return Category::where('section_id', $id)->get();
    }

    public function subcategories($id)
    {
       if(!$id) return [];
       return Subcategory::where('category_id', $id)->get();
    }
};
?>


<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل العنصر" />

        <form id="form-edit" wire:submit.prevent="update">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم العنصر <span class="req">*</span></label>
                    <input type="text" class="input" id="edit-name" required value="{{ $this->name }}"
                        wire:model="name">
                    @error('name')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">السعر <span class="req">*</span></label>
                    <input type="number" step="0.01" class="input" id="edit-price" required value="{{ $this->price }}"
                        wire:model="price">
                    @error('price')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">القسم</label>
                    <select wire:model.live="section_id" class="select" id="edit-section" >
                        <option value="" wire:click="section_id = null">اختر القسم</option>
                        @foreach ($this->sections() as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('section_id')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                @if ($this->section_id)
                    <div class="field">
                        <label class="field-label">الفئة الأساسية</label>
                        <select wire:model.live="category_id" class="select" id="edit-category" >
                            <option value="" wire:click="category_id = null">اختر الفئة الأساسية</option>
                            @foreach ($this->categories($this->section_id) as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                @if ($this->category_id)
                    <div class="field">
                        <label class="field-label">الفئة الفرعية</label>
                        <select wire:model="subcategory_id" class="select" id="edit-subcategory" >
                            <option value="" wire:click="subcategory_id = null">اختر الفئة الفرعية</option>
                            @foreach ($this->subcategories($this->category_id) as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                        @error('subcategory_id')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <div class="field">
                    <label class="field-label">ترتيب العرض</label>
                    <input type="number" class="input" id="edit-order" min="0"
                        value="{{ $this->display_order }}" wire:model="display_order">
                    @error('display_order')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">الوصف</label>
                    <textarea class="textarea" id="edit-description" wire:model="description">{{ $this->description }}</textarea>
                    @error('description')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">حالة التنشيط</label>
                    <select class="select" id="edit-status" wire:model="status">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                    @error('status')
                        <span style="color: red;">{{ $message }}</span>
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
