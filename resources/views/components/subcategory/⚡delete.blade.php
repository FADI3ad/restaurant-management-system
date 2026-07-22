<?php

use App\Models\Subcategory;
use App\Services\Subcategory\DeleteSubcategoryAction;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public ?Subcategory $subcategory = null;

    #[On('confirm-subcategory-delete')]
    public function getSubcategoryForDeletion($id)
    {
        $this->subcategory = Subcategory::findOrFail($id);
    }

    public function delete(DeleteSubcategoryAction $deleteSubcategory)
    {
        $deleteSubcategory($this->subcategory);
        $this->dispatch('close-delete-modal');
        $this->dispatch('subcategory-changed');
        $this->subcategory = null;
    }
};
?>

<div id="modal-delete" class="modal-overlay is-active" x-show="deleteOpen" x-cloak @click.self="deleteOpen = false"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-sm">
        <x-modal-head-component title="تأكيد حذف الصنف الفرعي" />

        <form wire:submit.prevent="delete">
            <div class="modal-body modal-form-stack">
                <div style="text-align: center; padding: 10px 0;">
                    <div
                        style="background: var(--danger-soft); color: var(--danger); width: 64px; height: 64px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" style="width: 32px; height: 32px;">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                            </path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <h4 style="margin: 0 0 8px; color: var(--t-base); font-size: 16px; font-weight: 700;">هل أنت متأكد
                        من حذف الصنف الفرعي؟</h4>
                    <p style="margin: 0; color: var(--t-light); font-size: 14px; line-height: 1.5;">
                        سيتم حذف الصنف الفرعي <strong style="color: var(--t-base);">"{{ $this->subcategory?->name }}"</strong>
                        نهائياً. هذا الإجراء لا يمكن التراجع عنه!
                    </p>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="deleteOpen = false;">إلغاء</button>
                <button type="submit" class="btn btn--danger" @click="deleteOpen = false;">تأكيد الحذف</button>
            </div>
        </form>
    </div>
</div>
