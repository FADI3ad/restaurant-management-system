<?php

use App\Models\Section;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $section = '';

    public $name = '';
    public $display_order = 0;
    public $description = '';
    public $status = 1;

    #[On('edit-section-details')]
    public function getSectionDetails($id)
    {
        $section = Section::findOrfail($id);
        $this->section = $section;
        $this->setData();
    }

    public function setData()
    {
        $this->name = $this->section->name;
        $this->display_order = $this->section->display_order;
        $this->description = $this->section->description;
        $this->status = $this->section->status;
    }

    public function update()
    {

        $validated = $this->validate([
            'name' => 'required|max:255|min:3|unique:sections,name' . ($this->section->id ? ',' . $this->section->id : ''),
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        
        $this->section->update($validated);
        $this->dispatch('close-edit-modal');
        $this->dispatch('section-changed');
    }
};
?>  


<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل القسم" />

        <form id="form-edit" wire:submit.prevent="update">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم القسم <span class="req">*</span></label>
                    <input type="text" class="input" id="edit-name" required value="{{ $this->name }}"
                        wire:model.defer="name">
                    @error('name')
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
                        <option value="1" {{ $this->status == 1 ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ $this->status == 0 ? 'selected' : '' }}>غير نشط</option>
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
