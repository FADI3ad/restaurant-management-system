<?php

use App\Models\Section;
use Livewire\Component;

new class extends Component {
    public $name = '';
    public $order = 0;
    public $description = '';
    public $status = 1;

    public function save()
    {
        $this->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);
        
        Section::create([
            'name' => $this->name,
            'description' => $this->description,
            'display_order' => $this->order,
            'status' => $this->status,
        ]);
        $this->dispatch('section-changed'); 
    }

};
?>

<div id="modal-add" class="modal-overlay">
    <div class="modal-content">
        <x-modal-head-component title="إضافة قسم جديد" />
        <form id="form-add" onsubmit="event.preventDefault();  closeModal('modal-add');">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم القسم <span class="req">*</span></label>
                    <input wire:model="name" type="text" class="input"
                        placeholder="مثال: قسم المأكولات، المشروبات..." required>
                    @error('name')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror

                </div>
                <div class="field">
                    <label class="field-label">ترتيب العرض</label>
                    <input wire:model="order" type="number" class="input" placeholder="0" min="0"
                        value="0">
                </div>
                <div class="field">
                    <label class="field-label">الوصف</label>
                    <textarea wire:model="description" class="textarea" placeholder="اكتب وصفاً مختصراً للقسم..."></textarea>
                </div>
                <div class="field">
                    <label class="field-label">حالة التنشيط</label>
                    <select wire:model="status" class="select">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" onclick="closeModal('modal-add')">إلغاء</button>
                <button wire:click="save" class="btn btn--primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
