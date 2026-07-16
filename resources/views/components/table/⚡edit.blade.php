<?php

use App\Http\Requests\Table\UpdateTableRequest;
use App\Livewire\Forms\TableForm;
use App\Models\Table;
use App\Services\Table\UpdateTableAction;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public TableForm $form;

    #[On('edit-table-details')]
    public function getTableDetails($id)
    {
        $table = Table::findOrFail($id);
        $this->form->setData($table);
    }

    public function update(UpdateTableAction $updateTable)
    {
        $validated = $this->form->validate(UpdateTableRequest::rulesArray($this->form->table->id ?? null));

        $updateTable($this->form->table, $validated);

        $this->dispatch('close-edit-modal');
        $this->dispatch('table-changed');
    }
};
?>

<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false;"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل الطاولة" />
        <form wire:submit.prevent="update">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">رقم/اسم الطاولة <span class="req">*</span></label>
                    <input wire:model="form.table_number" type="text" class="input" placeholder="مثال: 1, A1...">
                    @error('form.table_number')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                
                <div class="field">
                    <label class="field-label">نوع الطاولة <span class="req">*</span></label>
                    <select wire:model="form.type" class="select">
                        <option value="Public">عام (Public)</option>
                        <option value="Private">خاص (Private)</option>
                    </select>
                    @error('form.type')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field" style="display: flex; gap: 1rem;">
                    <div style="flex: 1;">
                        <label class="field-label">أقل سعة <span class="req">*</span></label>
                        <input wire:model="form.min_capacity" type="number" min="1" class="input" placeholder="1">
                        @error('form.min_capacity')
                            <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                        @enderror
                    </div>
                    <div style="flex: 1;">
                        <label class="field-label">أقصى سعة <span class="req">*</span></label>
                        <input wire:model="form.max_capacity" type="number" min="1" class="input" placeholder="4">
                        @error('form.max_capacity')
                            <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="field">
                    <label class="field-label">حالة الطاولة <span class="req">*</span></label>
                    <select wire:model="form.status" class="select">
                        <option value="Available">متاح</option>
                        <option value="Maintenance">صيانة</option>
                    </select>
                    @error('form.status')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">ملاحظات (اختياري)</label>
                    <textarea wire:model="form.notes" class="textarea" placeholder="اكتب أي ملاحظات خاصة بهذه الطاولة..."></textarea>
                    @error('form.notes')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="editOpen = false">إلغاء</button>
                <button type="submit" class="btn btn--primary" @close-edit-modal.window="editOpen = false">تحديث</button>
            </div>
        </form>
    </div>
</div>
