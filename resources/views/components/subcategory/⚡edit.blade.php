<?php

use App\Models\Subcategory;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $subcategory = '';

    public $name = '';
    public $category_id = '';
    public $display_order = 0;
    public $description = '';
    public $status = 1;

    #[On('edit-subcategory-details')]
    public function getSubcategoryDetails($id)
    {
        $subcategory = Subcategory::findOrfail($id);
        $this->subcategory = $subcategory;
        $this->setData();
    }

    public function setData()
    {
        $this->name = $this->subcategory->name;
        $this->category_id = $this->subcategory->category_id;
        $this->display_order = $this->subcategory->display_order;
        $this->description = $this->subcategory->description;
        $this->status = (int) $this->subcategory->status;
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|max:255|min:3|unique:subcategories,name' . ($this->subcategory->id ? ',' . $this->subcategory->id : ''),
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        $this->subcategory->update($validated);
        $this->dispatch('close-edit-modal');
        $this->dispatch('subcategory-changed');
    }

    public function categories()
    {
        return Category::orderBy('display_order')->get();
    }
};
?>


<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل الفئة الفرعية" />

        <form id="form-edit" wire:submit.prevent="update">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم الفئة الفرعية <span class="req">*</span></label>
                    <input type="text" class="input" id="edit-name" required value="{{ $this->name }}"
                        wire:model.defer="name">
                    @error('name')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">الفئة الأساسية <span class="req">*</span></label>
                    <select class="select" id="edit-category" wire:model.defer="category_id">
                        <option value="">اختر الفئة الأساسية</option>
                        @foreach ($this->categories() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">ترتيب العرض</label>
                    <input type="number" class="input" id="edit-order" min="0"
                        value="{{ $this->display_order }}" wire:model.defer="display_order">
                    @error('display_order')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">الوصف</label>
                    <textarea class="textarea" id="edit-description" wire:model.defer="description">{{ $this->description }}</textarea>
                    @error('description')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">حالة التنشيط</label>
                    <select class="select" id="edit-status" wire:model.defer="status">
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
