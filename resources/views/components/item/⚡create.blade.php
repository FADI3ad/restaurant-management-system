<?php

use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Item;
use Livewire\Component;

new class extends Component {
    public $name = '';
    public $section_id = '';
    public $category_id = null;
    public $subcategory_id = null;
    public $price = '';
    public $display_order = 0;
    public $description = '';
    public $status = 1;


    
    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|max:255|min:3|unique:items,name',
            'section_id' =>'required|exists:sections,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        Item::create($validated);

        $this->dispatch('close-add-modal');
        $this->dispatch('item-changed');
        $this->reset();
    }

    public function sections()
    {
        return Section::all();
    }

    public function categories($id)
    {
       return Category::where('section_id', $id)->get();
    }

    public function subcategories($id)
    {
       return Subcategory::where('category_id', $id)->get();
    }
}
?>

<div id="modal-add" class="modal-overlay is-active" x-show="addOpen" x-cloak @click.self="addOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="إضافة عنصر جديد" />
        <form id="form-add" wire:submit.prevent="save">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم العنصر <span class="req">*</span></label>
                    <input wire:model="name" type="text" class="input" placeholder="اسم العنصر...">
                    @error('name')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">السعر <span class="req">*</span></label>
                    <input wire:model="price" type="number" step="0.01" class="input" placeholder="السعر...">
                    @error('price')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">القسم</label>
                    <select wire:model.live="section_id" class="select" >
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
                        <select wire:model.live="category_id" class="select" >
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
                        <select wire:model="subcategory_id" class="select" >
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
                    <input wire:model="display_order" type="number" class="input" placeholder="0" min="0"
                        value="0">
                    @error('display_order')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">الوصف</label>
                    <textarea wire:model="description" class="textarea" placeholder="اكتب وصفاً للعنصر..."></textarea>
                    @error('description')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">حالة التنشيط</label>
                    <select wire:model="status" class="select">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                    @error('status')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="addOpen = false">إلغاء</button>
                <button type="submit" class="btn btn--primary" @close-add-modal.window="addOpen = false">
                    حفظ
                </button>
            </div>
        </form>
    </div>
</div>
