<?php

use App\Http\Requests\Table\StoreTableRequest;
use App\Models\Table;
use App\Services\Table\CreateTableAction;
use Livewire\Component;

new class extends Component {
    public $table_number = '';
    public $type = 'Public';
    public $min_capacity = 1;
    public $max_capacity = 4;
    public $status = 'Available';
    public $notes = '';

    public function save(CreateTableAction $createTable)
    {
        $validated = $this->validate(StoreTableRequest::rulesArray());

        $createTable($validated);

        $this->dispatch('close-add-modal');
        $this->dispatch('table-changed');
        $this->reset();
    }
};
?>

<div id="modal-add" class="modal-overlay is-active" x-show="addOpen" x-cloak @click.self="addOpen = false;"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="إضافة طاولة جديدة" />
        <form id="form-add" wire:submit.prevent="save">
            <div class="modal-body modal-form-stack">
                <div class="field">
                    <label class="field-label">رقم/اسم الطاولة <span class="req">*</span></label>
                    <input wire:model="table_number" type="text" class="input" placeholder="مثال: 1, A1...">
                    @error('table_number')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                
                <div class="field">
                    <label class="field-label">نوع الطاولة <span class="req">*</span></label>
                    <select wire:model="type" class="select">
                        <option value="Public">عام (Public)</option>
                        <option value="Private">خاص (Private)</option>
                    </select>
                    @error('type')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field" style="display: flex; gap: 1rem;">
                    <div style="flex: 1;">
                        <label class="field-label">أقل سعة <span class="req">*</span></label>
                        <input wire:model="min_capacity" type="number" min="1" class="input" placeholder="1">
                        @error('min_capacity')
                            <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                        @enderror
                    </div>
                    <div style="flex: 1;">
                        <label class="field-label">أقصى سعة <span class="req">*</span></label>
                        <input wire:model="max_capacity" type="number" min="1" class="input" placeholder="4">
                        @error('max_capacity')
                            <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="field">
                    <label class="field-label">حالة الطاولة <span class="req">*</span></label>
                    <select wire:model="status" class="select">
                        <option value="Available">متاح (Available)</option>
                        <option value="Occupied">مشغول (Occupied)</option>
                        <option value="Reserved">محجوز (Reserved)</option>
                        <option value="Maintenance">صيانة (Maintenance)</option>
                    </select>
                    @error('status')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">ملاحظات (اختياري)</label>
                    <textarea wire:model="notes" class="textarea" placeholder="اكتب أي ملاحظات خاصة بهذه الطاولة..."></textarea>
                    @error('notes')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="addOpen = false">إلغاء</button>
                <button type="submit" class="btn btn--primary" @close-add-modal.window="addOpen = false">حفظ</button>
            </div>
        </form>
    </div>
</div>
