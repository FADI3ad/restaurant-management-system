<?php

use App\Models\Subcategory;
use App\Models\Category;
use Livewire\Component;

new class extends Component {
    public $name = '';
    public $category_id = '';
    public $display_order = 0;
    public $description = '';
    public $status = 1;

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|max:255|min:3|unique:subcategories,name',
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        Subcategory::create($validated);

        $this->dispatch('close-add-modal');
        $this->dispatch('subcategory-changed');
        $this->reset();
    }

    public function categories()
    {
        return Category::orderBy('display_order')->get();
    }
};
?>

<div id="modal-add" class="modal-overlay is-active" x-show="addOpen" x-cloak @click.self="addOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="إضافة فئة فرعية جديدة" />
        <form id="form-add" wire:submit.prevent="save">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم الفئة الفرعية <span class="req">*</span></label>
                    <input wire:model="name" type="text" class="input" placeholder="اسم الفئة الفرعية...">
                    @error('name')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">الفئة الأساسية <span class="req">*</span></label>
                    <select wire:model="category_id" class="select">
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
                    <input wire:model="display_order" type="number" class="input" placeholder="0" min="0" value="0">
                    @error('display_order')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">الوصف</label>
                    <textarea wire:model="description" class="textarea" placeholder="اكتب وصفاً للفئة الفرعية..."></textarea>
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
