<?php

use App\Models\Section;
use App\Services\Section\UpdateSectionAction;
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
        $this->status = (int) $this->section->status;
    }

    public function update(UpdateSectionAction $updateSection)
    {
        $validated = $this->validate([
            'name' => 'required|max:255|min:3|unique:sections,name' . ($this->section->id ? ',' . $this->section->id : ''),
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        $updateSection($this->section, $validated);
        $this->dispatch('close-edit-modal');
        $this->dispatch('section-changed');
    }
};
?>


<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل قسم المنيو" />

        <form id="form-edit" wire:submit.prevent="update">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">اسم قسم المنيو <span class="req">*</span></label>
                    <input type="text" class="input" id="edit-name" required value="{{ $this->name }}"
                        wire:model.defer="name">
                    @error('name')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">ترتيب العرض</label>
                    <input type="number" class="input" id="edit-order" min="0"
                        value="{{ $this->display_order }}" wire:model.defer="display_order">
                    @error('display_order')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">الوصف</label>
                    <textarea class="textarea" id="edit-description" wire:model.defer="description">{{ $this->description }}</textarea>
                    @error('description')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">حالة التنشيط</label>
                    <select class="select" id="edit-status" wire:model.defer="status">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                    @error('status')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
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
