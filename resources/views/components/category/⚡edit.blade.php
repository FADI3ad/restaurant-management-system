<?php

use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $category = '';

    public $name = '';
    public $section = '';
    public $items_count = 0;
    public $display_order = 0;
    public $description = '';
    public $status = 1;

    #[On('edit-category-details')]
    public function getCategoryDetails($id)
    {
        $category = Category::findOrfail($id);
        $this->category = $category;
        $this->setData();
    }

    public function setData()
    {
        $this->name = $this->category->name;
        $this->section = $this->category->section;
        $this->items_count = $this->category->items_count;
        $this->display_order = $this->category->display_order;
        $this->description = $this->category->description;
        $this->status = (int) $this->category->status;
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|max:255|min:3|unique:categories,name' . ($this->category->id ? ',' . $this->category->id : ''),
            'section' => 'nullable|string|max:255',
            'items_count' => 'nullable|integer|min:0',
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        $this->category->update($validated);
        $this->dispatch('close-edit-modal');
        $this->dispatch('category-changed');
    }
};
?>


<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل الفئة" />

        <form id="form-edit" wire:submit.prevent="update">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم الفئة <span class="req">*</span></label>
                    <input type="text" class="input" id="edit-name" required value="{{ $this->name }}"
                        wire:model.defer="name">
                    @error('name')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">القسم</label>
                    <input type="text" class="input" id="edit-section" value="{{ $this->section }}"
                        wire:model.defer="section">
                    @error('section')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">عدد العناصر</label>
                    <input type="number" class="input" id="edit-items-count" min="0" value="{{ $this->items_count }}"
                        wire:model.defer="items_count">
                    @error('items_count')
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
