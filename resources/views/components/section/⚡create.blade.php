<?php

use App\Models\Section;
use Livewire\Component;

new class extends Component {
    public $isOpen = 0;

    public $name = '';

    public $display_order = 0;

    public $description = '';

    public $status = 1;

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        $section = Section::create($validated);

        
        $this->dispatch('section-created');
        $this->reset();
    }
};
?>

<div id="modal-add" class="modal-overlay" >
    <div class="modal-content modal-md">
        <x-modal-head-component title="إضافة قسم جديد" />
        <form id="form-add" wire:submit.prevent="save">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم القسم <span class="req">*</span></label>
                    <input wire:model="name" type="text" class="input"
                        placeholder="مثال: قسم المأكولات، المشروبات...">
                    @error('name')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
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
                    <textarea wire:model="description" class="textarea" placeholder="اكتب وصفاً مختصراً للقسم..."></textarea>
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
                <button type="button" class="btn btn--ghost" onclick="closeModal('modal-add')">إلغاء</button>
                <button class="btn btn--primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
