<?php

use App\Models\Category;
use Livewire\Component;

new class extends Component {
    public $name = '';
    public $section = '';
    public $items_count = 0;
    public $display_order = 0;
    public $description = '';
    public $status = 1;

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|max:255|min:3|unique:categories,name',
            'section' => 'nullable|string|max:255',
            'items_count' => 'nullable|integer|min:0',
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        Category::create($validated);

        $this->dispatch('close-add-modal');

        $this->dispatch('category-changed');
        
        $this->reset();
    }
};
?>

<div id="modal-add" class="modal-overlay is-active" x-show="addOpen" x-cloak @click.self="addOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="إضافة فئة جديدة" />
        <form id="form-add" wire:submit.prevent="save">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم الفئة <span class="req">*</span></label>
                    <input wire:model="name" type="text" class="input" placeholder="اسم الفئة...">
                    @error('name')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">القسم</label>
                    <input wire:model="section" type="text" class="input" placeholder="القسم الخاص بالفئة...">
                    @error('section')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">عدد العناصر</label>
                    <input wire:model="items_count" type="number" class="input" placeholder="0" min="0" value="0">
                    @error('items_count')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">ترتيب العرض</label>
                    <input wire:model="display_order" type="number" class="input" placeholder="0" min="0" value="0">
                    @error('display_order')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">الوصف</label>
                    <textarea wire:model="description" class="textarea" placeholder="اكتب وصفاً مختصراً للفئة..."></textarea>
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
